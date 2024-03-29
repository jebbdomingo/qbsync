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

<? foreach ($items as $item): ?>
    <tr>
        <td style="text-align: center;">
            <?= helper('grid.checkbox', array('entity' => $item)) ?>
        </td>
        <td class="deskman_table__title_field"><a href="<?= route("view=item&id={$item->id}") ?>"><?= $item->Name ?></a></td>
        <td><?= $item->Type ?></td>
        <td><?= $item->Active ?></td>
        <td><?= $item->ItemRef ?></td>
        <td style="text-align: right"><?= number_format($item->UnitPrice, 2) ?></td>
        <td style="text-align: right"><?= number_format($item->PurchaseCost, 2) ?></td>
        <td style="text-align: right"><?= $item->QtyOnHand ?></td>
        <td style="text-align: right"><?= $item->quantity_purchased ?></td>
    </tr>
<? endforeach; ?>