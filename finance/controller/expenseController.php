<?php 
require_once("../../include/initialize.php");



function typeList(){
     global $db; 
    ?>
    <br>
 <table class="table table-hover" id="dataTable-10" style="font-size:11px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Expense Type Name</th>
            <th>Description</th>
            
            <th></th>
          
        </tr>
    </thead>
    <tbody>
    <?php
        $sql = $db->prepare("SELECT `category_id`, `category_name`, `description`  FROM expense_categories");
        $sql->execute();

        $i = 0;
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $i++;
    ?>
        <tr>
            <td><?= $i; ?></td>

            <td>
               
                <b><?= htmlspecialchars($row['category_name']); ?></b>
            </td>

            <td>
                <?= htmlspecialchars($row['description']); ?>
            </td>

           

          

            <td class="text-center">
                <a href="javascript:void(0);"
                   onClick="editAccount(<?= $row['category_id']; ?>);"
                   data-id="<?= $row['category_id']; ?>"
                   class="edit_data">
                    <i class="fe fe-edit"></i>
                </a> |
                <a href="javascript:void(0);"
                   onClick="reoveExpType(<?= $row['category_id']; ?>);"
                   data-id="<?= $row['category_id']; ?>"
                   class="delete_data">
                    <i class="fe fe-trash-2 text-danger"></i>
                </a>
            </td>
        </tr>

        <?php //include 'account_update_modal.php'; ?>
    <?php } ?>
    </tbody>
    
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

function saveNewType() {
    global $db;

    // Basic validation
    if (
        empty($_POST['type_name']) ||
        empty($_POST['description'] )
    ) {
        echo "<span class='text-danger'>Required fields missing</span>";
        return;
    }

    $type_name        = trim($_POST['type_name']);
    $description = trim($_POST['description'] ?? '');
    
    $balance             = 0;

    // check for duplicate account_reference
    $check = $db->prepare("SELECT COUNT(*) FROM expense_categories WHERE category_name = :category_name");
    $check->bindParam(':category_name', $type_name);
    $check->execute();
    if ($check->fetchColumn() > 0) {
        echo "<span class='text-danger'>Error:  type already exists</span>";
        return;
    }

    try {
        $sql = $db->prepare("
            INSERT INTO expense_categories
            (category_name, description)
            VALUES
            (:category_name, :description)
        ");

        $sql->bindParam(':category_name', $type_name);
        $sql->bindParam(':description', $description);;

        $sql->execute();

        echo "<span class='text-success'>expense type saved successfully</span>";

    } catch (PDOException $e) {
        echo "<span class='text-danger'>Error: expense type not saved</span>";
        // For debugging only:
        // echo $e->getMessage();
    }
}

function deleteType() {
    global $db;

    $type_id = intval($_POST['type_id'] ?? 0);

    if ($type_id <= 0) {
        echo "<span class='text-danger'>Invalid type ID</span>";
        return;
    }

    // check if account has balance or linked transactions before deleting (not implemented here)
    // SELECT `entry_id`, `entry_date`, `debit_account_id`, `credit_account_id`, `amount`, `charges`, `method_id`, `reference_id`, `description`, `created_at`, `user_id`, `action` FROM `tbl_journal_entries` WHERE 1
    $check = $db->prepare("SELECT COUNT(*) FROM tbl_journal_entries WHERE reference_id = :id");
    $check->bindParam(':id', $type_id);
    $check->execute();
    if ($check->fetchColumn() > 0) {
        echo "<span class='text-danger'>Error: Cannot delete expense type with linked transactions</span>";
        return;
    }
    

    try {
        $sql = $db->prepare("DELETE FROM expense_categories WHERE category_id = :id");
        $sql->bindParam(':id', $type_id);
        $sql->execute();

        echo "<span class='text-success'>type deleted successfully</span>";

    } catch (PDOException $e) {
        echo "<span class='text-danger'>Error: type not deleted</span>";
        // For debugging only:
        // echo $e->getMessage();
    }
}

    




// Handle AJAX action
$action = $_POST['action'] ?? '';

switch($action) {
    case 'payment-methods':
        methodList();
        break;
    case 'expense-type-list':
        typeList();
        break;
    case 'save_exp_type';
    saveNewType();
         break;
    case 'delete-expense-type':
        deleteType();
        break;
        
    default:
        echo 'Invalid action';
        break;
}
?>


