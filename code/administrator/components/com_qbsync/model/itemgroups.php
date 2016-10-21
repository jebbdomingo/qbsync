<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncModelItemgroups extends KModelDatabase
{
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('parent_id', 'int')
            ->insert('ItemRef', 'int')
        ;
    }

    protected function _buildQueryWhere(KDatabaseQueryInterface $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->getState();

        if ($state->parent_id) {
            $query->where('tbl.parent_id = :parent_id')->bind(['parent_id' => $state->parent_id]);
        }

        if ($state->ItemRef) {
            $query->where('tbl.ItemRef IN :ItemRef')->bind(array('ItemRef' => (array) $state->ItemRef));
        }
    }
}