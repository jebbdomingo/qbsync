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
        <td class="deskman_table__title_field"><?= $item->id ?></td>
        <td><?= $item->item_id ?>
        <td><?= $item->ItemRef ?></td>
        <td><?= number_format($item->UnitPrice, 2) ?></td>
        <td><?= number_format($item->PurchaseCost, 2) ?></td>
        <td><?= $item->QtyOnHand ?></td>
        <td><?= $item->quantity_purchased ?></td>
        <td><?= helper('date.humanize', array('date' => $item->last_synced_on)) ?></td>
        <td><?= $item->getInitiator()->getName() ?></td>
    </tr>
<? endforeach; ?>