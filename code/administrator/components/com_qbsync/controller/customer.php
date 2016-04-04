<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncControllerCustomer extends ComKoowaControllerModel
{
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
        if(!$context->result instanceof KModelEntityInterface) {
            $entities = $this->getModel()->fetch();
        } else {
            $entities = $context->result;
        }

        if(count($entities))
        {
            foreach($entities as $entity)
            {
                $entity->setProperties($context->request->data->toArray());

                if ($entity->sync() === false)
                {
                    $error = $entity->getStatusMessage();
                    $context->response->addMessage($error ? $error : 'Sync Action Failed', 'error');
                }
                else $context->response->setStatus(KHttpResponse::NO_CONTENT);
            }

        }
        else throw new KControllerExceptionResourceNotFound('Resource Not Found');

        return $entities;
    }
}