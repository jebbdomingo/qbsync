<?
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

defined('KOOWA') or die; ?>

<?= helper('behavior.koowa'); ?>

<ktml:style src="media://koowa/com_koowa/css/koowa.css" />
<ktml:style src="media://com_qbsync/css/bootstrap.css" />

<ktml:module position="toolbar">
    <ktml:toolbar type="actionbar" title="<?= ($item->id) ? "{$item->id} | {$item->Name}" : 'New Item'; ?>" icon="task-add icon-book">
</ktml:module>

<form method="post" class="-koowa-form">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= translate('Item Details'); ?></h3>
        </div>
        <table class="table">
            <tbody>
                <tr>
                    <td><label><strong><?= translate('Name') ?></strong></label></td>
                    <td><input type="text" name="Name" value="<?= $item->Name ?>" /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Short Description') ?></strong></label></td>
                    <td><input type="text" name="Description" value="<?= $item->Description ?>" /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Full Text') ?></strong></label></td>
                    <td>
                        <div class="k-form-group">
                            <?= helper('editor.display', array(
                                'name' => 'fulltext',
                                'text' => $item->fulltext
                            )) ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</form>