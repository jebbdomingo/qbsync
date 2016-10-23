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
            if ($Item->getType() == 'Group')
            {
                $bundlePrice = 0;
                $numLines    = $Item->getItemGroupDetail()->countLine();

                for ($i = 0; $i < $numLines; $i++)
                {
                    $Line    = $Item->getItemGroupDetail()->getItemGroupLine($i);
                    $subItem = $this->_fetchItem(QuickBooks_IPP_IDS::usableIDType($Line->getItemRef()));
                    $nucItem = $this->getObject('com://admin/qbsync.model.items')
                        ->ItemRef(QuickBooks_IPP_IDS::usableIDType($subItem->getId()))
                        ->fetch()
                    ;

                    if (count($nucItem))
                    {
                        $nucItem->QtyOnHand       = $subItem->getQtyOnHand();
                        $nucItem->getUnitPrice    = $subItem->getUnitPrice();
                        $nucItem->getPurchaseCost = $subItem->getPurchaseCost();
                        $nucItem->save();

                        $bundlePrice += (float) $subItem->getUnitPrice() * (int) $Line->getQty();
                    }
                }

                $this->UnitPrice = $bundlePrice;
            }
            else
            {
                $this->QtyOnHand    = $Item->getQtyOnHand();
                $this->UnitPrice    = $Item->getUnitPrice();
                $this->PurchaseCost = $Item->getPurchaseCost();
            }

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
