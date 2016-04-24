<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncModelEntityEmployee extends ComQbsyncQuickbooksModelEntityRow
{
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
            $this->setStatusMessage("Employee #{$this->id} is already synced");
            
            return false;
        }

        $EmployeeService = new QuickBooks_IPP_Service_Employee();

        if ($this->action == 'add')
        {
            $Employee = $this->_buildEmployee(new QuickBooks_IPP_Object_Employee());
            if ($resp = $EmployeeService->add($this->Context, $this->realm, $Employee))
            {
                $this->synced      = 'yes';
                $this->EmployeeRef = QuickBooks_IPP_IDS::usableIDType($resp);
                $this->save();
                return true;
            }
            else $this->setStatusMessage($EmployeeService->lastError($this->Context));
        }
        elseif ($this->action == 'update')
        {
            $Employees = $EmployeeService->query($this->Context, $this->realm, "SELECT * FROM Employee WHERE Id = '{$this->EmployeeRef}' ");
            $Employee  = $this->_buildEmployee($Employees[0]);
            if ($EmployeeService->update($this->Context, $this->realm, $Employee->getId(), $Employee))
            {
                $this->synced = 'yes';
                $this->save();
                return true;
            }
            else $this->setStatusMessage('Employee Sync Error: ' . $EmployeeService->lastError($this->Context));
        }

        return false;
    }

    /**
     * Build the Employee object
     *
     * @param QuickBooks_IPP_Object_Employee $Employee
     *
     * @return QuickBooks_IPP_Object_Employee
     */
    protected function _buildEmployee($Employee)
    {
        $Employee->setGivenName($this->GivenName);
        $Employee->setFamilyName($this->FamilyName);
        $Employee->setPrintOnCheckName($this->PrintOnCheckName);

        // Phone #
        $PrimaryPhone = new QuickBooks_IPP_Object_PrimaryPhone();
        $PrimaryPhone->setFreeFormNumber($this->PrimaryPhone);
        $Employee->setPrimaryPhone($PrimaryPhone);

        // Mobile #
        $Mobile = new QuickBooks_IPP_Object_Mobile();
        $Mobile->setFreeFormNumber($this->Mobile);
        $Employee->setMobile($Mobile);

        // Bill address
        $PrimaryAddr = new QuickBooks_IPP_Object_PrimaryAddr();
        $PrimaryAddr->setLine1($this->Line1);
        $PrimaryAddr->setCity($this->City);
        $PrimaryAddr->setState($this->State);
        $PrimaryAddr->setPostalCode($this->PostalCode);
        $PrimaryAddr->setCountry($this->Country);
        $Employee->setPrimaryAddr($PrimaryAddr);

        // Email
        $PrimaryEmailAddr = new QuickBooks_IPP_Object_PrimaryEmailAddr();
        $PrimaryEmailAddr->setAddress($this->PrimaryEmailAddr);
        $Employee->setPrimaryEmailAddr($PrimaryEmailAddr);

        return $Employee;
    }
}