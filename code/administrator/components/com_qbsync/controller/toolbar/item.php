<?php

/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncControllerToolbarItem extends ComKoowaControllerToolbarActionbar
{
    /**
     * Sync Command
     *
     * @param KControllerToolbarCommand $command
     *
     * @return void
     */
    protected function _commandSync(KControllerToolbarCommand $command)
    {
        $command->icon = 'icon-32-copy';

        $command->append(array(
            'attribs' => array(
                'data-action'     => 'sync',
                'data-novalidate' => 'novalidate'
            )
        ));

        $command->label = 'Sync';
    }

    // protected function _beforeRender(KControllerContextInterface $context)
    // {
    //     parent::_beforeRender($context);

    //     $controller = $this->getController();
    //     $canSave    = ($controller->isEditable() && $controller->canSave());
    //     $allowed    = true;

    //     if (isset($context->result) && $context->result->isLockable() && $context->result->isLocked()) {
    //         $allowed = false;
    //     }

    //     // Sync command
    //     if ($canSave)
    //     {
    //         $this->addCommand('sync', array(
    //             'allowed' => $allowed,
    //         ));
    //     }
    // }

    protected function _afterBrowse(KControllerContextInterface $context)
    {
        parent::_afterBrowse($context);

        $this->removeCommand('delete');

        $controller = $this->getController();
        $canSave    = ($controller->isEditable() && $controller->canSave());
        $allowed    = true;

        if (isset($context->result) && $context->result->isLockable() && $context->result->isLocked()) {
            $allowed = false;
        }

        // Sync command
        if ($canSave)
        {
            $this->addCommand('sync', array(
                'allowed' => $allowed,
            ));
        }
    }
}