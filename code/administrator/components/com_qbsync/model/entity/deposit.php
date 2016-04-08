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
     *
     * @return KModelEntityRowset
     */
    public function getLineItems()
    {
        return $this->getObject('com:qbsync.model.salesreceipts')->deposit_id($this->id)->fetch();
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
        
        foreach ($this->getLineItems() as $line)
        {
            $Line = new QuickBooks_IPP_Object_Line();
            $Line->setDetailType('LinkedTxn');
            //$Line->setAmount($line->Amount);
            
            $Details = new QuickBooks_IPP_Object_LinkedTxn();
            $Details->setTxnId($line->qbo_salesreceipt_id); // Sales Receipt ID in QBO
            $Details->setTxnType('SalesReceipt');
            $Details->setTxnLineId(0);

            $Line->addLinkedTxn($Details);

            /*$txn = new stdClass;
            $txn->TxnId   = $line->qbo_salesreceipt_id; // Sales Receipt ID in QBO
            $txn->TxnType = 'SalesReceipt';
            $txn->TxnLineId = 0;
            $Line->addLinkedTxn($txn);*/

            $Deposit->addLine($Line);
        }

        //var_dump($Deposit);die('test');

        $DepositService = new QuickBooks_IPP_Service_Deposit();

        if ($resp = $DepositService->add($this->Context, $this->realm, $Deposit))
        {
            $this->synced = 'yes';
            $this->save();

            //$this->_syncTransfers();

            return true;
        }
        else $this->setStatusMessage($DepositService->lastError($this->Context));

        return false;
    }

    /**
     * Sync releated account funds transfers
     *
     * @return boolean
     */
    /*protected function _syncTransfers()
    {
        $transfers = $this->getObject('com:qbsync.model.transfers')->order_id($this->DocNumber)->fetch();

        foreach ($this->getObject('com:qbsync.model.transfers')->order_id($this->DocNumber)->fetch() as $transfer)
        {
            if (!$transfer->sync())
            {
                $this->setStatusMessage("Syncing Related Transfer Transaction #{$transfer->id} failed for Sales Receipt with Doc Number {$this->DocNumber}");
                return false;
            }
        }
    }*/
}