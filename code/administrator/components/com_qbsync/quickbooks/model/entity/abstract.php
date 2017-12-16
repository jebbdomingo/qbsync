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

require_once dirname(__FILE__) . '/../../src/config.php';

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

class ComQbsyncQuickbooksModelEntityAbstract extends KModelEntityRow
{
    /**
     * QuickBooks Online DataService
     *
     * @var QuickBooksOnline\API\DataService\DataService
     */
    protected $_data_service;

    /**
     * Constructor.
     *
     * @param KObjectConfig $config Configuration options.
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $data_service = DataService::Configure(array(
                'auth_mode'       => $config->auth_mode,
                'ClientID'        => $config->client_id,
                'ClientSecret'    => $config->client_secret,
                'accessTokenKey'  => $config->access_token,
                'refreshTokenKey' => $config->refresh_token,
                'QBORealmID'      => $config->realm_id,
                'baseUrl'         => $config->base_url
        ));

        // Log path
        $data_service->setLogLocation($config->log_path);

        $this->_data_service = $data_service;

        // Test configuration
        $company_info = $data_service->getCompanyInfo();
        $error        = $data_service->getLastError();

        if ($error != null) {
            $this->setProperty('_qbo_connected', false);
        } else {
            $this->setProperty('_qbo_connected', true);
        }
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
            'auth_mode'     => 'oauth2',
            'client_id'     => $data->CONFIG_CLIENT_ID,
            'client_secret' => $data->CONFIG_CLIENT_SECRET,
            'access_token'  => $data->CONFIG_ACCESS_TOKEN,
            'refresh_token' => $data->CONFIG_REFRESH_TOKEN,
            'realm_id'      => $data->CONFIG_REALM_ID,
            'base_url'      => $data->CONFIG_BASE_URL,
            'log_path'      => $data->CONFIG_LOG_PATH
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
     * Get data service
     *
     * @return QuickBooksOnline\API\DataService\DataService
     */
    public function getDataService()
    {
        return $this->_data_service;
    }
}