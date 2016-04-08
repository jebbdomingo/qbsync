<table class="table">
    <thead>
        <th>&nbsp;</th>
        <th>ID</th>
        <th>Synced</th>
        <th>DocNumber</th>
        <th>DepartmentRef</th>
        <th>TxnDate</th>
    </thead>

    <tbody>
        <? if (count($sales_receipts) > 0): ?>
            <? foreach ($sales_receipts as $sales_receipt): ?>
                <tr>
                    <td>
                        <? if ($deposit->id): ?>
                            &nbsp;
                        <? else: ?>
                            <input type="checkbox" name="salesreceipts[]" value="<?= $sales_receipt->id ?>" <?= ($deposit->id) ? 'disabled="disabled"' : '' ?> />
                        <? endif; ?>
                    </td>
                    <td><?= $sales_receipt->id ?></td>
                    <td><?= $sales_receipt->synced ?></td>
                    <td><?= $sales_receipt->DocNumber ?></td>
                    <td><?= $sales_receipt->DepartmentRef ?></td>
                    <td><?= $sales_receipt->TxnDate ?></td>
                </tr>
            <? endforeach; ?>
        <? else: ?>
            <tr><td colspan="6" style="text-align: center;">No Record(s) Found</td></tr>
        <? endif; ?>
    </tbody>
</table>