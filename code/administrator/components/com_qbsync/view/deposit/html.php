<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */
class ComQbsyncViewDepositHtml extends ComKoowaViewHtml
{
    /**
     *
     * @var integer
     */
    protected $_bank_account_ref;

    /**
     *
     * @var integer
     */
    protected $_department_ref;

    /**
     * Constructor.
     *
     * @param KObjectConfig $config Configuration options.
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->_bank_account_ref = $config->bank_account_ref;
        $this->_department_ref   = $config->department_ref;
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
            'bank_account_ref' => 269, // Bank Account
            'department_ref'   => 3, // Angono EC Valle
        ));

        parent::_initialize($config);
    }

    protected function _fetchData(KViewContext $context)
    {
        $model   = $this->getModel();
        $deposit = $model->fetch();

        if ($deposit->id)
        {
            $sales_receipts = $this->getObject('com:qbsync.model.salesreceipts')
                ->deposit_id($deposit->id)
                ->fetch()
            ;
        }
        else
        {
            $sales_receipts = $this->getObject('com:qbsync.model.salesreceipts')
                ->deposit_id(0)
                ->fetch()
            ;
        }

        $context->data->sales_receipts      = $sales_receipts;
        $context->data->DepositToAccountRef = $this->_bank_account_ref;
        $context->data->DepartmentRef       = $this->_department_ref;

        parent::_fetchData($context);
    }
}