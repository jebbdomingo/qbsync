<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncModelEmployees extends KModelDatabase
{
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('synced', 'string')
            ->insert('employee_id', 'int')
            ->insert('action', 'string')
        ;
    }

    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'searchable' => array('columns' => array('DisplayName'))
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

        if ($state->employee_id) {
            $query->where('tbl.employee_id = :employee_id')->bind(['employee_id' => $state->employee_id]);
        }

        if ($state->action) {
            $query->where('tbl.action = :action')->bind(['action' => $state->action]);
        }
    }
}