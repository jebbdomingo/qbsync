<?php
/**
 * Nucleon Plus - QBO
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2017 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

use QuickBooksOnline\API\Facades\JournalEntry;

class ComQbsyncServiceJournalEntry extends ComQbsyncQuickbooksModelEntityAbstract
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
        $JournalEntry['Line'][] = $this->_createDebitLine($data);
        // $Line = $this->_createDebitLine($data);
        // $JournalEntry->addLine($Line);

        // Credit line
        $JournalEntry['Line'][] = $this->_createCreditLine($data);
        // $Line = $this->_createCreditLine($data);
        // $JournalEntry->addLine($Line);

        // Create the Journal Entry
        return $this->_add($JournalEntry);
        // return $this->_add($JournalEntry, $data);
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
        $datum = array(
            'DocNumber' => $data['DocNumber'],
            'TxnDate'   => $data['Date'],
        );

        return $datum;
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
        $datum = array(
            "Description" => $data['DebitDescription'],
            "Amount"      => (float) $data['DebitAmount'],
            "DetailType"  => 'JournalEntryLineDetail',

            "JournalEntryLineDetail" => array(
                "PostingType" => 'Debit',
                "AccountRef"  => $data['DebitAccount']
            )
        );

        return $datum;
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
        $datum = array(
            "Description" => $data['CreditDescription'],
            "Amount"      => (float) $data['CreditAmount'],
            "DetailType"  => 'JournalEntryLineDetail',

            "JournalEntryLineDetail" => array(
                "PostingType" => 'Credit',
                "AccountRef"  => $data['CreditAccount']
            )
        );

        return $datum;
    }

    /**
     * Create Journal Entry
     *
     * @throws KControllerExceptionActionFailed
     * @param array $JournalEntry
     * @return void
     */
    protected function _add(array $JournalEntry)
    {
        $entity = JournalEntry::create($JournalEntry);
        $result = $this->add($entity, 'Error in creating JournalEntry on QBO: ');

        return $result->Id;
    }
}
