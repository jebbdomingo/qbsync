<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */        https://github.com/nooku/nooku-framework for the canonical source repository

/**
 * Syncable Database Behavior
 *
 * @author  Jebb Domingo <https://github.com/jebbdomingo>
 */
class ComQbsyncDatabaseBehaviorSyncable extends KDatabaseBehaviorAbstract
{
    /**
     * Get the user that created the resource
     *
     * @return KUserInterface Returns a User object
     */
    public function getInitiator()
    {
        $provider = $this->getObject('user.provider');

        if($this->hasProperty('last_synced_by') && !empty($this->last_synced_by))
        {
            if($this->_initiator_id && $this->_initiator_id == $this->last_synced_by
                && !$provider->isLoaded($this->last_synced_by))
            {
                $data = array(
                    'id'         => $this->_initiator_id,
                    'email'      => $this->_initiator_email,
                    'name'       => $this->_initiator_name,
                    'username'   => $this->_initiator_username,
                    'authentic'  => false,
                    'enabled'    => !$this->_initiator_block,
                    'expired'    => (bool) $this->_initiator_activation,
                    'attributes' => json_decode($this->_initiator_params)
                );

                $user = $provider->store($this->_initiator_id, $data);
            }
            else $user = $provider->load($this->last_synced_by);
        }
        else $user = $provider->load(0);

        return $user;
    }

    /**
     * Get synced information
     *
     * Requires a 'last_synced_by' column
     *
     * @param KDatabaseContext  $context A database context object
     * @return void
     */
    protected function _beforeSelect(KDatabaseContext $context)
    {
        $context->query
            ->columns(array('_initiator_id'         => '_initiator.id'))
            ->columns(array('_initiator_name'       => '_initiator.name'))
            ->columns(array('_initiator_username'   => '_initiator.username'))
            ->columns(array('_initiator_email'      => '_initiator.email'))
            ->columns(array('_initiator_params'     => '_initiator.params'))
            ->columns(array('_initiator_block'      => '_initiator.block'))
            ->columns(array('_initiator_activation' => '_initiator.activation'))
            ->columns(array('last_synced_by_name'    => '_initiator.name'))
            ->join(array('_initiator' => 'users'), 'tbl.last_synced_by = _initiator.id');
    }

    /**
     * Check if the behavior is supported
     *
     * Behavior requires a 'last_synced_by' or 'last_synced_on' row property
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $table = $this->getMixer();

        //Only check if we are connected with a table object, otherwise just return true.
        if ($table instanceof KDatabaseTableInterface)
        {
            if(!$table->hasColumn('last_synced_by') && !$table->hasColumn('last_synced_on')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Set synced information
     *
     * Requires an 'last_synced_on' and 'last_synced_by' column
     *
     * @param KDatabaseContext  $context A database context object
     * @return void
     */
    protected function _beforeUpdate(KDatabaseContextInterface $context)
    {
        //Get the modified columns
        $modified = $this->getTable()->filter($this->getProperties(true));

        if(!empty($modified))
        {
            if ($this->hasProperty('last_synced_by')) {
                $this->last_synced_by = (int) $this->getObject('user')->getId();
            }

            if ($this->hasProperty('last_synced_on')) {
                $this->last_synced_on = gmdate('Y-m-d H:i:s');
            }
        }
    }
}
