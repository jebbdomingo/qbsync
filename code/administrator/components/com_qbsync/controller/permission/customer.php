<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncControllerPermissionCustomer extends ComKoowaControllerPermissionAbstract
{
    /**
     * Specialized permission check
     *
     * @return boolean
     */
    public function canAdd()
    {
        $data    = $this->getMixer()->getContext()->request->data;
        $account = $this->getMixer()->getObject('com:nucleonplus.model.accounts')->id($data->account_id)->fetch();

        if ($this->getObject('user')->isAuthentic()) {
            return true;
        } elseif ($account->id) {
            // Allow to add even the actor isn't authenticated as long as there's existing member account
            // Primarily used in Joomla user activation 
            return true;
        } else {
            return parent::canAdd();
        }
    }
}