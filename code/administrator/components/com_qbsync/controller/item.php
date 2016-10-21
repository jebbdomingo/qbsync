<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncControllerItem extends ComQbsyncControllerAbstract
{
    protected function _actionSync(KControllerContextInterface $context)
    {
        if (!$context->result instanceof KModelEntityInterface) {
            $entities = $this->getModel()->fetch();
        } else {
            $entities = $context->result;
        }

        $service = $this->getObject('com://admin/qbsync.quickbooks.service');
        $service->sync();

        return $entities;
    }
}
