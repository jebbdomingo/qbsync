<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncControllerAbstract extends ComKoowaControllerModel
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

        $count = count($entities);

        if ($count)
        {
            foreach($entities as $entity)
            {
                $entity->setProperties($context->request->data->toArray());

                if ($entity->sync() === false)
                {
                    $error = $entity->getStatusMessage();
                    $context->response->addMessage($error ? $error : 'Sync Action Failed', 'error');

                    return $entities;
                }
                else $context->response->setStatus(KHttpResponse::NO_CONTENT);
            }
        }
        else throw new KControllerExceptionResourceNotFound('Resource Not Found');

        $context->response->addMessage("{$count} Items(s) has been synced");

        return $entities;
    }
}