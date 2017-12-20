<?php
/**
 * Nucleon Plus - QBO
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2017 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

use QuickBooksOnline\API\Facades\Transfer;

class ComQbsyncServiceTransfer extends ComQbsyncQuickbooksModelEntityAbstract
{
    /**
     * Create transfer transaction
     *
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data)
    {
        $datum = array(
            'FromAccountRef' => $data['FromAccountRef'],
            'ToAccountRef'   => $data['ToAccountRef'],
            'Amount'         => $data['Amount'],
            'TxnDate'        => $data['TxnDate'],
            'PrivateNote'    => "NUC-TRN-{$data['PrivateNote']}",
        );

        $entity = Transfer::create($datum);
        $result = $this->add($entity, 'Error in creating Transfer on QBO: ');

        return $result->Id;
    }
}
