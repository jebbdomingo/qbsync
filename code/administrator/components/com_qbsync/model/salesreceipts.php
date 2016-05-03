<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */
class ComQbsyncModelSalesreceipts extends KModelDatabase
{
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('synced', 'string')
            ->insert('deposit_id', 'int')
            ->insert('transaction_type', 'string')
        ;
    }

    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'searchable' => array('columns' => array('DocNumber'))
            )
        ));

        parent::_initialize($config);
    }

    protected function _buildQueryWhere(KDatabaseQueryInterface $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->getState();

        if (!is_null($state->synced) && $state->synced <> 'all') {
            $query->where('tbl.synced = :synced')->bind(['synced' => $state->synced]);
        }

        if (!is_null($state->deposit_id)) {
            $query->where('tbl.deposit_id = :deposit_id')->bind(['deposit_id' => $state->deposit_id]);
        }

        if ($state->transaction_type) {
            $query->where('tbl.transaction_type = :transaction_type')->bind(['transaction_type' => $state->transaction_type]);
        }
    }
}