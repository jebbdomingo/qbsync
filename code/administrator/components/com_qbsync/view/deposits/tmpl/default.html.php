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
    <ktml:toolbar type="actionbar" title="COM_QBSYNC_SUBMENU_DEPOSITS" icon="task icon-stack">
</ktml:module>

<div class="nucleonplus-container">
    <div class="nucleonplus_admin_list_grid">
        <form action="" method="get" class="-koowa-grid">
            <div class="scopebar">
                <div class="scopebar-group last hidden-tablet hidden-phone">
                    <?= helper('listbox.filterList', array('active_status' => parameters()->synced)); ?>
                </div>
            </div>
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
                                <?= helper('grid.sort', array('column' => 'synced', 'title' => 'Synced')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'DepositToAccountRef', 'title' => 'Deposit To Account Ref.')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'DepartmentRef', 'title' => 'Department Ref')); ?>
                            </th>
                            <th>
                                <?= helper('grid.sort', array('column' => 'TxnDate', 'title' => 'Date')); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <? if (count($deposits)): ?>
                            <?= import('default_deposits.html', ['deposits' => $deposits]) ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" align="center" style="text-align: center;">
                                    <?= translate('No record(s) found.') ?>
                                </td>
                            </tr>
                        <? endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <?= helper('paginator.pagination', array('limit' => 10)) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>
</div>