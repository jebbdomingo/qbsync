<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncQuickbooksService extends ComQbsyncQuickbooksObject
{
    public function sync()
    {
        $itemService          = new QuickBooks_IPP_Service_Term();
        $qbSyncItemModel      = $this->getObject('com://admin/qbsync.model.items');
        $qbSyncItemGroupModel = $this->getObject('com://admin/qbsync.model.itemgroups');
        $Items                = $this->_fetchItem();

        // foreach ($Items as $Item) {
        //     var_dump($Item);
        // }
        // die;

        foreach ($Items as $Item)
        {
            if ($Item !== false)
            {
                $qbSyncItem = $qbSyncItemModel->ItemRef(QuickBooks_IPP_IDS::usableIDType($Item->getId()))->fetch();

                $data = array(
                    'ItemRef'      => QuickBooks_IPP_IDS::usableIDType($Item->getId()),
                    'Name'         => $Item->getName(),
                    'Description'  => $Item->getDescription(),
                    'Active'       => (int) $Item->getActive(),
                    'Taxable'      => (int) $Item->getTaxable(),
                    'UnitPrice'    => $Item->getUnitPrice(),
                    'Type'         => $Item->getType(),
                    'QtyOnHand'    => $Item->getQtyOnHand(),
                    'PurchaseCost' => $Item->getPurchaseCost()
                );

                var_dump($Item->getType());
                echo '<br />';

                // Create nucleon item
                if (count($qbSyncItem) == 0)
                {
                    $qbSyncItem = $qbSyncItemModel->create($data);
                    $qbSyncItem->save();
                }
                else
                {
                    foreach ($data as $key => $value) {
                        $qbSyncItem->{$key} = $value;
                    }
                }

                if ($Item->getType() == 'Group')
                {
                    $numLines = (int) $Item->getItemGroupDetail()->countLine();

                    // Grouped items
                    if ($numLines > 0)
                    {
                        $bundlePrice  = 0;
                        $bundleWeight = 0;

                        // Reset nucleon item group
                        $groupItems = $qbSyncItemGroupModel->parent_id($qbSyncItem->ItemRef)->fetch();
                        foreach ($groupItems as $groupItem) {
                            $groupItem->delete();
                        }

                        for ($i = 0; $i < $numLines; $i++)
                        {
                            $Line = $Item->getItemGroupDetail()->getItemGroupLine($i);

                            // Compute bundle price and weight
                            $subItem = $qbSyncItemModel
                                ->ItemRef(QuickBooks_IPP_IDS::usableIDType($Line->getItemRef()))
                                ->fetch()
                            ;
                            $bundlePrice  += (float) $subItem->UnitPrice * (int) $Line->getQty();
                            $bundleWeight += (int) $subItem->weight * (int) $Line->getQty();

                            // Create nucleon group item
                            $data = array(
                                'parent_id' => $qbSyncItem->ItemRef,
                                'ItemRef'   => $subItem->ItemRef,
                                'quantity'  => $Line->getQty()
                            );
                            $groupItem = $qbSyncItemGroupModel->create($data);
                            $groupItem->save();
                        }

                        $qbSyncItem->UnitPrice = $bundlePrice;
                        $qbSyncItem->weight    = $bundleWeight;
                    }
                }

                $qbSyncItem->save();
            }
        }

        die('test');
    }

    protected function _fetchItem($ItemRef = null)
    {
        $itemService = new QuickBooks_IPP_Service_Term();

        if (is_null($ItemRef))
        {
            $items = $itemService->query($this->Context, $this->realm, "SELECT * FROM Item WHERE Type IN ('Inventory', 'Non-inventory', 'Group')");
        }
        else $items = $itemService->query($this->Context, $this->realm, "SELECT * FROM Item WHERE Id = '{$ItemRef}' AND WHERE Type IN ('Inventory', 'Non-inventory', 'Group')");

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
}
