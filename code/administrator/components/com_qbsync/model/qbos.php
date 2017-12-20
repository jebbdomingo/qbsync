<?php
/**
 * QBSync
 *
 * @package     QBSync
 * @copyright   Copyright (C) 2017 Nucleon Plus Co. (http://www.rewardlabs.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/rewardlabs for the canonical source repository
 */

class ComQbsyncModelQbos extends KObject
{
    /**
     * QBO Data
     *
     * @var array
     */
    protected $_config;

    /**
     * Constructor.
     *
     * @param KObjectConfig $config Configuration options.
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $env   = getenv('HTTP_APP_ENV');
        $model = $this->getObject('com:qbsync.model.configs');

        switch ($env) {
            case 'staging':
                $this->_config = $model->item('qbo_staging')->fetch();
                break;
            
            case 'production':
                $this->_config = $model->item('qbo_production')->fetch();
                break;
            
            default:
                $this->_config = $model->item('qbo_local')->fetch();
                break;
        }
    }

    /**
     * Get config entity
     *
     * @return KModelEntityInterface
     */
    public function getEntity()
    {
        return $this->_config;
    }

    /**
     * Getter
     *
     * @param  string $name Name of the property
     * @return mixed|bool
     */
    public function __get($name)
    {
        $name   = strtoupper($name);
        $data   = $this->_config->getJsonValue();
        $result = false;

        if (isset($data->$name))
        {
            $result = $data->$name;
        }
        else
        {
            $trace = debug_backtrace();
            trigger_error(
                'Undefined property via __get(): ' . $name .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'],
                E_USER_NOTICE
            );
        }

        return $result;
    }

    /**
     * Setter
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $name = strtoupper($name);
        $data = $this->_config->getJsonValue();

        $data->$name = $value;
        $this->_config->setJsonValue($data);
    }
}
