<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2017 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

use QuickBooksOnline\API\Facades\Item;

class ComQbsyncServiceItem extends ComQbsyncQuickbooksModelEntityAbstract
{
    /**
     * Create record
     *
     * @param array  $data
     * @throws Exception
     * @return string
     */
    public function create(array $data)
    {
        // Add a record
        $entity = Item::create(array(
            'Type'              => $data['Type'],
            'Name'              => $data['Name'],
            'Description'       => $data['Description'],
            'UnitPrice'         => $data['UnitPrice'],
            'PurchaseCost'      => $data['PurchaseCost'],
            'TrackQtyOnHand'    => $data['TrackQtyOnHand'],
            'QtyOnHand'         => $data['QtyOnHand'],
            'IncomeAccountRef'  => array(
                'value' => $data['IncomeAccountRef'],
                'name'  => 'Sales income'
            ),
            'ExpenseAccountRef' => array(
                'value' => $data['ExpenseAccountRef'],
                'name'  => 'Cost of goods sold'
            ),
            'AssetAccountRef'   => array(
                'value' => $data['AssetAccountRef'],
                'name'  => 'Inventory asset'
            ),
            'InvStartDate'      => $data['InvStartDate'],
        ));

        $result = $this->add($entity, 'Error in creating Item on QBO: ');

        return $result->Id;
    }

    /**
     * Update customer record
     *
     * @param array  $data
     * @throws KControllerExceptionActionFailed
     * @return void
     */
    public function update(array $data)
    {
        // Get the existing entity first (you need the latest SyncToken value)
        $entity = $this->fetch('Item', $data['ItemRef']);

        $updated_entity = Item::update($entity, array(
            // If you are going to do a full Update, set sparse to false
            'sparse'      => true,
            'Name'        => $data['Name'],
            'Description' => $data['Description'],
            'UnitPrice'   => $data['UnitPrice'],
        ));

        $result = $this->edit($updated_entity, 'Error in updating Item on QBO: ');
    }
}
