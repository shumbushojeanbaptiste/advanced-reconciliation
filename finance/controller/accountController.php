<?php 
require_once("../../include/initialize.php");



function accountList(){
     global $db; 
    ?>
    <br>
<table class="table table-hover" id="dataTable-10" style="font-size:11px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Account Name</th>
            <th>Reference</th>
            <th>Method Type</th>
            <th>Balance</th>
            <th></th>
          
        </tr>
    </thead>
    <tbody>
    <?php
        $sql = $db->prepare("SELECT account_id, account_name, account_reference, account_method_type, balance, created_at,method_name FROM tbl_accounts
        LEFT JOIN payment_methods ON payment_methods.method_id= tbl_accounts.account_method_type");
        $sql->execute();

        $i = 0;
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $i++;
    ?>
        <tr>
            <td><?= $i; ?></td>

            <td>
                <i class="fe fe-credit-card fe-16"></i>
                <b><?= htmlspecialchars($row['account_name']); ?></b>
            </td>

            <td>
                <?= htmlspecialchars($row['account_reference']); ?>
            </td>

            <td>
                <span class="badge badge-info">
                    <?= htmlspecialchars($row['method_name']); ?>
                </span>
            </td>

            <td style="color:green;font-weight:bold;">
                <?= number_format($row['balance'], 2); ?>
            </td>

          

            <td class="text-center">
                <a href="javascript:void(0);"
                   onClick="editAccount(<?= $row['account_id']; ?>);"
                   data-id="<?= $row['account_id']; ?>"
                   class="edit_data">
                    <i class="fe fe-edit"></i>
                </a> |
                <a href="javascript:void(0);"
                   onClick="reoveAccount(<?= $row['account_id']; ?>);"
                   data-id="<?= $row['account_id']; ?>"
                   class="delete_data">
                    <i class="fe fe-trash-2 text-danger"></i>
                </a>
            </td>
        </tr>

        <?php //include 'account_update_modal.php'; ?>
    <?php } ?>
    </tbody>
    <tr>
        <td colspan="3"></td>
        
        <td colspan="3" class="text-center">
            <button class="btn btn-primary"  data-toggle="modal" data-target="#addAccountModal">
  <small><i class="fe fe-plus"></i> new Account </small>
</button>
        </td>

    </tr>
</table>



<?php

}

function methodList(){

       global $db; 
    ?>
<table class="table table-hover" id="dataTable-10" style="font-size:11px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Account Name</th>
            <th>Balance</th>
            <th></th>
          
        </tr>
    </thead>
    <tbody>
    <?php
        $sql = $db->prepare("SELECT method_name,description,method_id  FROM payment_methods");
        $sql->execute();

        $i = 0;
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $i++;
    ?>
        <tr>
            <td><?= $i; ?></td>

            <td>
                
                <b><?= htmlspecialchars($row['method_name']); ?></b>
            </td>

            <td>
                <?= htmlspecialchars($row['description']); ?>
            </td>

           

          

          

            <td class="text-center">
            
                <a href="javascript:void(0);"
                   onClick="removeMethod(<?= $row['method_id']; ?>);"
                   data-id="<?= $row['method_id']; ?>"
                   class="delete_data">
                    <i class="fe fe-trash-2 text-danger"></i>
            </td>
        </tr>

        <?php //include 'account_update_modal.php'; ?>
    <?php } ?>
    </tbody>
</table>

<?php

}

function saveNewAccount() {
    global $db;

    // Basic validation
    if (
        empty($_POST['account_no']) ||
        empty($_POST['wallet_type'] || empty($_POST['account_name']))
    ) {
        echo "<span class='text-danger'>Required fields missing</span>";
        return;
    }

    $account_name        = trim($_POST['account_name']);
    $account_method_type = trim($_POST['wallet_type'] ?? '');
    $account_reference   = trim($_POST['account_no']); // used as reference/description
    
    $balance             = 0;

    // check for duplicate account_reference
    $check = $db->prepare("SELECT COUNT(*) FROM tbl_accounts WHERE account_reference = :reference");
    $check->bindParam(':reference', $account_reference);
    $check->execute();
    if ($check->fetchColumn() > 0) {
        echo "<span class='text-danger'>Error: Account with this reference already exists</span>";
        return;
    }

    try {
        $sql = $db->prepare("
            INSERT INTO tbl_accounts
            (account_name, account_reference, account_method_type, balance, created_at)
            VALUES
            (:name, :reference, :method, :balance, NOW())
        ");

        $sql->bindParam(':name', $account_name);
        $sql->bindParam(':reference', $account_reference);
        $sql->bindParam(':method', $account_method_type);
        $sql->bindParam(':balance', $balance);

        $sql->execute();

        echo "<span class='text-success'>Account saved successfully</span>";

    } catch (PDOException $e) {
        echo "<span class='text-danger'>Error: Account not saved</span>";
        // For debugging only:
        // echo $e->getMessage();
    }
}

function deleteAccount() {
    global $db;

    $account_id = intval($_POST['account_id'] ?? 0);

    if ($account_id <= 0) {
        echo "<span class='text-danger'>Invalid account ID</span>";
        return;
    }

    // check if account has balance or linked transactions before deleting (not implemented here)
    // SELECT `entry_id`, `entry_date`, `debit_account_id`, `credit_account_id`, `amount`, `charges`, `method_id`, `reference_id`, `description`, `created_at`, `user_id`, `action` FROM `tbl_journal_entries` WHERE 1
    $check = $db->prepare("SELECT COUNT(*) FROM tbl_journal_entries WHERE debit_account_id = :id OR credit_account_id = :id");
    $check->bindParam(':id', $account_id);
    $check->execute();
    if ($check->fetchColumn() > 0) {
        echo "<span class='text-danger'>Error: Cannot delete account with linked transactions</span>";
        return;
    }
    

    try {
        $sql = $db->prepare("DELETE FROM tbl_accounts WHERE account_id = :id");
        $sql->bindParam(':id', $account_id);
        $sql->execute();

        echo "<span class='text-success'>Account deleted successfully</span>";

    } catch (PDOException $e) {
        echo "<span class='text-danger'>Error: Account not deleted</span>";
        // For debugging only:
        // echo $e->getMessage();
    }
}

function accountBalance() {
    global $db;

    $account_id = $_POST['account_id'] ?? 0;

    if ($account_id <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid account ID'
        ]);
        return;
    }

    try {
        $stmt = $db->prepare("
            SELECT balance 
            FROM tbl_accounts 
            WHERE account_id = :account_id
            LIMIT 1
        ");

        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Account not found'
            ]);
            return;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $balance = $row['balance'];

        echo json_encode([
            'status'  => 'success',
            'message' => 'Balance returned',
            'balance' => number_format($balance, 2)." Frw"
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error'
        ]);
    }
}
   




// Handle AJAX action
$action = $_POST['action'] ?? '';

switch($action) {
    case 'payment-methods':
        methodList();
        break;
    case 'account-list':
        accountList();
        break;
    case 'save_account';
    saveNewAccount();
         break;
    case 'delete-account':
        deleteAccount();
        break;
    case 'balance':
        accountBalance();
        break;
        
    default:
        echo 'Invalid action';
        break;
}
?>


