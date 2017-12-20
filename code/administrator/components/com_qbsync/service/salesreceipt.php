<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2017 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */        https://github.com/nooku/nooku-framework for the canonical source repository

use QuickBooksOnline\API\Facades\SalesReceipt;

class ComQbsyncServiceSalesreceipt extends ComQbsyncQuickbooksModelEntityAbstract
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
        $datum = array(
            'CustomerRef'         => $data['CustomerRef'],
            'DepositToAccountRef' => $data['DepositToAccountRef'],
            'DepartmentRef'       => $data['DepartmentRef'],
            'DocNumber'           => "NUC-SR-{$data['DocNumber']}",
            'TxnDate'             => $data['TxnDate'],

            'ShipAddr' => array(array(
                'Line1' => $data['ShipAddr']['Line1'],
                'Line2' => $data['ShipAddr']['Line2'],
                'Line3' => $data['ShipAddr']['Line3'],
            )),
        );

        $items = array();
        foreach ($data['lines'] as $line)
        {
            $datum['Line'][] = array(
                "Description"         => $line['Description'],
                "Amount"              => (float) $line['Amount'],
                "DetailType"          => 'SalesItemLineDetail',
                "SalesItemLineDetail" => array(
                    "Qty"     => $line['Qty'],
                    "ItemRef" => array(
                        "value" => $line['ItemRef'],
                    )
                )
            );

            if ( 'SHIPPING_ITEM_ID' != $line['ItemRef'] ) {
                @$items[] = $line['ItemRef'];
            }
        }

        $entity = SalesReceipt::create($datum);
        $result = $this->add($entity, 'Error in creating SalesReceipt on QBO: ');

        // Sync items to get updated quantity
        $this->_syncItems($items);

        return $result->Id;
    }

    /**
     * Sync items
     *
     * @throws KControllerExceptionActionFailed
     * @param  array $ids
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
