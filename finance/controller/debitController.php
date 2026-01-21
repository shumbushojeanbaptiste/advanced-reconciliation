<?php 
require_once("../../include/initialize.php");



function recentExpense() {

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
        <p class="text-muted text-center">No expense records found.</p>
    <?php endif;

}



function saveExpense() {
    global $db;
    global $USER_ID;

    header('Content-Type: application/json');

    // Collect inputs
    $due_date     = $_POST['due_date'] ?? null;
    $amount       = floatval($_POST['amount'] ?? 0);
    $charges      = floatval($_POST['charges'] ?? 0);
    $description  = $_POST['description'] ?? '';
    $account_id   = $_POST['account_id'] ?? null;
    $expense_type = $_POST['expense_type'] ?? null;

    // Validation
    if ($amount <= 0 || !$due_date || !$account_id || !$expense_type) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All required fields must be provided'
        ]);
        return;
    }

    try {
        // START TRANSACTION
        $db->beginTransaction();

        // 1️⃣ Lock account row and get balance
        $stmtCheck = $db->prepare("
            SELECT balance 
            FROM tbl_accounts 
            WHERE account_id = :account_id
            FOR UPDATE
        ");
        $stmtCheck->execute([
            ':account_id' => $account_id
        ]);

        $account = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$account) {
            throw new Exception("Account not found");
        }

        // 2️⃣ Calculate total expense
        $totalExpense = $amount + $charges;

        // 3️⃣ Check if balance is sufficient
        if ($account['balance'] < $totalExpense) {
            throw new Exception("Insufficient balance. Available: {$account['balance']}, Required: {$totalExpense}");
        }

        // 4️⃣ Generate payment reference
        $payment_reference = 'TxE-' . time();

        // 5️⃣ Insert expense
        $stmtExpense = $db->prepare("
            INSERT INTO tbl_expense 
            (payment_reference, expense_type, amount, paid_at, reconciled, created_at, recorded_by, account_id, narrative, charges)
            VALUES 
            (:ref, :expense_type, :amount, :paid_at, 0, NOW(), :user_id, :account_id, :narrative, :charges)
        ");

        $stmtExpense->execute([
            ':ref'          => $payment_reference,
            ':expense_type' => $expense_type,
            ':amount'       => $amount,
            ':paid_at'      => $due_date,
            ':user_id'      => $USER_ID,
            ':account_id'   => $account_id,
            ':narrative'    => $description,
            ':charges'     => $charges
        ]);

        // 6️⃣ Deduct balance
        $stmtBalance = $db->prepare("
            UPDATE tbl_accounts
            SET balance = balance - :amount
            WHERE account_id = :account_id
        ");

        $stmtBalance->execute([
            ':amount'     => $totalExpense,
            ':account_id' => $account_id
        ]);

        // COMMIT TRANSACTION
        $db->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Expense recorded successfully',
            'reference' => $payment_reference,
            'remaining_balance' => $account['balance'] - $totalExpense
        ]);

    } catch (Exception $e) {
        // ROLLBACK ON ERROR
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
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
            i.expense_id,
            i.payment_reference,
            i.amount,
            i.paid_at,
            i.reconciled,
            i.created_at,
            i.charges,
            cat.category_name
        FROM tbl_expense i
        LEFT JOIN expense_categories cat 
            ON cat.category_id = i.expense_type

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
           <small> Recent expenses on</small> <?= $description ?>
            <span class="fe fe-arrow-down float-right" onclick="recent_expenseAccount(<?= $keyword ?>,'<?= $description ?>');"></span>
        </h5>
    </div>

    <?php if (!empty($incomes)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Expense Type</th>
                        <th>Reference</th>
                        <th class="text-end">Amount (FRW)</th>
                        <th class="text-end">Charge (FRW)</th>
                        <th class="text-end">Total (FRW)</th>
                        <th>Done at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1;  
                $limit=$balance;
                foreach ($incomes as $row): 
                  $total=$row['amount'] + $row['charges'];
                ?>
                    <tr>
                        <td><?= $i++; ?></td>

                        <td>
                            <b><?= htmlspecialchars(date("d/m/Y", strtotime($row['paid_at']))); ?></b>
                        </td>

                        <td>
                            <b><?= htmlspecialchars($row['category_name']); ?></b>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['payment_reference']); ?>
                        </td>

                        <td class="text-end">
                            <?= number_format($row['amount'], 2); ?>
                        </td>
                          <td class="text-end text-danger">
                            <?= number_format($row['charges'], 2); ?>
                        </td>
                          <td class="text-end">
                            <?= number_format($total, 2); ?>
                        </td>

                         <td>
                            <?= htmlspecialchars(date("d/m/Y H:i:s", strtotime($row['created_at']))); ?>
                        </td>

                        <td>
                          
                            <span class="fe fe-trash text-danger" title="Below limit — can delete" onClick="cancelIncome(<?= $row['expense_id'] ?>,<?= $account_id ?>,'<?= $description ?>','<?= $row['amount'] ?>')"></span>
                            
                            <span id="remove_spinner<?= $row['expense_id'] ?>"> </span>
                        </td>
                         <?php   $limit=$limit-$row['amount']; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No expense records found.</p>
    <?php endif;

}


    




// Handle AJAX action
$action = $_POST['action'] ?? '';

switch($action) {
    case 'recent':
    recentExpense();
        break;
    case 'save_expense_data';
    saveExpense();
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


