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

<? foreach ($deposits as $deposit): ?>
    <tr>
        <td style="text-align: center;">
            <?= helper('grid.checkbox', array('entity' => $deposit)) ?>
        </td>
        <td class="deskman_table__title_field">
            <a href="<?= route('view=deposit&id='.$deposit->id); ?>">
                <?= $deposit->id ?>
            </a>
        </td>
        <td>
            <span class="label <?= ($deposit->synced == 'no') ? 'label-default' : 'label-info' ?>"><?= ucwords(escape(($deposit->synced == 'no') ? 'No' : 'Yes')) ?></span>
        </td>
        <td><?= $deposit->DepositToAccountRef ?></td>
        <td><?= $deposit->DepartmentRef ?></td>
        <td>
            <?= helper('date.format', array('date' => $deposit->TxnDate)) ?>
        </td>
    </tr>
<? endforeach; ?>