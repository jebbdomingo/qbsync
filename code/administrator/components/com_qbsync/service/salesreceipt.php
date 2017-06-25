<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */        https://github.com/nooku/nooku-framework for the canonical source repository

class ComQbsyncServiceSalesreceipt extends ComQbsyncQuickbooksModelEntityRow
{
    /**
     * Create salesreceipt record
     *
     * @param array  $data
     * @throws KControllerExceptionActionFailed
     * @return boolean
     */
    public function create(array $data)
    {
        $config = $this->getObject('com:nucleonplus.accounting.service.data');
        
        // Create the salesreceipt object
        $SalesReceipt = new QuickBooks_IPP_Object_SalesReceipt();
        $SalesReceipt->setDepositToAccountRef($data['DepositToAccountRef']);
        $SalesReceipt->setDepartmentRef($data['DepartmentRef']);
        $SalesReceipt->setDocNumber("NUC-SR-{$data['DocNumber']}");
        $SalesReceipt->setTxnDate($data['TxnDate']);

        // Set shipping address
        $ShipAddr = new QuickBooks_IPP_Object_ShipAddr();
        $ShipAddr->setLine1($data['ShipAddr']['Line1']);
        $ShipAddr->setLine2($data['ShipAddr']['Line2']);
        $ShipAddr->setLine3($data['ShipAddr']['Line3']);
        $SalesReceipt->setShipAddr($ShipAddr);

        if ($data['CustomerRef']) {
            $SalesReceipt->setCustomerRef($data['CustomerRef']);
        }

        $items = array();
        foreach ($data['lines'] as $line)
        {
            $Line = new QuickBooks_IPP_Object_Line();
            $Line->setDescription($line['Description']);
            $Line->setDetailType('SalesItemLineDetail');
            $Line->setAmount((float) $line['Amount']);

            $Details = new QuickBooks_IPP_Object_SalesItemLineDetail();
            $Details->setQty($line['Qty']);
            $Details->setItemRef($line['ItemRef']);
            $Line->addSalesItemLineDetail($Details);
            
            @$items[] = $line['ItemRef'];

            $SalesReceipt->addLine($Line);
        }

        $SalesReceiptService = new QuickBooks_IPP_Service_SalesReceipt();

        if ($resp = $SalesReceiptService->add($this->getQboContext(), $this->getQboRealm(), $SalesReceipt))
        {
            // Sync corresponding account transfers in online salesreceipts
            // if ($this->transaction_type == 'online') {
            //     $this->_syncTransfers($this->DocNumber);
            // }

            // Sync items to get updated quantity
            $this->_syncItems($items);

            return true;
        }
        else throw new KControllerExceptionActionFailed('SalesReceipt Creation Error: ' . $SalesReceiptService->lastError($this->Context));
    }

    /**
     * Sync releated account funds transfers
     *
     * @param integer $entity_id
     *
     * @return boolean
     */
    protected function _syncTransfers($entity_id)
    {
        $transfers = $this->getObject('com:qbsync.model.transfers')
            ->entity('order')
            ->entity_id($entity_id)
            ->fetch()
        ;

        foreach ($transfers as $transfer)
        {
            if ($transfer->sync() === false)
            {
                $this->setStatusMessage("Syncing Related Transfer Transaction #{$transfer->id} failed for Sales Receipt with Doc Number {$transfer->entity_id}");

                return false;
            }
        }

        return true;
    }

    /**
     * Sync items
     *
     * @param array $ids
     *
     * @throws Exception
     *
     * @return void
     */
    protected function _syncItems(array $ids)
    {
        $items = $this->getObject('com:qbsync.model.items')->ItemRef($ids)->fetch();

        foreach ($items as $item)
        {
            if ($item->sync() === false)
            {
                $error = $item->getStatusMessage();
                throw new KControllerExceptionActionFailed($error ? $error : 'Sync Action Failed');
            }
        }
    }
}
