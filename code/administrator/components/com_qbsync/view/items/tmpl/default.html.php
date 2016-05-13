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

<?= helper('bootstrap.load', array('javascript' => true)); ?>
<?= helper('behavior.koowa'); ?>

<ktml:style src="media://koowa/com_koowa/css/admin.css" />

<ktml:module position="submenu">
    <ktml:toolbar type="menubar">
</ktml:module>

<ktml:module position="toolbar">
    <ktml:toolbar type="actionbar" title="COM_QBSYNC_SUBMENU_ITEMS" icon="task icon-stack">
</ktml:module>

<div class="nucleonplus-container">
    <div class="nucleonplus_admin_list_grid">
        <form action="" method="get" class="-koowa-grid">
            <div class="nucleonplus_table_container">
                <table class="table table-striped footable">
                    <thead>
                        <tr>
                            <th style="text-align: center;" width="1">
                                <?= helper('grid.checkall')?>
                            </th>
                            <th class="nucleonplus_table__title_field">
                                <?= helper('grid.sort', array('column' => 'id', 'title' => 'ID')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'item_id', 'title' => 'Item ID')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'ItemRef', 'title' => 'ItemRef')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'UnitPrice', 'title' => 'Unit Price')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'PurchaseCost', 'title' => 'Purchase Cost')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'QtyOnHand', 'title' => 'Quantity on Hand')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'quantity_purchased', 'title' => 'Quantity Purchased')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'last_synced_on', 'title' => 'Last Synced On')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'last_synced_by', 'title' => 'Last Synced By')); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <? if (count($items)): ?>
                            <?= import('default_items.html', ['items' => $items]) ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" align="center" style="text-align: center;">
                                    <?= translate('No record(s) found.') ?>
                                </td>
                            </tr>
                        <? endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10">
                                <?= helper('paginator.pagination') ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>
</div>