<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @author      Jebb Domingo <https://github.com/jebbdomingo>
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncQuickbooksModelEntityRow extends KModelEntityRow
{
    /**
     * Constructor.
     *
     * @param KObjectConfig $config Configuration options.
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        // Require the library code
        require_once dirname(__FILE__) . '/../../qbo/QuickBooks.php';

        if (!QuickBooks_Utilities::initialized($config->dsn)) {
            QuickBooks_Utilities::initialize($config->dsn);
        }

        $IntuitAnywhere = new QuickBooks_IPP_IntuitAnywhere(
            $config->dsn,
            $config->encryption_key,
            $config->consumer_key,
            $config->consumer_secret,
            $config->oauth_url,
            $config->oauth_success_url
        );

        if ($IntuitAnywhere->check($config->username, $config->tenant) and 
            $IntuitAnywhere->test($config->username, $config->tenant))
        {
            $this->setProperty('_qbo_connected', true);

            $IPP   = new QuickBooks_IPP($config->dsn);
            $creds = $IntuitAnywhere->load($config->username, $config->tenant);

            $IPP->authMode(
                QuickBooks_IPP::AUTHMODE_OAUTH, 
                $config->username, 
                $creds);

            if ($config->sandbox) {
                $IPP->sandbox(true);
            }

            $this->setProperty('_qbo_ipp', $IPP);
            $this->setProperty('_qbo_realm', $creds['qb_realm']);
        }
        else $this->setProperty('_qbo_connected', false);
    }

    /**
     * Initializes the options for the object.
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param KObjectConfig $config Configuration options.
     */
    protected function _initialize(KObjectConfig $config)
    {
        $data = $this->getObject('com://site/rewardlabs.accounting.data');

        $config->append(array(
            'consumer_key'      => $data->CONFIG_CONSUMER_KEY,
            'consumer_secret'   => $data->CONFIG_CONSUMER_SECRET,
            'sandbox'           => $data->CONFIG_SANDBOX,
            'oauth_url'         => $data->CONFIG_OAUTH_URL,
            'oauth_success_url' => $data->CONFIG_OAUTH_SUCCESS_URL,
            'dsn'               => $data->CONFIG_DSN,
            'encryption_key'    => $data->CONFIG_ENCRYPTION_KEY,
            'username'          => $data->CONFIG_USERNAME,
            'tenant'            => $data->CONFIG_TENANT,
        ));

        parent::_initialize($config);
    }

    /**
     * Check if Nucleon+ is connected to QuickBooks Online
     *
     * @return boolean
     */
    public function isQboConnected()
    {
        return $this->_qbo_connected;
    }

    /**
     * Get IPP Context
     *
     * @return QuickBooks_IPP_Context
     */
    public function getQboContext()
    {
        return $this->_qbo_ipp->context();
    }

    /**
     * Get realm
     *
     * @return string
     */
    public function getQboRealm()
    {
        return $this->_qbo_realm;
    }

    /**
     * Get IPP
     *
     * @return QuickBooks_IPP
     */
    public function getQboIpp()
    {
        return $this->_qbo_ipp;
    }
}