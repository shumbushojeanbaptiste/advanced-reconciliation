<?php 
require_once("../../include/initialize.php");



function recentIncome() {

    global $db;

    // Optional filter (example: by income type or reference)
    $keyword = $_POST['keyword1'] ?? '';

    // Fetch recent incomes
    $stmt = $db->prepare("
        SELECT 
            i.income_id,
            i.payment_reference,
            i.amount,
            i.received_at,
            i.reconciled,
            i.created_at,
            it.income_name
        FROM tbl_income i
        LEFT JOIN tbl_income_type it 
            ON it.income_type_id = i.income_type_id
        ORDER BY i.created_at DESC
        LIMIT 100
    ");

    $stmt->execute();
    $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Recent Income Header -->
    <div class="card-header">
        <h5 class="card-title mb-0">
            Recent Income
             <span class="fe fe-arrow-down float-right" onclick="recent_income(1);"></span>
        </h5>
    </div>

    <?php if (!empty($incomes)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Income Type</th>
                        <th>Reference</th>
                        <th class="text-end">Amount (FRW)</th>
                        <th>Done at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1; foreach ($incomes as $row): ?>
                    <tr>
                        <td><?= $i++; ?></td>

                        <td>
                            <b><?= htmlspecialchars(date("d/m/Y", strtotime($row['received_at']))); ?></b>
                        </td>

                        <td>
                            <b><?= htmlspecialchars($row['income_name']); ?></b>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['payment_reference']); ?>
                        </td>

                        <td class="text-end">
                            <?= number_format($row['amount'], 2); ?>
                        </td>

                         <td>
                            <?= htmlspecialchars(date("d/m/Y H:i:s", strtotime($row['created_at']))); ?>
                        </td>

                        <td>
                            <?php if ($row['reconciled'] >= 0): ?>
                            <span class="fe fe-check text-success" title="Approved deposit"></span>
                            <?php else: ?>
                               <span class="fe fe-trash text-danger" title="Below limit — can delete" onClick="cancelIncome()"></span>
                            <?php endif; ?>
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



function saveIncome() {
    global $db;
    global $USER_ID;

    header('Content-Type: application/json');

    // Collect inputs
    $due_date        = $_POST['due_date'] ?? null;
    $amount          = $_POST['amount'] ?? null;
    $description     = $_POST['description'] ?? '';
    $account_id      = $_POST['account_id'] ?? null;
    $income_type_id  = $_POST['income_type'] ?? null;

    // Validation
    if (!$amount || !$due_date || !$account_id || !$income_type_id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required'
        ]);
        return;
    }

    try {
        // START TRANSACTION
        $db->beginTransaction();

        // 1️⃣ Generate payment reference
        $payment_reference = 'TxI-' . time();

        // 2️⃣ Insert income
        $stmtIncome = $db->prepare("
            INSERT INTO tbl_income 
            (payment_reference, income_type_id, amount, received_at, reconciled, created_at, recorded_by, account_id, narrative)
            VALUES 
            (:ref, :income_type, :amount, :received_at, 0, NOW(), :user_id,:account_id, :narrative)
        ");

        $stmtIncome->execute([
            ':ref'          => $payment_reference,
            ':income_type'  => $income_type_id,
            ':amount'       => $amount,
            ':received_at'  => $due_date,
            ':user_id'      => $USER_ID,
            ':account_id'  => $account_id,
            ':narrative'      => $description
        ]);

        $income_id = $db->lastInsertId();

        // 4️⃣ Update account balance (add income)
        $stmtBalance = $db->prepare("
            UPDATE tbl_accounts
            SET balance = balance + :amount
            WHERE account_id = :account_id
        ");

        $stmtBalance->execute([
            ':amount'     => $amount,
            ':account_id' => $account_id
        ]);

        // COMMIT TRANSACTION
        $db->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Income recorded successfully',
            'reference' => $payment_reference
        ]);

    } catch (Exception $e) {

        // ROLLBACK IF ANY ERROR
        $db->rollBack();

        echo json_encode([
            'status' => 'error',
            'message' => 'Transaction failed',
            'error' => $e->getMessage()
        ]);
    }
}

