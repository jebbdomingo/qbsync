<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncModelEntityDeposit extends ComQbsyncQuickbooksModelEntityRow
{
    /**
     * Sync unsynced sales receipts
     * 
     * @return boolean
     */
    public function syncSalesReceipts()
    {
        // Sync SalesReceipts added to this Deposit that are currently unsynced
        $salesreceipts = $this->getObject('com:qbsync.model.salesreceipts')
            ->deposit_id($this->id)
            ->synced('no')
            ->fetch()
        ;
        
        foreach ($salesreceipts as $salesreceipt)
        {
            if ($salesreceipt->sync() === false)
            {
                $error = $salesreceipt->getStatusMessage();
                $this->setStatusMessage($error ? $error : 'SalesReceipt Sync Action Failed', 'error');

                return false;
            }
        }

        return true;
    }

    /**
     * Sync to QBO
     *
     * @throws Exception QBO sync error
     * 
     * @return boolean
     */
    public function sync()
    {
        if ($this->synced == 'yes')
        {
            $this->setStatusMessage("Deposit #{$this->id} is already synced");

            return false;
        }

        $Deposit = new QuickBooks_IPP_Object_Deposit();
        $Deposit->setDepositToAccountRef($this->DepositToAccountRef);
        $Deposit->setDepartmentRef($this->DepartmentRef);
        $Deposit->setTxnDate($this->TxnDate);

        $order_ids = array();

        // Sync unsynced sales receipts added to this deposit
        if (!$this->syncSalesReceipts()) {
            return false;
        }

        // Add the sales receipts as line items of this deposit
        foreach ($this->getObject('com:qbsync.model.salesreceipts')->deposit_id($this->id)->fetch() as $line)
        {
            $order_ids[] = $line->DocNumber;

            $Line = new QuickBooks_IPP_Object_Line();
            $Line->setDetailType('LinkedTxn');
            
            $Details = new QuickBooks_IPP_Object_LinkedTxn();
            $Details->setTxnId($line->qbo_salesreceipt_id); // Sales Receipt ID in QBO
            $Details->setTxnType('SalesReceipt');
            $Details->setTxnLineId(0);

            $Line->addLinkedTxn($Details);

            $Deposit->addLine($Line);
        }

        $DepositService = new QuickBooks_IPP_Service_Deposit();
        if ($resp = $DepositService->add($this->Context, $this->realm, $Deposit))
        {
            if ($this->_syncTransfers($order_ids) === true)
            {
                $this->synced = 'yes';
                $this->save();
            }
        }
        else $this->setStatusMessage('Deposit Sync Error: ' . $DepositService->lastError($this->Context));

        return true;
    }

    /**
     * Sync releated account funds transfers
     *
     * @param array $order_ids
     *
     * @return boolean
     */
    protected function _syncTransfers(array $order_ids)
    {
        $transfers = $this->getObject('com:qbsync.model.transfers')->order_ids($order_ids)->fetch();

        foreach ($transfers as $transfer)
        {
            if ($transfer->sync() === false)
            {
                $this->setStatusMessage("Syncing Related Transfer Transaction #{$transfer->id} failed for Sales Receipt with Doc Number {$transfer->order_id}");

                return false;
            }
        }

        return true;
    }
}