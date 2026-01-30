  
  <style>
.cursor-pointer {
    cursor: pointer;
}
.fe-plus-circle:hover,
.fe-minus-circle:hover {
    opacity: 0.8;
}
</style>

  <?php if (!empty($transactions)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Transaction Type</th>
                        <th class="text-end">Income (FRW)</th>
                        <th class="text-end">Expense (FRW)</th>
                       
                       
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                           
                $i = 1;  
                
                foreach ($transactions as $row): 
                    if ($row['debit_amount'] > 0) {
                        $debit = number_format($row['debit_amount'], 0);
                    } else {
                        $debit = '-';
                    }

                    if ($row['credit_amount'] > 0) {
                        $credit = number_format($row['credit_amount'], 0);
                    } else {
                        $credit = '-';
                    }

                    $td_class = '';
                    if ($row['status'] == 0) {
                        $td_class = '';
                    }
                    $issues= $row['issues'];
                    // set isseus  title area and look as html alert
                    $alert_class = '';
                    if (!empty($issues)) {
                        $alert_class = '<div class="alert alert-danger" role="alert">
                        ' . htmlspecialchars($issues) . '</div>';
                    }

              $hasIssues = !empty($issues);

                  
                 
                ?>
                    <tr class="<?= $td_class ?>">
    <td>
        <?= $i++; ?>

        <?php if ($hasIssues): ?>
            <span class="fe fe-plus-circle text-danger ms-2 cursor-pointer"
                  id="toggleIcon<?= $row['transaction_id'] ?>"
                  title="Show issues"
                  onclick="toggleIssues(<?= $row['transaction_id'] ?>)">
            </span>
        <?php endif; ?>
    </td>

    <td>
        <b><?= htmlspecialchars(date("d/m/Y", strtotime($row['transaction_date']))); ?></b>
    </td>

    <td>
        <b><?= htmlspecialchars($row['transaction_type']); ?></b>
    </td>

    <td class="text-end"><?= $credit; ?></td>
    <td class="text-end"><?= $debit; ?></td>

    <td>
        <?php if ($row['status'] == 0): ?>
            <span class=" text-danger">Issues</span>
        <?php else: ?>
            <span class="fe fe-check text-success" title="Approved transaction"></span>
        <?php endif ?>
    </td>
</tr>

<?php if ($hasIssues): ?>
<tr id="issuesRow<?= $row['transaction_id'] ?>" style="display:none;">
    <td colspan="6">
        <div class="alert alert-danger mb-0">
            <b>Issues found:</b><br>
            <?= nl2br(htmlspecialchars($issues)); ?>
        </div>
    </td>
</tr>
<?php endif; ?>


                <?php endforeach; ?>
                <tr>
                    <th colspan="3" class="text-end">Balance:</th>
                    <th class="text-end">
                        <?php 
                            $totalCredit = array_sum(array_column($transactions, 'credit_amount'));
                            echo number_format($totalCredit, 2); 
                        ?>
                    </th>
                    <th class="text-end ">
                        <?php 
                            $totalDebit = array_sum(array_column($transactions, 'debit_amount'));
                            echo number_format($totalDebit, 2); 
                        ?>
                    </th>
                    <th class="text-end">
                        <?= number_format($totalCredit - $totalDebit, 2); ?>
                    </th>
                  
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No records found.</p>
    <?php endif; ?>

    <script>
function toggleIssues(id) {
    const row = document.getElementById('issuesRow' + id);
    const icon = document.getElementById('toggleIcon' + id);

    if (!row) return;

    if (row.style.display === 'none') {
        row.style.display = '';
        icon.classList.remove('fe-plus-circle');
        icon.classList.add('fe-minus-circle');
        icon.title = 'Hide issues';
    } else {
        row.style.display = 'none';
        icon.classList.remove('fe-minus-circle');
        icon.classList.add('fe-plus-circle');
        icon.title = 'Show issues';
    }
}
</script>
