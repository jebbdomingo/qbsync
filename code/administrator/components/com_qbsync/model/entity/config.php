<?php
/**
 * QBSync
 *
 * @package     QBSync
 * @copyright   Copyright (C) 2017 Nucleon Plus Co. (http://www.rewardlabs.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/rewardlabs for the canonical source repository
 */

class ComQbsyncModelEntityConfig extends KModelEntityRow
{
    const ACCESS_TOKEN       = 'access_token';
    const REFRESH_TOKEN      = 'refresh_token';
    const AUTHORIZATION_CODE = 'authorization_code';
    const REALM_ID           = 'realm_id';

    public function getJsonValue()
    {
        return json_decode($this->value);
    }

    public function setJsonValue($value)
    {
        $this->value = json_encode($value);
    }
}
