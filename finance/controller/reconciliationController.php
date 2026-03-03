<?php 
require_once("../../include/initialize.php");



function    initList(){
     global $db; 
    ?>
    <br>
  <table class="table table-hover" id="dataTable-10" style="font-size:11px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Accontd / wallets</th>
            <th>Period Covered</th>
            <th>Reffrence code</th>
             <th> Narratives</th>
             <th> Status</th>
            
            <th></th>
          
        </tr>
    </thead>
    <tbody>
    <?php
         $sql = $db->query("
    SELECT 
        rm.reco_mode_id,
        rm.reco_code,
        rm.from_date,
        rm.to_date,
        rm.created_at AS reco_created_at,
        rm.updated_at,
        rm.created_by,
        rm.narrative,
        rm.status AS reco_status,

        a.account_id,
        a.account_name,
        a.account_reference,
        a.account_method_type,
        a.balance,
        a.created_at AS account_created_at,

        pm.method_name
    FROM reconciliation_mode rm
    INNER JOIN tbl_accounts a 
        ON a.account_id = rm.account_id
    LEFT JOIN payment_methods pm 
        ON pm.method_id = a.account_method_type
    ORDER BY rm.created_at DESC
 ");

        $sql->execute();

        $i = 0;
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $i++;
            $reco_id=$row['reco_mode_id'];
            $status=$row['reco_status'];
            $toggle='';
            if($status == 1){
                 $toggle='primary';
            }
             if($status == 0){
                 $toggle='danger';
            }
             if($status == 4){
                 $toggle='success';
            }
            
    ?>
        <tr>
            <td><?= $i; ?></td>

            <td>
               
                <b><?= htmlspecialchars($row['account_name'])." | ".htmlspecialchars($row['method_name']); ?></b>
            </td>

            <td>
               <?= "<b>".date('d M Y', strtotime($row['from_date'])) . '</b> up to <b>' . date('d M Y', strtotime($row['to_date']))."</b>" ?>

            </td>
              <td>
                <b>#<?= htmlspecialchars($row['reco_code']); ?></b>
            </td>
              <td>
                <?= htmlspecialchars($row['narrative'] ?? '-'); ?>
            </td>
            <td> 
    <div class="custom-control custom-switch switch-<?= $toggle ?>">
    <input type="checkbox"
           class="custom-control-input"
           id="flexSwitch<?= $reco_id ?>" checked>
    <label class="custom-control-label"
           for="flexSwitch<?= $reco_id ?>"></label>
     </div>


            </td>

           

          

            <td class="text-center">
                
                <a href="javascript:void(0);"
                   onClick="removeInit(<?= $row['reco_mode_id']; ?>);"
                   data-id="<?= $row['reco_mode_id']; ?>"
                   class="delete_data">
                    <i class="fe fe-trash-2 text-danger"></i>
                </a>
            </td>
        </tr>

        <?php //include 'account_update_modal.php'; ?>
    <?php } ?>
    </tbody>

     <tr>
        <td colspan="4"></td>
        
        <td colspan="3" class="text-center">
            <button class="btn btn-primary"  data-toggle="modal" data-target="#newInitModal">
   <small> start new  <i class="fe fe-arrow-right fe-16 ml-2"></i></small>
   </button>
        </td>

    </tr>
    
  </table>



  <?php

}


function initReconciliation() {
    global $db;
    global $USER_ID;

    header('Content-Type: application/json');

    // Collect & sanitize inputs
    $from_date   = $_POST['date_from'] ?? null;
    $to_date     = $_POST['to_date'] ?? null;
    $account_id  = $_POST['wallet_type'] ?? null;
    $narrative =  $_POST['narrative'] ?? null;

    // Basic validation
    if (!$from_date || !$to_date || !$account_id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required'
        ]);
        return;
    }

    // Date validation
    if ($from_date > $to_date) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Starting date must be earlier than ending date'
        ]);
        return;
    }

    // Check if reconciliation already exists for same account & period
    $check = $db->prepare("
        SELECT reco_mode_id 
        FROM reconciliation_mode
        WHERE account_id = :account_id and (status = 1 or status = 1)
        LIMIT 1
    ");
    $check->execute([
        ':account_id' => $account_id
    ]);

    if ($check->rowCount() > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'This account is already registered for the selected period'
        ]);
        return;
    }

    // Generate reconciliation code
    $reco_code = 'RECO-' . date('YmdHis');

    // Insert reconciliation
    $insert = $db->prepare("
        INSERT INTO reconciliation_mode
        (reco_code, account_id, from_date, to_date, created_by, status,narrative, created_at)
        VALUES
        (:reco_code, :account_id, :from_date, :to_date, :created_by, 1,:narrative, NOW())
    ");

    $success = $insert->execute([
        ':reco_code'  => $reco_code,
        ':account_id' => $account_id,
        ':from_date'  => $from_date,
        ':to_date'    => $to_date,
        ':created_by' => $USER_ID,
        ':narrative' => $narrative
    ]);

    if ($success) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Reconciliation initialized successfully',
            'reco_code' => $reco_code
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to save reconciliation'
        ]);
    }
}


