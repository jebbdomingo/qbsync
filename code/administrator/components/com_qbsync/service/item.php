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
        $data = Item::create(array(
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

        $result = $this->getDataService()->Add($data);
        $error  = $this->getDataService()->getLastError();

        if ($error)
        {
            $error_message = "The Status code is: {$error->getHttpStatusCode()}\n";
            $error_message .= "The Helper message is: {$error->getOAuthHelperError()}\n";
            $error_message .= "The Response message is: {$error->getResponseBody()}\n";

            throw new KControllerExceptionActionFailed('Error in creating Item in QBO: ' . $error_message);
        }
        else return $result->Id;
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
        $entity = $this->get($data['ItemRef']);

        $updated_entity = Item::update($entity, array(
            // If you are going to do a full Update, set sparse to false
            'sparse'      => true,
            'Name'        => $data['Name'],
            'Description' => $data['Description'],
            'UnitPrice'   => $data['UnitPrice'],
        ));

        $result = $this->getDataService()->Update($updated_entity);
        $error  = $this->getDataService()->getLastError();

        if ($error != null)
        {
            $error_message = "The Status code is: {$error->getHttpStatusCode()}\n";
            $error_message .= "The Helper message is: {$error->getOAuthHelperError()}\n";
            $error_message .= "The Response message is: {$error->getResponseBody()}\n";
            
            throw new KControllerExceptionActionFailed('Error in updating Item in QBO: ' . $error_message);
        }
    }

    /**
     * Get customer record
     *
     * @param  mixed $id]
     *
     * @return bool|object
     */
    public function get($id)
    {
        $result   = false;
        $entities = $this->getDataService()->Query("SELECT * FROM Item where Id='{$id}'");
        $error    = $this->getDataService()->getLastError();

        if ($error)
        {
            $error_message = "The Status code is: {$error->getHttpStatusCode()}\n";
            $error_message .= "The Helper message is: {$error->getOAuthHelperError()}\n";
            $error_message .= "The Response message is: {$error->getResponseBody()}\n";
            
            throw new KControllerExceptionActionFailed('Error in querying Items in QBO: ' . $error_message);
        }

        if (!empty($entities)) {
            // Get the first element
            $result = reset($entities);
        }

        return $result;
    }
}
