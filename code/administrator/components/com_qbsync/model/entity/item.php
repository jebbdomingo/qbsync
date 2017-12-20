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
        $result = false;

        $Item = $this->fetch('Item', $this->ItemRef);

        if ($Item !== false)
        {
            $this->QtyOnHand    = $Item->QtyOnHand;
            $this->UnitPrice    = $Item->UnitPrice;
            $this->PurchaseCost = $Item->PurchaseCost;
            $this->Type         = $Item->Type;
            $this->Name         = $Item->Name;
            $this->Description  = $Item->Description;
            $this->save();

            $result = true;
        }

        return $result;
    }

    public function delete()
    {
        return false;
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
