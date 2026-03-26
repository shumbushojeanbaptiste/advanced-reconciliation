<?php 
require_once("../../include/initialize.php");



function stakeholderList(){

    
    global $db;

    // Optional filter (example: by income type or reference)
    $keyword = $_POST['keyword1'] ?? '';
    $description = $_POST['description'] ?? '';
    $account_id=$keyword;

    // Fetch recent incomes
    $stmt = $db->prepare("
        SELECT *
        FROM tbl_stakeholders st order by client_id desc
    ");

    $stmt->execute();
    $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);



    ?>

    <!-- Recent Income Header -->
    <div class="card-header">
        <h5 class="card-title mb-0">
           <small> </small> <?= $description ?>
            <span class="fe fe-arrow-down float-right" onclick="stakeList(<?= $keyword ?>,'<?= $description ?>');"></span>
        </h5>
    </div>

    <?php if (!empty($incomes)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>code </th>
                        <th>names</th>
                        <th>Phone</th>
                        <th class="text-end">ID</th>
                        <th>Joined at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1;  
                
                foreach ($incomes as $row): ?>
                    <tr>
                        <td><?= $i++; ?></td>

                        <td>
                            <b><?= htmlspecialchars($row['client_ref_no']); ?></b>
                        </td>

                        <td>
                            <b><?= htmlspecialchars($row['clients_names'])." | ".htmlspecialchars($row['leader_name']); ?></b>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['client_phone']); ?>
                        </td>

                        <td class="text-end">
                            <?= htmlspecialchars($row['id_number'], 2); ?>
                        </td>

                         <td>
                            <?= htmlspecialchars(date("d/m/Y H:i:s", strtotime($row['jained_date']))); ?>
                        </td>

                        <td>
                            <?php if ($row['status'] == 1): ?>
                            <span class="fe fe-check text-success" title="Approved income"></span>
                            <?php else: ?>
                               <span class="fe fe-trash text-danger" title="Below limit — can delete" onClick="cancelIncome(<?= $row['income_id'] ?>,<?= $account_id ?>,'<?= $description ?>','<?= $row['amount'] ?>')"></span>
                            <?php endif; ?>
                            <span id="remove_spinner<?= $row['client_id'] ?>"> </span>
                        </td>
                         
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No income records found.</p>
    <?php endif;

}

function saveNewIncomeStakeHolder() {
    global $db;
    
    // 
    $pdo= $db;

    header('Content-Type: application/json');

    $stakeholder_type_id = trim($_POST['stakeholder_type_id'] ?? '');
    $names               = trim($_POST['names'] ?? '');
    $phone               = trim($_POST['phone'] ?? '');
    $ID_number           = trim($_POST['ID_number'] ?? '');
    $email               = trim($_POST['email'] ?? '');
    $leader              = trim($_POST['leader'] ?? '');
    $tin_no              = trim($_POST['tin_no'] ?? '');
    $client_code         =  $_POST['client_code'] ?? "STK-" . time(). "-".date("Y");

    try {
        // Simple validation
        if (empty($names) || empty($phone)) {
            throw new Exception("Name and phone are required");
        }

        // Check duplicate phone
        $stmtCheck = $pdo->prepare("SELECT client_id FROM tbl_stakeholders WHERE client_phone = :phone OR client_ref_no = :client_code");
        $stmtCheck->execute([':phone' => $phone,':client_code' => $client_code]);
        if ($stmtCheck->rowCount() > 0) {
            throw new Exception("Phone number or code already exists");
        }

        // Insert using named placeholders
        $stmt = $pdo->prepare("
            INSERT INTO tbl_stakeholders
            (client_ref_no, clients_names, client_type, tin_no, leader_name, client_phone, id_number, client_email)
            VALUES (:client_ref_no, :clients_names, :client_type, :tin_no, :leader_name, :client_phone, :id_number, :client_email)
        ");

        $stmt->execute([
            ':client_ref_no'   => $client_code,
            ':clients_names'   => $names,
            ':client_type'     => $stakeholder_type_id,
            ':tin_no'          => $tin_no,
            ':leader_name'     => $leader,
            ':client_phone'    => $phone,
            ':id_number'       => $ID_number,
            ':client_email'    => $email
        ]);

        echo json_encode([
            "status" => "success",
            "message" => "Stakeholder saved successfully"
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }

    exit;
}




    




// Handle AJAX action
$action = $_POST['action'] ?? '';

switch($action) {
    case 'list':
        stakeholderList();
        break;
    case 'save_stakeholder_data';
    saveNewIncomeStakeHolder();
         break;
    
        
    default:
        echo 'Invalid action';
        break;
}
?>


