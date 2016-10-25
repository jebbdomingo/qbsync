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
    const TYPE_GROUP          = 'Group';
    const TYPE_INVENTORY_ITEM = 'Inventory';

    public static $item_types = array(
        self::TYPE_INVENTORY_ITEM,
        self::TYPE_GROUP
    );

    /**
     * Sync from QBO
     *
     * @throws Exception QBO sync error
     * 
     * @return boolean
     */
    public function sync()
    {
        $itemService = new QuickBooks_IPP_Service_Term();
        $result      = false;

        $Item = $this->_fetchItem($this->ItemRef);

        if ($Item !== false)
        {
            $this->QtyOnHand    = $Item->getQtyOnHand();
            $this->UnitPrice    = $Item->getUnitPrice();
            $this->PurchaseCost = $Item->getPurchaseCost();

            $this->save();

            $result = true;
        }

        return $result;
    }

    public function delete()
    {
        return false;
    }

    protected function _fetchItem($ItemRef = null)
    {
        $itemService = new QuickBooks_IPP_Service_Term();

        if (is_null($ItemRef)) {
            $items = $itemService->query($this->Context, $this->realm, "SELECT * FROM Item");
        }
        else $items = $itemService->query($this->Context, $this->realm, "SELECT * FROM Item WHERE Id = '{$ItemRef}'");

        if (count($items) == 0)
        {
            $this->setStatusMessage("Invalid ItemRef {$this->ItemRef}");
            $result = false;
        }
        else
        {
            $result = is_null($ItemRef) ? $items : $items[0];
        }

        return $result;
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
