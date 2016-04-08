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
    <ktml:toolbar type="actionbar" title="<?= ($deposit->id) ? 'Deposit #' . $deposit->id : 'New Deposit'; ?>" icon="task-add icon-book">
</ktml:module>

<form method="post" class="-koowa-form">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title"><?= translate('Deposit Details'); ?></h3>
        </div>

        <table class="table">
            <tbody>
                <tr>
                    <td><label><strong><?= translate('Deposit To Account Ref.') ?></strong></label></td>
                    <td><input type="text" name="DepositToAccountRef" value="<?= ($deposit->DepositToAccountRef) ? $deposit->DepositToAccountRef : $DepositToAccountRef ?>" /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Department Ref') ?></strong></label></td>
                    <td><input type="text" name="DepartmentRef" value="<?= ($deposit->DepartmentRef) ? $deposit->DepartmentRef : $DepartmentRef ?>" /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Date') ?></strong></label></td>
                    <td>
                        <?= helper('date.humanize', array('date' => $deposit->TxnDate)) ?>
                        <?= helper('date.format', array('date' => $deposit->TxnDate)) ?>
                    </td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Synced') ?></strong></label></td>
                    <td>
                        <?= helper('listbox.sync', array(
                            'name'     => 'synced',
                            'selected' => $deposit->synced,
                            'attribs'   => array(
                                'disabled' => ($deposit->id) ? false : true
                            )
                        )) ?>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title"><?= translate('Sales Receipts'); ?></h3>
        </div>

        <?= import('sales_receipts.html', ['sales_receipts' => $sales_receipts]) ?>
    </div>

</form>