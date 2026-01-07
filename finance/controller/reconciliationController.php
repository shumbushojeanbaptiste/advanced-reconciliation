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
                <?= htmlspecialchars($row['narrative']); ?>
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
        
    default:
        echo 'Invalid action';
        break;
}
?>


