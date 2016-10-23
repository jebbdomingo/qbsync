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

    protected function _buildQueryColumns(KDatabaseQueryInterface $query)
    {
        parent::_buildQueryColumns($query);

        $query
            ->columns(array('_item_ref'           => '_item.ItemRef'))
            ->columns(array('_item_name'          => '_item.Name'))
            ->columns(array('_item_price'         => '_item.UnitPrice'))
            ->columns(array('_item_type'          => '_item.Type'))
            ->columns(array('_item_qty_onhand'    => '_item.QtyOnHand'))
            ->columns(array('_item_qty_purchased' => '_item.quantity_purchased'))
            ->columns(array('_item_slots'         => '_item.slots'))
            ->columns(array('_item_prpv'          => '_item.prpv'))
            ->columns(array('_item_drpv'          => '_item.drpv'))
            ->columns(array('_item_irpv'          => '_item.irpv'))
            ->columns(array('_item_rebates'       => '_item.rebates'))
            ->columns(array('_item_charges'       => '_item.charges'))
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

    protected function _buildQueryJoins(KDatabaseQueryInterface $query)
    {
        $query
            ->join(array('_item' => 'qbsync_items'), 'tbl.ItemRef = _item.ItemRef')
        ;

        parent::_buildQueryJoins($query);
    }
}