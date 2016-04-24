<?
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */
?>

<? foreach ($employees as $employee): ?>
    <tr>
        <td style="text-align: center;">
            <?= helper('grid.checkbox', array('entity' => $employee)) ?>
        </td>
        <td class="deskman_table__title_field">
            <a href="<?= route('view=employee&id='.$employee->id); ?>">
                <?= $employee->id ?>
            </a>
        </td>
        <td><?= ucwords($employee->action) ?></td>
        <td>
            <span class="label <?= ($employee->synced == 'no') ? 'label-default' : 'label-info' ?>"><?= ucwords(escape(($employee->synced == 'no') ? 'No' : 'Yes')) ?></span>
        </td>
        <td><?= $employee->employee_id ?></td>
        <td><?= $employee->EmployeeRef ?></td>
        <td><?= $employee->GivenName ?></td>
        <td><?= $employee->FamilyName ?></td>
    </tr>
<? endforeach; ?>