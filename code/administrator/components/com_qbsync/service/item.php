<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2017 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncServiceItem extends ComQbsyncQuickbooksModelEntityRow
{
    /**
     * Update record
     *
     * @param array  $data
     * @throws Exception
     * @return string
     */
    public function update(array $data)
    {
        $config = $this->getObject('com:rewardlabs.accounting.data');
        $Item   = $this->get($data['ItemRef']);

        if ($Item)
        {
            $Item->setType('Inventory');
            $Item->setName($data['Name']);
            $Item->setDescription($data['Description']);
            $Item->setUnitPrice($data['UnitPrice']);
            $Item->setIncomeAccountRef($config->ACCOUNT_SALES_INCOME);
            $Item->setExpenseAccountRef($config->ACCOUNT_COGS);
            $Item->setAssetAccountRef($config->ACCOUNT_INVENTORY_ASSET);

            $ItemService = new QuickBooks_IPP_Service_Item();

            if (!$ItemService->update($item->getQboContext(), $item->getQboRealm(), $Item->getId(), $Item)) {
                throw new KControllerExceptionActionFailed($ItemService->lastError($item->getQboContext()));
            }
        }
    }

    /**
     * Get record
     *
     * @param  mixed $id]
     *
     * @return bool|object
     */
    public function get($id)
    {
        $ItemService = new QuickBooks_IPP_Service_Item();

        // Get the existing item 
        $items = $ItemService->query($this->getQboContext(), $this->getQboRealm(), "SELECT * FROM Item WHERE Id = '{$id}' ");

        if (count($items)) {
            return $items[0];
        } else {
            return false;
        }
    }
}
