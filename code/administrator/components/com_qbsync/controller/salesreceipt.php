<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncControllerSalesreceipt extends ComQbsyncControllerAbstract
{
    /**
     * Undeposited funds account
     *
     * @var integer
     */
    protected $_undeposited_funds_account;

    /**
     * Constructor.
     *
     * @param KObjectConfig $config Configuration options.
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->_undeposited_funds_account = $config->undeposited_funds_account;
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KObjectConfig $config Configuration options
     * @return void
     */
    protected function _initialize(KObjectConfig $config)
    {
        $data = $this->getObject('com:nucleonplus.accounting.service.data');

        $config->append(array(
            'undeposited_funds_account' => $data->ACCOUNT_UNDEPOSITED_REF,
        ));

        parent::_initialize($config);
    }

    /**
     * Add
     *
     * @param KControllerContextInterface $context
     *
     * @return KModelEntityInterface
     */
    protected function _actionAdd(KControllerContextInterface $context)
    {
        if (is_null($context->request->data->DepositToAccountRef)) {
            $context->request->data->DepositToAccountRef = $this->_undeposited_funds_account;
        }

        return parent::_actionAdd($context);
    }
}