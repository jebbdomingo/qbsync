<?php
/**
 * Nucleon Plus - QBO
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2017 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncServiceJournal extends ComQbsyncQuickbooksModelEntityRow
{
    /**
     * Create customer record
     *
     * @param array  $data
     * @throws Exception
     * @return string
     */
    public function create(array $data)
    {
        // Main journal entry object
        $JournalEntry = $this->_createJournalEntry($data);

        // Debit line
        $Line = $this->_createDebitLine($data);
        $JournalEntry->addLine($Line);

        // Credit line
        $Line = $this->_createCreditLine($data);
        $JournalEntry->addLine($Line);

        // Create the Journal Entry
        return $this->_add($JournalEntry, $data);
    }

    /**
     * Create main journal entry object
     *
     * @param array $data
     *
     * @return this
     */
    protected function _createJournalEntry($data)
    {
        $JournalEntry = new QuickBooks_IPP_Object_JournalEntry();
        $JournalEntry->setDocNumber($data['DocNumber']);
        $JournalEntry->setTxnDate($data['Date']);

        return $JournalEntry;
    }

    /**
     * Create debit line
     *
     * @param array $data
     *
     * @return QuickBooks_IPP_Object_Line
     */
    protected function _createDebitLine(array $data)
    {
        $Line = new QuickBooks_IPP_Object_Line();
        $Line->setDescription($data['DebitDescription']);
        $Line->setAmount($data['DebitAmount']);
        $Line->setDetailType('JournalEntryLineDetail');

        $Detail = new QuickBooks_IPP_Object_JournalEntryLineDetail();
        $Detail->setPostingType('Debit');
        $Detail->setAccountRef($data['DebitAccount']);

        $Line->addJournalEntryLineDetail($Detail);

        return $Line;
    }

    /**
     * Create credit line
     *
     * @param array $data
     *
     * @return QuickBooks_IPP_Object_Line
     */
    protected function _createCreditLine(array $data)
    {
        $Line = new QuickBooks_IPP_Object_Line();
        $Line->setDescription($data['CreditDescription']);
        $Line->setAmount($data['CreditAmount']);
        $Line->setDetailType('JournalEntryLineDetail');

        $Detail = new QuickBooks_IPP_Object_JournalEntryLineDetail();
        $Detail->setPostingType('Credit');
        $Detail->setAccountRef($data['CreditAccount']);

        $Line->addJournalEntryLineDetail($Detail);

        return $Line;
    }

    /**
     * Create Journal Entry
     *
     * @param QuickBooks_IPP_Object_JournalEntry $JournalEntry
     * @param array                              $data
     */
    protected function _add(QuickBooks_IPP_Object_JournalEntry $JournalEntry, $data)
    {
        $JournalEntryService = new QuickBooks_IPP_Service_JournalEntry();
        $resp = $JournalEntryService->add($this->getQboContext(), $this->getQboRealm(), $JournalEntry);

        if ($resp) {
            return QuickBooks_IPP_IDS::usableIDType($resp);
        } else {
            $data = print_r($data, true);
            throw new KControllerExceptionActionFailed('Error creating the Journal Entry in QBO: ' . $TransferService->lastError($this->getQboContext()) . $data);
        }
    }
}
