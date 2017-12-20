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
            'baseUrl'         => $config->base_url,
            'scope'           => $config->scope,
        ));

        // Log path
        $data_service->setLogLocation($config->log_path);

        $this->setDataService($data_service);

        // Check OAuth2 connection
        $this->refreshConnection();
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
        $qbo = $this->getObject('com:qbsync.model.qbos');

        $config->append(array(
            'auth_mode'     => 'oauth2',
            'client_id'     => $qbo->CONFIG_CLIENT_ID,
            'client_secret' => $qbo->CONFIG_CLIENT_SECRET,
            'access_token'  => $qbo->CONFIG_ACCESS_TOKEN,
            'refresh_token' => $qbo->CONFIG_REFRESH_TOKEN,
            'realm_id'      => $qbo->CONFIG_REALM_ID,
            'base_url'      => $qbo->CONFIG_BASE_URL,
            'log_path'      => $qbo->CONFIG_LOG_PATH,
            'scope'         => 'com.intuit.quickbooks.accounting',
        ));

        parent::_initialize($config);
    }

    /**
     * Generate new token
     *
     * @return void
     */
    protected function refreshConnection()
    {
        // Test connection
        $CompanyInfo = $this->getDataService()->getCompanyInfo();
        $error = $this->getDataService()->getLastError();

        if ($error != null)
        {
            $OAuth2LoginHelper = $this->getDataService()->getOAuth2LoginHelper();

            $accessToken = $OAuth2LoginHelper->refreshToken();
            $error       = $OAuth2LoginHelper->getLastError();

            if ($error != null)
            {
                echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                echo "The Response message is: " . $error->getResponseBody() . "\n";
                
                throw new KControllerExceptionActionFailed("Error in generating new access token from QBO");
            }

            $this->getDataService()->updateOAuth2Token($accessToken);

            $CompanyInfo = $this->getDataService()->getCompanyInfo();

            $error = $this->getDataService()->getLastError();
            if ($error != null)
            {
                echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                echo "The Response message is: " . $error->getResponseBody() . "\n";

                throw new KControllerExceptionActionFailed("Problem connecting to QBO after access token regeneration");
            }

            // Set new refresh token
            $qbo = $this->getObject('com:qbsync.model.qbos');
            $qbo->CONFIG_ACCESS_TOKEN          = $accessToken->getAccessToken();
            $qbo->CONFIG_REFRESH_TOKEN         = $accessToken->getRefreshToken();
            $qbo->CONFIG_ACCESS_TOKEN_EXPIRES  = $accessToken->getAccessTokenExpiresAt();
            $qbo->CONFIG_REFRESH_TOKEN_EXPIRES = $accessToken->getRefreshTokenExpiresAt();
        }
    }

    /**
     * Check if Nucleon+ is connected to QuickBooks Online
     *
     * @return boolean
     */
    public function isQboConnected()
    {
        $company_info = $this->getDataService()->getCompanyInfo();
        $error        = $this->getDataService()->getLastError();

        if ($error != null) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get data service
     *
     * @param  QuickBooksOnline\API\DataService\DataService $data_service
     * @return void
     */
    public function setDataService($data_service)
    {
        return $this->_data_service = $data_service;
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

    /**
     * Add action
     *
     * @param  QuickBooksOnline\API\Facades $entity
     * @param  string                       $error_message
     * @return QuickBooksOnline\API\Facades
     */
    public function add($entity, $error_message = 'Error: ')
    {
        $result = $this->getDataService()->Add($entity);
        $error  = $this->getDataService()->getLastError();

        if ($error)
        {
            $details = "The Status code is: {$error->getHttpStatusCode()}\n";
            $details .= "The Helper message is: {$error->getOAuthHelperError()}\n";
            $details .= "The Response message is: {$error->getResponseBody()}\n";

            throw new KControllerExceptionActionFailed($error_message . $details);
        }

        return $result;
    }

    /**
     * Update action
     *
     * @param  QuickBooksOnline\API\Facades $entity
     * @param  string                       $error_message
     * @return QuickBooksOnline\API\Facades
     */
    public function edit($entity, $error_message = 'Error: ')
    {
        $result = $this->getDataService()->Update($entity);
        $error  = $this->getDataService()->getLastError();

        if ($error)
        {
            $details = "The Status code is: {$error->getHttpStatusCode()}\n";
            $details .= "The Helper message is: {$error->getOAuthHelperError()}\n";
            $details .= "The Response message is: {$error->getResponseBody()}\n";

            throw new KControllerExceptionActionFailed($error_message . $details);
        }

        return $result;
    }

    /**
     * Fetch QBO record
     *
     * @param  string $entity
     * @param  mixed  $id
     * @return bool|QuickBooksOnline\API\Facades
     */
    public function fetch($entity, $id)
    {
        $result   = false;
        $entities = $this->getDataService()->Query("SELECT * FROM {$entity} where Id='{$id}'");
        $error    = $this->getDataService()->getLastError();

        if ($error)
        {
            $error_message = "The Status code is: {$error->getHttpStatusCode()}\n";
            $error_message .= "The Helper message is: {$error->getOAuthHelperError()}\n";
            $error_message .= "The Response message is: {$error->getResponseBody()}\n";
            
            throw new KControllerExceptionActionFailed("Error in fetching {$entity} from QBO: {$error_message}");
        }

        if (!empty($entities)) {
            // Get the first element
            $result = reset($entities);
        }

        return $result;
    }
}