function deleteIncome() {
    global $db;

    header('Content-Type: application/json');

    $income_id  = intval($_POST['income_id'] ?? 0);

    if ($income_id <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid income ID'
        ]);
        return;
    }

    try {
        //  Start transaction
        $db->beginTransaction();

        /* ===========================
           1. Fetch income record
        ============================ */
        $stmt = $db->prepare("
            SELECT income_id, amount, account_id
            FROM tbl_income
            WHERE income_id = :income_id
            FOR UPDATE
        ");
        $stmt->execute([':income_id' => $income_id]);
        $income = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$income) {
            $db->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Income not found'
            ]);
            return;
        }

        $amount     = (float)$income['amount'];
        $account_id = (int)$income['account_id'];

        /* ===========================
           2. Check account balance
        ============================ */
        $stmt = $db->prepare("
            SELECT balance
            FROM tbl_accounts
            WHERE account_id = :account_id
            FOR UPDATE
        ");
        $stmt->execute([':account_id' => $account_id]);
        $balance = (float)$stmt->fetchColumn();

        if ($balance < $amount) {
            $db->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Cannot delete income. Account balance is insufficient.'
            ]);
            return;
        }

        /* ===========================
           3. Decrement account balance
        ============================ */
        $stmt = $db->prepare("
            UPDATE tbl_accounts
            SET balance = balance - :amount
            WHERE account_id = :account_id
        ");
        $stmt->execute([
            ':amount'     => $amount,
            ':account_id' => $account_id
        ]);

        /* ===========================
           4. Delete income record
        ============================ */
        $stmt = $db->prepare("
            DELETE FROM tbl_income
            WHERE income_id = :income_id
        ");
        $stmt->execute([':income_id' => $income_id]);

        // ✅ Commit transaction
        $db->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Income deleted successfully'
        ]);

    } catch (Exception $e) {
        // Rollback on error
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to delete income',
            'error'   => $e->getMessage()
        ]);
    }
}

function accountRencentIncome(){

    
    global $db;

    // Optional filter (example: by income type or reference)
    $keyword = $_POST['keyword1'] ?? '';
    $description = $_POST['description'] ?? '';
    $account_id=$keyword;

    // Fetch recent incomes
    $stmt = $db->prepare("
        SELECT 
            i.income_id,
            i.payment_reference,
            i.amount,
            i.received_at,
            i.reconciled,
            i.created_at,
            it.income_name
        FROM tbl_income i
        LEFT JOIN tbl_income_type it 
            ON it.income_type_id = i.income_type_id

            where i.account_id = :account_id
        ORDER BY i.created_at DESC
        LIMIT 100
    ");

    $stmt->execute([':account_id' => $account_id]);
    $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);

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


    ?>

    <!-- Recent Income Header -->
    <div class="card-header">
        <h5 class="card-title mb-0">
           <small> Recent Income on</small> <?= $description ?>
            <span class="fe fe-arrow-down float-right" onclick="recent_incomeAccount(<?= $keyword ?>,'<?= $description ?>');"></span>
        </h5>
    </div>

    <?php if (!empty($incomes)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Income Type</th>
                        <th>Reference</th>
                        <th class="text-end">Amount (FRW)</th>
                        <th>Done at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1;  
                $limit=$balance;
                foreach ($incomes as $row): ?>
                    <tr>
                        <td><?= $i++; ?></td>

                        <td>
                            <b><?= htmlspecialchars(date("d/m/Y", strtotime($row['received_at']))); ?></b>
                        </td>

                        <td>
                            <b><?= htmlspecialchars($row['income_name']); ?></b>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['payment_reference']); ?>
                        </td>

                        <td class="text-end">
                            <?= number_format($row['amount'], 2); ?>
                        </td>

                         <td>
                            <?= htmlspecialchars(date("d/m/Y H:i:s", strtotime($row['created_at']))); ?>
                        </td>

                        <td>
                            <?php if ($row['reconciled'] == 1 || $limit < $row['amount']): ?>
                            <span class="fe fe-check text-success" title="Approved income"></span>
                            <?php else: ?>
                               <span class="fe fe-trash text-danger" title="Below limit — can delete" onClick="cancelIncome(<?= $row['income_id'] ?>,<?= $account_id ?>,'<?= $description ?>','<?= $row['amount'] ?>')"></span>
                            <?php endif; ?>
                            <span id="remove_spinner<?= $row['income_id'] ?>"> </span>
                        </td>
                         <?php   $limit=$limit-$row['amount']; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No income records found.</p>
    <?php endif;

}


    




// Handle AJAX action
$action = $_POST['action'] ?? '';

switch($action) {
    case 'recent':
        recentIncome();
        break;
    case 'save_income_data';
    saveIncome();
         break;
    case 'delete-income-data':
        deleteIncome();
        break;
    case 'recent-account':
        accountRencentIncome();
        break;
        
    default:
        echo 'Invalid action';
        break;
}
?>


