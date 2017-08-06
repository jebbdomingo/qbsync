<?php
/**
 * Nucleon Plus - QBO
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2017 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncServiceTransfer extends ComQbsyncQuickbooksModelEntityRow
{
    /**
     * Create transfer transaction
     *
     * @param array  $data
     * @throws Exception
     * @return string
     */
    public function create(array $data)
    {
        $Transfer = new QuickBooks_IPP_Object_Transfer();
        $Transfer->setFromAccountRef($data['FromAccountRef']);
        $Transfer->setToAccountRef($data['ToAccountRef']);
        $Transfer->setAmount($data['Amount']);
        $Transfer->setTxnDate($data['TxnDate']);
        $Transfer->setPrivateNote("NUC-TRN-{$data['PrivateNote']}");

        $TransferService = new QuickBooks_IPP_Service_Transfer();
        $resp = $TransferService->add($this->getQboContext(), $this->getQboRealm(), $Transfer);

        if ($resp) {
            return QuickBooks_IPP_IDS::usableIDType($resp);
        } else {
            $data = print_r($data, true);
            throw new KControllerExceptionActionFailed('Error creating the Transfer in QBO: ' . $TransferService->lastError($this->getQboContext()) . $data);
        }
    }
}
