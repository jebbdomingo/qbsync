<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncModelEntityItem extends ComQbsyncQuickbooksModelEntityRow
{
    /**
     * Sync from QBO
     *
     * @throws Exception QBO sync error
     * 
     * @return boolean
     */
    public function sync()
    {
        $CustomerService = new QuickBooks_IPP_Service_Customer();

        $itemService = new QuickBooks_IPP_Service_Term();

        $items = $itemService->query($this->Context, $this->realm, "SELECT * FROM Item WHERE Id = '{$this->ItemRef}' ");

        if (count($items) == 0)
        {
            $this->setStatusMessage("Invalid ItemRef {$this->ItemRef}");
            return false;
        }

        $this->QtyOnHand    = $items[0]->getQtyOnHand();
        $this->UnitPrice    = $items[0]->getUnitPrice();
        $this->PurchaseCost = $items[0]->getPurchaseCost();
        $this->save();

        return true;
    }

    public function delete()
    {
        return false;
    }

    /**
     * Update quantity purchased
     *
     * @param integer $qty
     *
     * @return self
     */
    public function updateQuantityPurchased($qty)
    {
        $qtyPurchased = (int) $this->quantity_purchased;
        $qty          = (int) $qty;
        
        $this->quantity_purchased = ($qtyPurchased + $qty);

        return $this;
    }
}