<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncControllerDeposit extends ComQbsyncControllerAbstract
{
    /**
     * Bank account
     *
     * @var integer
     */
    protected $_bank_account;

    /**
     * Constructor.
     *
     * @param KObjectConfig $config Configuration options.
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->_bank_account = $config->bank_account;
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
        $config->append(array(
            'bank_account' => 269,
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
        if (empty(trim($context->request->data->DepositToAccountRef))) {
            $context->request->data->DepositToAccountRef = $this->_bank_account;
        }

        $context->request->data->TxnDate = date('Y-m-d');

        $entity = parent::_actionAdd($context);

        foreach ($context->request->data->salesreceipts as $id)
        {
            $salesreceipt = $this->getObject('com:qbsync.model.salesreceipts')->id($id)->fetch();
            $salesreceipt->deposit_id = $entity->id;
            $salesreceipt->save();
        }

        return $entity;
    }

    /**
     * Sync Action
     *
     * @param   KControllerContextInterface $context A command context object
     * @throws  KControllerExceptionRequestNotAuthorized If the user is not authorized to update the resource
     * 
     * @return  KModelEntityInterface
     */
    protected function _actionSync(KControllerContextInterface $context)
    {
        if (!$context->result instanceof KModelEntityInterface) {
            $entities = $this->getModel()->fetch();
        } else {
            $entities = $context->result;
        }

        if (count($entities))
        {
            foreach($entities as $entity)
            {
                $entity->setProperties($context->request->data->toArray());

                // Sync SalesReceipts added to this Deposit that are currently unsynced
                $salesreceipts = $this->getObject('com:qbsync.model.salesreceipts')
                    ->deposit_id($entity->id)
                    ->synced('no')
                    ->fetch()
                ;
                
                foreach ($salesreceipts as $salesreceipt)
                {
                    if ($salesreceipt->sync() === false)
                    {
                        $error = $salesreceipt->getStatusMessage();
                        $context->response->addMessage($error ? $error : 'SalesReceipt Sync Action Failed', 'error');
                        
                        return $entities;
                    }
                }

                // Sync this Deposit
                if ($entity->sync() === false)
                {
                    $error = $entity->getStatusMessage();
                    $context->response->addMessage($error ? $error : 'Deposit Sync Action Failed', 'error');

                    return $entities;
                }
                else $context->response->setStatus(KHttpResponse::NO_CONTENT);
            }
        }
        else throw new KControllerExceptionResourceNotFound('Resource Not Found');

        $context->response->addMessage("Deposit transaction(s) has been synced");

        return $entities;
    }
}