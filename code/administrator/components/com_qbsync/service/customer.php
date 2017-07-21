<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncServiceCustomer extends ComQbsyncQuickbooksModelEntityRow
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
        // Create the customer object
        $Customer = new QuickBooks_IPP_Object_Customer();

        // Make display name unique
        $Customer->setDisplayName($data['DisplayName']);
        $Customer->setPrintOnCheckName($data['PrintOnCheckName']);

        // Phone #
        $PrimaryPhone = new QuickBooks_IPP_Object_PrimaryPhone();
        $PrimaryPhone->setFreeFormNumber($data['PrimaryPhone']);
        $Customer->setPrimaryPhone($PrimaryPhone);

        // Mobile #
        $Mobile = new QuickBooks_IPP_Object_Mobile();
        $Mobile->setFreeFormNumber($data['Mobile']);
        $Customer->setMobile($Mobile);

        // Bill address
        $BillAddr = new QuickBooks_IPP_Object_BillAddr();
        $BillAddr->setLine1($data['Line1']);
        $BillAddr->setCity($data['City']);
        $BillAddr->setState($data['State']);
        $BillAddr->setPostalCode($data['PostalCode']);
        $BillAddr->setCountry($data['Country']);
        $Customer->setBillAddr($BillAddr);

        // Email
        $PrimaryEmailAddr = new QuickBooks_IPP_Object_PrimaryEmailAddr();
        $PrimaryEmailAddr->setAddress($data['PrimaryEmailAddr']);
        $Customer->setPrimaryEmailAddr($PrimaryEmailAddr);

        $CustomerService = new QuickBooks_IPP_Service_Customer();
        $resp = $CustomerService->add($this->getQboContext(), $this->getQboRealm(), $Customer);
        // $resp = false;

        if ($resp) {
            return QuickBooks_IPP_IDS::usableIDType($resp);
        } else {
            throw new Exception('Error in creating Customer in QBO: ' . $CustomerService->lastError($this->getQboContext()));
        }
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
        $Customer = $this->get($data['CustomerRef']);

        // Make display name unique
        $Customer->setDisplayName($data['DisplayName']);
        $Customer->setPrintOnCheckName($data['PrintOnCheckName']);
        $Customer->setActive($data['Active'] ? 'true' : 'false');

        // Phone #
        $PrimaryPhone = $Customer->getPrimaryPhone();
        if (!$PrimaryPhone)
        {
            $PrimaryPhone = new QuickBooks_IPP_Object_PrimaryPhone();
            $PrimaryPhone->setFreeFormNumber($data['PrimaryPhone']);
            $Customer->setPrimaryPhone($PrimaryPhone);
        }
        else $PrimaryPhone->setFreeFormNumber($data['PrimaryPhone']);

        // Mobile #
        $Mobile = $Customer->getMobile();
        if (!$Mobile)
        {
            $Mobile = new QuickBooks_IPP_Object_Mobile();
            $Mobile->setFreeFormNumber($data['Mobile']);
            $Customer->setMobile($Mobile);
        }
        else $Mobile->setFreeFormNumber($data['Mobile']);

        // Bill address
        $BillAddr = $Customer->getBillAddr();
        $BillAddr->setLine1($data['Line1']);
        $BillAddr->setCity($data['City']);
        $BillAddr->setState($data['State']);
        $BillAddr->setPostalCode($data['PostalCode']);
        $BillAddr->setCountry($data['Country']);

        // Email
        $PrimaryEmailAddr = $Customer->getPrimaryEmailAddr();
        $PrimaryEmailAddr->setAddress($data['PrimaryEmailAddr']);

        $CustomerService = new QuickBooks_IPP_Service_Customer();

        $resp = $CustomerService->update($this->getQboContext(), $this->getQboRealm(), $Customer->getId(), $Customer);

        if (!$resp) {
            throw new KControllerExceptionActionFailed('Error in updating Customer in QBO: ' . $CustomerService->lastError($this->getQboContext()));
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
        $CustomerService = new QuickBooks_IPP_Service_Customer();

        // Get the existing customer first (you need the latest SyncToken value)
        $customers = $CustomerService->query($this->getQboContext(), $this->getQboRealm(), "SELECT * FROM Customer WHERE Id = '{$id}' ");

        if (!count($customers)) {
            return false;
        } else {
            return $customers[0];
        }
    }
}
