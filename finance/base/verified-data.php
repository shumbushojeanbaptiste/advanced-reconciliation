  <?php 
  require_once("../../include/initialize.php");
   //$from_date= $_POST['date_from'];
   $account_id = 1;
   $mode_reco = $_POST['mode_reco'];
  



   // Fetch transaction records
    $stmt = $db->prepare("
    SELECT 
        transaction_id,
        reference_code,
        transaction_code,
        transaction_type,
        debit_amount,
        credit_amount,
        transaction_date,
        reference,
        status,
        created_at,
        issues
    FROM tbl_bank_transaction
    WHERE reference_code = :reference_code
    ORDER BY transaction_date DESC
"); 

$stmt->execute([':reference_code' => $mode_reco]); 
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
  

<div class="container py-4">

     <div class="card-body">
        <br>
     
<?php
  
  $i = 0;  
                $totalCredit = 0;
                $totalDebit = 0;
  if (!empty($transactions)): ?>
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
                $totalCredit = 0;
                $totalDebit = 0;
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
            <span class=" text-danger"></span>
        <?php else: ?>
            <?php $totalCredit += $row['credit_amount']; ?>
            <?php $totalDebit += $row['debit_amount']; ?>
            <span class="fe fe-check text-success" title="Approved transaction"></span>
        <?php endif ?>

        <?php if ($hasIssues): ?>
            <span class="fe fe-plus-circle text-danger ms-2 cursor-pointer"
                  id="toggleIcon<?= $row['transaction_id'] ?>"
                  title="Show issues"
                  onclick="toggleIssues(<?= $row['transaction_id'] ?>)">
            </span>
        <?php endif; ?>
    </td>
</tr>

<?php if ($hasIssues): ?>
<tr id="issuesRow<?= $row['transaction_id'] ?>" style="display:none;">
    <td colspan="5">
        <div class="alert alert-danger mb-0">
            <b>Issues found:</b><br>
            <?= nl2br(htmlspecialchars($issues)); 
             $credit = 0;
             $debit = 0;
            ?>
        </div>
    </td>
</tr>
<?php endif; ?>


                <?php endforeach; ?>
                <tr>
                    <th colspan="2" class="text-end">Balance:</th>
                    <th class="text-end">
                        <?php 
                            //$totalCredit = array_sum(array_column($transactions, 'credit_amount'));
                            echo number_format($totalCredit, 2); 
                        ?>
                    </th>
                    <th class="text-end ">
                        <?php 
                           // $totalDebit = array_sum(array_column($transactions, 'debit_amount'));
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
    <p class="text-muted text-center"> No records found.</p>
    <?php endif; ?>

    </div>
    </div>
    </div>