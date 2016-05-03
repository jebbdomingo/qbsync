<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

/**
 * Sync Template Helper
 *
 * @package Nucleon Plus
 */
class ComQbsyncTemplateHelperListbox extends ComKoowaTemplateHelperListbox
{
    /**
     * @var array
     */
    protected $_sync_filters = array();

    /**
     * @var array
     */
    protected $_sync = array();

    /**
     * @var array
     */
    protected $_transaction_type_filters = array();

    /**
     * Constructor
     *
     * @param KObjectConfig $config [description]
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->_sync_filters             = $config->sync_filters;
        $this->_sync                     = $config->sync;
        $this->_transaction_type_filters = $config->transaction_type_filters;
    }

    /**
     * Initialization
     *
     * @param KObjectConfig $config
     *
     * @return void
     */
    protected function _initialize(KObjectConfig $config)
    {
        $config
        ->append(array(
            'sync_filters' => array(
                'all' => 'All',
                'no'  => 'No',
                'yes' => 'Yes',
            )
        ))
        ->append(array(
            'sync' => array(
                array('label' => 'No', 'value' => 'no'),
                array('label' => 'Yes', 'value' => 'yes')
            )
        ))
        ->append(array(
            'transaction_type_filters' => array(
                'offline' => 'Offline',
                'online'  => 'Online',
            )
        ));

        parent::_initialize($config);
    }

    /**
     * Generates sync status list box
     * 
     * @param array $config [optional]
     * 
     * @return html
     */
    public function sync(array $config = array())
    {
        $config = new KObjectConfig($config);
        $config->append(array(
            'name'     => 'synced',
            'selected' => null,
            'options'  => $this->_sync,
            'filter'   => array()
        ));

        return parent::radiolist($config);
    }

    /**
     * Generates sync status filter buttons
     *
     * @todo rename to status filter list
     *
     * @param array $config [optional]
     *
     * @return  string  html
     */
    public function filterList(array $config = array())
    {
        $status = $this->_sync_filters;

        // Merge with user-defined status
        if (isset($config['status']) && $config['status']) {
            $status = $status->merge($config['status']);
        }

        $result = '';

        foreach ($status as $value => $label)
        {
            $class = ($config['active_status'] == $value) ? 'class="active"' : null;
            $href  = ($config['active_status'] <> $value) ? 'href="' . $this->getTemplate()->route("synced={$value}") . '"' : null;
            $label = $this->getObject('translator')->translate($label);

            $result .= "<a {$class} {$href}>{$label}</a>";
        }

        return $result;
    }

    /**
     * Generates transaction type filters
     *
     * @todo rename to status filter list
     *
     * @param array $config [optional]
     *
     * @return  string  html
     */
    public function transactionTypeFilters(array $config = array())
    {
        $types = $this->_transaction_type_filters;

        // Merge with user-defined types
        if (isset($config['transacton_types']) && $config['transacton_types']) {
            $types = $types->merge($config['transacton_types']);
        }

        $result = '';

        foreach ($types as $value => $label)
        {
            $class = ($config['active_status'] == $value) ? 'class="active"' : null;
            $href  = ($config['active_status'] <> $value) ? 'href="' . $this->getTemplate()->route("transaction_type={$value}") . '"' : null;
            $label = $this->getObject('translator')->translate($label);

            $result .= "<a {$class} {$href}>{$label}</a>";
        }

        return $result;
    }
}