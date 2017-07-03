<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */        https://github.com/nooku/nooku-framework for the canonical source repository

class ComQbsyncServiceCustomer extends ComQbsyncQuickbooksModelEntityRow
{
    /**
     * Create customer record
     *
     * @param array  $data
     * @throws KControllerExceptionActionFailed
     * @return string
     */
    public function create(array $data)
    {
        $config = $this->getObject('com:nucleonplus.accounting.service.data');

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

        if ($resp) {
            return QuickBooks_IPP_IDS::usableIDType($resp);
        } else {
            throw new Exception('Error in creating Customer in QBO: ' . $CustomerService->lastError($this->getQboContext()));
        }
    }
}
