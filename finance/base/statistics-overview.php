  <?php 
  require_once("../../include/initialize.php");
   $from_date= $_POST['date_from'];
   $account_id = 1;
   $to_date = $_POST['date_to'];
   $action = $_POST['action'];

  // current transaction on account

  if ($action != "ALL"){
      $stmt = $db->prepare("
    SELECT 
        entry_key AS transaction_id,
        transaction_code,
        entry_date AS transaction_date,
        amount,
        charges,
        description AS reference,
        reconciled AS status,
        action,
        credit_account_id,
        reconciled AS status
        
    FROM vw_journal_entries
    WHERE action = :action AND entry_date BETWEEN :from_date AND :to_date 
    ORDER BY entry_date asc
");

$stmt->execute([
    ':action' => $action,
    ':from_date' => $from_date,
    ':to_date' => $to_date
]);
  }
  else {
    $stmt = $db->prepare("
    SELECT 
        entry_key AS transaction_id,
        transaction_code,
        entry_date AS transaction_date,
        amount,
        charges,
        description AS reference,
        reconciled AS status,
        action,
        credit_account_id,
        reconciled AS status
        
    FROM vw_journal_entries
    WHERE entry_date BETWEEN :from_date AND :to_date 
    ORDER BY entry_date asc
");

$stmt->execute([
    ':from_date' => $from_date,
    ':to_date' => $to_date
]);

  }
$localTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
  

<div class="container py-4">

     <div class="card-body">
        <br>
     

  <?php
  $totalAmount = 0;
  $totalCharges = 0;
  $i2 = 0;
  if (!empty($localTransactions)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                       
                        <th>Date</th>
                        <th>Transaction Type</th>
                        <th></th>
                        <th class="text-end">Amount (FRW)</th>
                        <th class="text-end">Charges (FRW)</th>
                         <th class="text-end">Total (FRW)</th>
                       
                       
                        <th class="text-end">Balance (FRW)</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                           
                $i2 = 1;  
                $totalAmount = 0;
                $totalCharges = 0;
                $balance = 0;
                
                foreach ($localTransactions as $row): 
              
                        $amount = number_format($row['amount'], 0);
                        $charges = number_format($row['charges'], 0);
                        $total = number_format($row['amount'] + $row['charges'], 0);
                        $sub = $row['amount'] + $row['charges'];
                        $totalAmount += $row['amount'];
                        $totalCharges += $row['charges'];
                        if ($row['action'] == "INCOME"){
                          $balance =  $balance + $sub;
                        }
                        else {
                            $balance =  $balance - $sub;

                        }

                $stmtMode = $db->query('SELECT * FROM tbl_accounts where account_id="'.$row['credit_account_id'].'" ');
                $rowdata = $stmtMode->fetch(PDO::FETCH_ASSOC);
                $mode_name = $rowdata['account_name'];
                         
                     

                    
                 
                ?>
                    <tr>
                       

                        <td>
                            <?= $i2++.". "; ?><b><?= htmlspecialchars(date("d/m/Y", strtotime($row['transaction_date']))); ?></b>
                        </td>

                        <td>
                            <b><small><?= htmlspecialchars($row['action']); ?></small></b>
                        </td>
                        <td> <?= $mode_name ?> </td>


                        <td class="text-end">
                            <?= $amount; ?>
                        </td>
                          <td class="text-end text-danger">
                            <?= $charges; ?>
                        </td>
                            <td class="text-end">
                                <?= $total; ?>
                            </td>
                         

                      

                       <td class="text-end">
                                <?= $balance; ?>
                            </td>
                        
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="3" class="text-end">Balance:</th>
                    <th class="text-end">
                        <?= number_format($totalAmount, 2); ?>
                    </th>
                    <th class="text-end text-danger">
                        <?= number_format($totalCharges, 2); ?>
                    </th>
                    <th class="text-end">
                        <?= number_format($totalAmount - $totalCharges, 2); ?>
                    </th>
                    <th></th>
                </tr>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No records found. <?=   $action ?></p>
    <?php endif; ?>

    </div>
    </div>
    </div>