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

<?
// Disable editing when employee is already synced
$disabled       = ($employee->synced == 'yes') ? true : false;
$disabledMarkup = ($disabled) ? 'disabled="disabled"' : '';
?>

<?= helper('behavior.koowa'); ?>

<ktml:style src="media://koowa/com_koowa/css/koowa.css" />
<ktml:style src="media://com_qbsync/css/bootstrap.css" />

<ktml:module position="toolbar">
    <ktml:toolbar type="actionbar" title="<?= ($employee->id) ? 'Employee #' . $employee->id : 'New Employee'; ?>" icon="task-add icon-book">
</ktml:module>

<form method="post" class="-koowa-form">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= translate('Employee Details'); ?></h3>
        </div>
        <table class="table">
            <tbody>
                <tr>
                    <td><label><strong><?= translate('Employee ID') ?></strong></label></td>
                    <td><input type="text" name="employee_id" value="<?= $employee->employee_id ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Employee Ref.') ?></strong></label></td>
                    <td><input type="text" name="EmployeeRef" value="<?= $employee->EmployeeRef ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Given Name') ?></strong></label></td>
                    <td><input type="text" name="GivenName" value="<?= $employee->GivenName ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Family Name') ?></strong></label></td>
                    <td><input type="text" name="FamilyName" value="<?= $employee->FamilyName ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Primary Phone') ?></strong></label></td>
                    <td><input type="text" name="PrimaryPhone" value="<?= $employee->PrimaryPhone ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Mobile') ?></strong></label></td>
                    <td><input type="text" name="Mobile" value="<?= $employee->Mobile ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Primary Email Addr') ?></strong></label></td>
                    <td><input type="text" name="PrimaryEmailAddr" value="<?= $employee->PrimaryEmailAddr ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Print On Check Name') ?></strong></label></td>
                    <td><input type="text" name="PrintOnCheckName" value="<?= $employee->PrintOnCheckName ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Line 1') ?></strong></label></td>
                    <td><input type="text" name="Line1" value="<?= $employee->Line1 ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('City') ?></strong></label></td>
                    <td><input type="text" name="City" value="<?= $employee->City ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('State') ?></strong></label></td>
                    <td><input type="text" name="State" value="<?= $employee->State ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Postal Code') ?></strong></label></td>
                    <td><input type="text" name="PostalCode" value="<?= $employee->PostalCode ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Country') ?></strong></label></td>
                    <td><input type="text" name="Country" value="<?= $employee->Country ?>" <?= $disabledMarkup ?> /></td>
                </tr>
                <tr>
                    <td><label><strong><?= translate('Synced') ?></strong></label></td>
                    <td>
                        <?= helper('listbox.sync', array(
                            'name'     => 'synced',
                            'selected' => $employee->synced,
                            'attribs'  => array('disabled' => $disabled)
                        )) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</form>