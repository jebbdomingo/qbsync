<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncModelItems extends KModelDatabase
{
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('ItemRef'    , 'int')
            ->insert('app'        , 'cmd'   , null, true)
            ->insert('app_entity' , 'cmd'   , null, true)
        ;
    }

    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'searchable' => array('columns' => array('Name', 'DocNumber'))
            )
        ));

        parent::_initialize($config);
    }

    protected function _buildQueryWhere(KDatabaseQueryInterface $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->getState();

        if ($state->ItemRef) {
            $query->where('tbl.ItemRef IN :ItemRef')->bind(array('ItemRef' => (array) $state->ItemRef));
        }

        if ($state->app) {
            $query->where('app = :app')->bind(array('app' => $state->app));
        }

        if ($state->app_entity) {
            $query->where('app_entity = :app_entity')->bind(array('app_entity' => $state->app_entity));
        }

        // Exclude unused qbo items
        $query->where("tbl.Type NOT LIKE '_NOT_USED_%'");
    }
}