function deleteInit() {
    global $db;

    header('Content-Type: application/json');

    $reco_mode_id = intval($_POST['reco_mode_id'] ?? 0);

    if ($reco_mode_id <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid reconciliation ID'
        ]);
        exit;
    }

    // Check if reconciliation is already used
    $check = $db->prepare("
        SELECT reconciliation_mode_id
        FROM tbl_reconciliation
        WHERE reconciliation_mode_id = :reco_mode_id
        LIMIT 1
    ");
    $check->execute([
        ':reco_mode_id' => $reco_mode_id
    ]);

    if ($check->rowCount() > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Cannot delete. Reconciliation already has transactions.'
        ]);
        exit;
    }

    // 🗑 Delete reconciliation mode
    $delete = $db->prepare("
        DELETE FROM reconciliation_mode
        WHERE reco_mode_id = :reco_mode_id
        LIMIT 1
    ");

    if ($delete->execute([':reco_mode_id' => $reco_mode_id])) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Reconciliation deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to delete reconciliation'
        ]);
    }

    exit;
}

function proccessListAndAction(){

    
    global $db;

    // Optional filter (example: by income type or reference)
    $account_id = $_POST['account_id'] ?? '';
    $description = $_POST['description'] ?? '';
    $code=  $_POST['code'] ?? '';
    $reco_mode_id=  $_POST['reco_mode_id'] ?? '';
    $keyword = intval($account_id);
    
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
    WHERE account_id = :account_id
    ORDER BY transaction_date DESC
");

$stmt->execute([':account_id' => $account_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);


     $stmtBalance = $db->prepare("
            SELECT balance 
            FROM tbl_accounts 
            WHERE account_id = :account_id
            LIMIT 1
        ");

        $stmtBalance->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmtBalance->execute();
        $row = $stmtBalance->fetch(PDO::FETCH_ASSOC);
        $balance = $row['balance'];


            // Load bank transaction
     $stmt = $db->prepare("
    SELECT 
        COALESCE(SUM(debit_amount), 0)  AS total_debit,
        COALESCE(SUM(credit_amount), 0) AS total_credit
    FROM tbl_bank_transaction
    WHERE account_id = :account_id
      AND reference_code = :reference_code and (status=1 OR status=2)
");

$stmt->execute([
    ':account_id' => (int)$account_id,
    ':reference_code' => $code
]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total= (float)$row['total_credit'] + (float)$row['total_debit'];
$balance = (float)$row['total_credit'] - (float)$row['total_debit'];


// reconcilied
$recStmt = $db->prepare("
    SELECT
        COALESCE(SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END), 0) AS reconciled_credit,
        COALESCE(SUM(CASE WHEN transaction_type = 'expense'  THEN amount ELSE 0 END), 0) AS reconciled_debit
    FROM tbl_reconciliation
    WHERE reference_code = :reference_code
      AND account_id = :account_id
");

$recStmt->execute([
    ':reference_code' => $code,
    ':account_id' => (int)$account_id
]);

$rec = $recStmt->fetch(PDO::FETCH_ASSOC);

$reconciledCredit = (float)$rec['reconciled_credit'];
$reconciledDebit  = (float)$rec['reconciled_debit'];
$recTotal=$reconciledCredit + $reconciledDebit;
$reconciledBalance = $reconciledCredit - $reconciledDebit;

$finishedPercent=$recTotal > 0 && $total > 0 ? ($recTotal / $total) * 100 : 0;
$finishedPercent = round($finishedPercent, 2);

 // all reco mode type 
    $modeStmt = $db->prepare("
        SELECT m_id,re_mode_name 
        FROM reco_mode_type
    ");
    $modeStmt->execute();
    $modeRow = $modeStmt->fetchAll(PDO::FETCH_ASSOC);

  $mode_type = 1;
  // related info
    $stmt = $db->prepare("
            SELECT reconciliation_mode.*, reco_mode_type.*
            FROM reconciliation_mode 
            LEFT JOIN reco_mode_type ON reconciliation_mode.mode_type=reco_mode_type.m_id
            WHERE account_id = :account_id AND reco_mode_id = :reco_mode_id AND (status=1 OR status=3)
            LIMIT 1
        ");

        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->bindParam(':reco_mode_id', $reco_mode_id, PDO::PARAM_INT);
        $stmt->execute();
        // IF exists

        $row= $stmt->fetch();

      //$mode_type=$row['re_mode_name'];
      $period = date('d M Y', strtotime($row['from_date'])) . '</b> up to <b>' . date('d M Y', strtotime($row['to_date'])).'</b>';

     $from_date= $row['from_date'];
     $to_date= $row['to_date'];
      // current transaction on account
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
        reconciled AS status
        
    FROM vw_journal_entries
    WHERE credit_account_id = :account_id  and entry_date BETWEEN :from_date AND :to_date and reconciled=0
    ORDER BY entry_date DESC
");

$stmt->execute([
    ':account_id' => $account_id,
    ':from_date' => $from_date,
    ':to_date' => $to_date
]);
$localTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

    

  
    

  


    <!-- Recent Income Header -->
    <div class="card-header">
        <h5 class="card-title mb-0">
           <small><div class="badge bg-warning my-2">In progress <span class="float-end"><?= $finishedPercent ?> % </span> </div> |</small> <?= $description ?> # <small><?= $period ?> # <?php echo $mode_type; ?></small>   
            <span class="fe fe-arrow-down float-right" onclick="proceedToMakeAudit(<?= $keyword ?>,'<?= $description ?>',<?= $reco_mode_id ?>,'<?= $code ?>');"></span>
        </h5>
    </div>

      <!-- Nav tabs -->
    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <?php 
         foreach ($modeRow as $mode) {
            ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $mode_type === $mode['m_id'] ? 'active' : ''; ?>"  id="general-tab" data-toggle="tab" href="#general<?= $mode['m_id'] ?>" role="tab" aria-controls="general" aria-selected="true">Reconciliation | <?= $mode['re_mode_name'] ?></a>
        </li>
        <?php } ?>  
       
    </ul>

    <!-- Tab content -->
    <div class="tab-content" id="myTabContent">

        <!-- General Tab -->
         <?php 
         foreach ($modeRow as $mode) {
            ?>
        <div class="tab-pane fade show <?php echo $mode_type == $mode['m_id'] ? 'active' : ''; ?>" id="general<?= $mode['m_id'] ?>" role="tabpanel" aria-labelledby="general-tab">
            <div class="row">
                <div class="col-md-8 col-xl-5">
                    <!-- form for upload csv -->
                     <?php 
                    
                    if($mode['m_id']==1){
                        include '../base/csv_upload_form.php';
                        include '../base/reco_mode_type_manualy.php'; 
                    }
                    if($mode['m_id']==2){
                        //include '../base/reco_mode_type_csv_out.php';
                    }
                    if($mode['m_id']==3){
                    //include '../base/reco_mode_type_live.php'; 
                    }
                    ?>

                  
                </div>
                  <div class="col-md-8 col-xl-7">
                    <?php 
                    if($mode['m_id']==1){
                        include '../base/unVerfiedLocal_transaction.php'; 
                    }
                    if($mode['m_id']==2){
                        //include '../base/reco_mode_type_csv_out.php';
                    }
                    if($mode['m_id']==3){
                    //include '../base/reco_mode_type_live.php'; 
                    }
                    ?>
                    </div>

                    <?php 
                    if($mode['m_id']==1){
                        include '../base/reco_statisticsAndProceed.php'; 
                    }
                    ?>

            </div>
        </div>
          <?php } ?>  
       

        

        

    </div>

    <?php

}

function finilizeReco(){
    global $db;

    header('Content-Type: application/json');

    $code = $_POST['code'] ?? '';

    if ($code == '') {
        echo json_encode([
            'status' => 'error',
            'message' => "Invalid reconciliation ID  $code"
        ]);
        exit;
    }

    try {

        // START TRANSACTION
        $db->beginTransaction();

        // 1️⃣ Get reconciliation details (date range + account)
        $stmt = $db->prepare("
            SELECT from_date, to_date, account_id 
            FROM reconciliation_mode 
            WHERE reco_code = :code 
            LIMIT 1
        ");
        $stmt->execute([':code' => $code]);
        $reco = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reco) {
            throw new Exception("Reconciliation not found");
        }

        $from = $reco['from_date'];
        $to   = $reco['to_date'];
        $account_id = $reco['account_id'];

        // 2️⃣ Update Expenses in range
        $updateExpense = $db->prepare("
            UPDATE tbl_expense 
            SET reconciled = 1
            WHERE account_id = :account_id
            AND DATE(paid_at) BETWEEN :from_date AND :to_date
        ");

        $updateExpense->execute([
            ':account_id' => $account_id,
            ':from_date'  => $from,
            ':to_date'    => $to
        ]);

        // 3️⃣ Update Income in range
        $updateIncome = $db->prepare("
            UPDATE tbl_income 
            SET reconciled = 1
            WHERE account_id = :account_id
            AND DATE(received_at) BETWEEN :from_date AND :to_date
        ");

        $updateIncome->execute([
            ':account_id' => $account_id,
            ':from_date'  => $from,
            ':to_date'    => $to
        ]);

        // 4️⃣ Finalize reconciliation
        $updateReco = $db->prepare("
            UPDATE reconciliation_mode
            SET status = 4, updated_at = NOW()
            WHERE reco_code = :code
            LIMIT 1
        ");

        $updateReco->execute([':code' => $code]);

        // COMMIT
        $db->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Reconciliation finalized successfully'
        ]);

    } catch (Exception $e) {

        // ROLLBACK on error
        $db->rollBack();

        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }

    exit;
}

function resetRestart(){
    global $db;

    header('Content-Type: application/json');

    $code = $_POST['code'] ?? '';

    if ($code == '') {
        echo json_encode([
            'status' => 'error',
            'message' => "Invalid reconciliation ID  $code"
        ]);
        exit;
    }

    try {

        // START TRANSACTION
        $db->beginTransaction();

        // 1️⃣ Get reconciliation details (date range + account)
        $stmt = $db->prepare("
            SELECT from_date, to_date, account_id 
            FROM reconciliation_mode 
            WHERE reco_code = :code 
            LIMIT 1
        ");
        $stmt->execute([':code' => $code]);
        $reco = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reco) {
            throw new Exception("Reconciliation not found");
        }

        $from = $reco['from_date'];
        $to   = $reco['to_date'];
        $account_id = $reco['account_id'];

        // 2️⃣ Update Expenses in range
        $updateExpense = $db->prepare("
            UPDATE tbl_expense 
            SET reconciled = 0
            WHERE account_id = :account_id
            AND DATE(paid_at) BETWEEN :from_date AND :to_date
        ");

        $updateExpense->execute([
            ':account_id' => $account_id,
            ':from_date'  => $from,
            ':to_date'    => $to
        ]);

        // 3️⃣ Update Income in range
        $updateIncome = $db->prepare("
            UPDATE tbl_income 
            SET reconciled = 0
            WHERE account_id = :account_id
            AND DATE(received_at) BETWEEN :from_date AND :to_date
        ");

        $updateIncome->execute([
            ':account_id' => $account_id,
            ':from_date'  => $from,
            ':to_date'    => $to
        ]);

        // 4️⃣ Finalize reconciliation
        $updateReco = $db->prepare("
            UPDATE reconciliation_mode
            SET status = 1, updated_at = NOW()
            WHERE reco_code = :code
            LIMIT 1
        ");

        $updateReco->execute([':code' => $code]);

        // delete bank transaction
        $deleteBankTrans = $db->prepare("
            DELETE FROM tbl_bank_transaction
            WHERE  reference_code = :reference_code
        ");
        $deleteBankTrans->execute([
            ':reference_code' => $code
        ]);



        // COMMIT
        $db->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Reconciliation reseted successfully'
        ]);

    } catch (Exception $e) {

        // ROLLBACK on error
        $db->rollBack();

        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }

    exit;
}





    




// Handle AJAX action
$action = $_POST['action'] ?? '';

switch($action) {
    case 'init-list':
        initList();
        break;
    case 'save_init';
    initReconciliation();
         break;
    case 'delete-initReco':
        deleteInit();
        break;
    case 'reconciliation-process':
        proccessListAndAction();
        break;
    case 'finilize':
        finilizeReco();
        break;
    case 'reset-restart':
        resetRestart();
        break;
        
    default:
        echo 'Invalid action';
        break;
}
?>


