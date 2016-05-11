<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */
class ComQbsyncModelTransfers extends KModelDatabase
{
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('synced', 'string')
            ->insert('entity', 'string')
            ->insert('entity_id', 'int')
            ->insert('entity_ids', 'string')
        ;
    }

    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'searchable' => array('columns' => array('PrivateNote'))
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

        if ($state->entity) {
            $query->where('tbl.entity = :entity')->bind(['entity' => $state->entity]);
        }

        if ($state->entity_id) {
            $query->where('tbl.entity_id = :entity_id')->bind(['entity_id' => $state->entity_id]);
        }

        if ($state->entity_ids) {
            $query->where('tbl.entity_id IN :entity_id')->bind(['entity_id' => (array) $state->entity_ids]);
        }
    }
}