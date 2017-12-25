<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2017 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

use QuickBooksOnline\API\Facades\Customer;

class ComQbsyncServiceCustomer extends ComQbsyncQuickbooksModelEntityAbstract
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
        // Add a customer
        $customer = Customer::create(array(
            "DisplayName"        => $data['DisplayName'],
            'PrintOnCheckName'   => $data['PrintOnCheckName'],
            "PrimaryPhone"       => array(
                "FreeFormNumber" => $data['PrimaryPhone']
            ),
            "Mobile" => array(
                "FreeFormNumber" => $data['Mobile']
            ),
            "PrimaryEmailAddr" => array(
                "Address" => $data['PrimaryEmailAddr']
            ),
            "BillAddr" => array(
                "Line1"      =>  $data['Line1'],
                "City"       =>  $data['City'],
                "Country"    =>  $data['Country'],
                "PostalCode" =>  $data['PostalCode']
            ),
        ));

        $result = $this->add($customer, 'Error in creating Customer on QBO: ');

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
        // Get the existing customer first (you need the latest SyncToken value)
        $customer = $this->fetch('Customer', $data['CustomerRef']);

        $updated_customer = Customer::update($customer, array(
            // If you are going to do a full Update, set sparse to false
            'sparse'           => true,
            'DisplayName'      => $data['DisplayName'],
            'PrintOnCheckName' => $data['PrintOnCheckName'],
            'Active'           => $data['Active'] ? 'true' : 'false',
            "PrimaryPhone"     => array(
                "FreeFormNumber" => $data['PrimaryPhone']
            ),
            "Mobile" => array(
                "FreeFormNumber" => $data['Mobile']
            ),
            "PrimaryEmailAddr" => array(
                "Address" => $data['PrimaryEmailAddr']
            ),
            "BillAddr" => array(
                "Line1"      =>  $data['Line1'],
                "City"       =>  $data['City'],
                "Country"    =>  $data['Country'],
                "PostalCode" =>  $data['PostalCode']
            )
        ));

        $result = $this->edit($updated_customer, 'Error in updating Customer on QBO: ');
    }

    /**
     * Get customer
     *
     * @param  mixed $CustomerRef
     * @return bool|QuickBooksOnline\API\Facades
     */
    public function get($CustomerRef)
    {
        return $this->fetch('Customer', $CustomerRef);
    }
}
