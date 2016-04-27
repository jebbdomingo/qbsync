<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncModelEntitySalesreceipt extends ComQbsyncQuickbooksModelEntityRow
{
    /**
     *
     * @return KModelEntityRowset
     */
    public function getLineItems()
    {
        return $this->getObject('com:qbsync.model.salesreceiptlines')->SalesReceipt($this->id)->fetch();
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
        if ($this->synced == 'yes') {
            $this->setStatusMessage("Sales Receipt #{$this->id} with DocNumber {$this->DocNumber} is already synced");
            return false;
        }

        $SalesReceipt = new QuickBooks_IPP_Object_SalesReceipt();
        $SalesReceipt->setDepositToAccountRef($this->DepositToAccountRef);
        $SalesReceipt->setDepartmentRef($this->DepartmentRef);
        $SalesReceipt->setDocNumber("NUC-SR-{$this->DocNumber}");
        $SalesReceipt->setTxnDate($this->TxnDate);

        if ($this->CustomerRef > 0) {
            $SalesReceipt->setCustomerRef($this->CustomerRef);
        }

        $items = array();

        foreach ($this->getLineItems() as $line)
        {
            $Line = new QuickBooks_IPP_Object_Line();
            $Line->setDetailType('SalesItemLineDetail');
            $Line->setDescription($line->Description);
            $Line->setAmount($line->Amount);

            $Details = new QuickBooks_IPP_Object_SalesItemLineDetail();
            $Details->setItemRef($line->ItemRef);
            $Details->setQty($line->Qty);

            $items[$line->ItemRef] += (int) $line->Qty;

            $Line->addSalesItemLineDetail($Details);

            $SalesReceipt->addLine($Line);
        }

        $SalesReceiptService = new QuickBooks_IPP_Service_SalesReceipt();

        if ($resp = $SalesReceiptService->add($this->Context, $this->realm, $SalesReceipt))
        {
            $this->synced              = 'yes';
            $this->qbo_salesreceipt_id = QuickBooks_IPP_IDS::usableIDType($resp);
            $this->save();

            // Sync items to get updated quantity
            $this->_syncItems($items);

            return true;
        }
        else $this->setStatusMessage('SalesReceipt Sync Error: ' . $SalesReceiptService->lastError($this->Context));

        return false;
    }

    /**
     * Sync items
     *
     * @param integer $qtyPurchased
     *
     * @throws Exception
     *
     * @return boolean
     */
    protected function _syncItems($processedItems)
    {
        $ids   = array_keys($processedItems);
        $items = $this->getObject('com:qbsync.model.items')->ItemRef($ids)->fetch();

        foreach ($items as $item)
        {
            if ($item->sync() === false)
            {
                $error = $item->getStatusMessage();
                throw new Exception($error ? $error : 'Sync Action Failed', 'error');
            }

            // Reduced quantity purchased counter for each sales receipt line items synced
            // QtyOnHand was updated (reduced) from QBO accordingly as a result of the sync
            $item->quantity_purchased -= (int) $processedItems[$item->ItemRef];
            $item->save();
        }

        return true;
    }

    /**
     * Delete
     *
     * @return boolean
     */
    public function delete()
    {
        // Delete related sales receipt line items
        foreach ($this->getLineItems() as $line)
        {
            if (!$line->delete())
            {
                $this->setStatusMessage("Deleting Sales Receipt Item #{$line->id} failed");
                return false;
            }
        }

        // Delete the transfer transactions that are related to the sales receipt
        $transfers = $this->getObject('com:qbsync.model.transfers')->order_id($this->DocNumber)->fetch();

        foreach ($this->getObject('com:qbsync.model.transfers')->order_id($this->DocNumber)->fetch() as $transfer)
        {
            if (!$transfer->delete())
            {
                $this->setStatusMessage("Deleting Related Transfer Transaction #{$transfer->id} failed for Sales Receipt with Doc Number {$this->DocNumber}");
                return false;
            }
        }

        // Delete sales receipt
        return parent::delete();
    }
}