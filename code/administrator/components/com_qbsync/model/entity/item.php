<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncModelEntityItem extends ComQbsyncQuickbooksModelEntityAbstract
{
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';

    public static $status_messages = array(
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    );

    const TYPE_GROUP          = 'Group';
    const TYPE_INVENTORY_ITEM = 'Inventory';
    const TYPE_SERVICE        = 'Service';

    const TYPE_SHIPPING_POST = 'phlpost';
    const TYPE_SHIPPING_XEND = 'xend';

    public static $item_types = array(
        self::TYPE_INVENTORY_ITEM,
        self::TYPE_GROUP
    );

    public function save()
    {
        $this->Active = $this->status == self::STATUS_ACTIVE ? 1 : 0;

        $unit_price = floatval($this->PurchaseCost)
            + floatval($this->profit)
            + floatval($this->charges)
            + floatval($this->drpv)
            + floatval($this->irpv)
            + floatval($this->rebates)
            + floatval($this->stockist)
        ;

        $this->UnitPrice = $unit_price;
        $this->Type      = 'Inventory';

        return parent::save();
    }

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
            $this->Type         = $Item->getType();
            $this->Name         = $Item->getName();
            $this->Description  = $Item->getDescription();
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

        if (is_null($ItemRef))
        {
            $items = $itemService->query($this->getQboContext(), $this->getQboRealm(), "SELECT * FROM Item WHERE Type IN ('Inventory', 'Group')");
        }
        else $items = $itemService->query($this->getQboContext(), $this->getQboRealm(), "SELECT * FROM Item WHERE Id = '{$ItemRef}' AND Type IN ('Inventory', 'Group')");

        if (count($items) == 0)
        {
            $this->setStatusMessage("Invalid ItemRef {$this->ItemRef}");
            $result = false;
        }
        else $result = is_null($ItemRef) ? $items : $items[0];

        return $result;
    }

    /**
     * Check available stock
     *
     * @return boolean
     */
    public function hasAvailableStock()
    {
        return (bool) $this->QtyOnHand;
    }
}
