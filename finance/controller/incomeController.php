<?php 
require_once("../../include/initialize.php");



function    incomeTypeList(){
     global $db; 
    ?>
    <br>
 <table class="table table-hover" id="dataTable-10" style="font-size:11px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Income Type Name</th>
            <th>Description</th>
            
            <th></th>
          
        </tr>
    </thead>
    <tbody>
    <?php
        $sql = $db->prepare("SELECT `income_type_id`, `income_name`, `description`  FROM tbl_income_type");
        $sql->execute();

        $i = 0;
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $i++;
    ?>
        <tr>
            <td><?= $i; ?></td>

            <td>
               
                <b><?= htmlspecialchars($row['income_name']); ?></b>
            </td>

            <td>
                <?= htmlspecialchars($row['description']); ?>
            </td>

           

          

            <td class="text-center">
                <a href="javascript:void(0);"
                   onClick="editAccount(<?= $row['income_type_id']; ?>);"
                   data-id="<?= $row['income_type_id']; ?>"
                   class="edit_data">
                    <i class="fe fe-edit"></i>
                </a> |
                <a href="javascript:void(0);"
                   onClick="removeIncomeType(<?= $row['income_type_id']; ?>);"
                   data-id="<?= $row['income_type_id']; ?>"
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


function  saveNewIncomeType(){
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
    $check = $db->prepare("SELECT COUNT(*) FROM tbl_income_type WHERE income_name = :income_name");
    $check->bindParam(':income_name', $type_name);
    $check->execute();
    if ($check->fetchColumn() > 0) {
        echo "<span class='text-danger'>Error:  type already exists</span>";
        return;
    }

    try {
        $sql = $db->prepare("
            INSERT INTO tbl_income_type
            (income_name, description)
            VALUES
            (:income_name, :description)
        ");

        $sql->bindParam(':income_name', $type_name);
        $sql->bindParam(':description', $description);;

        $sql->execute();

        echo "<span class='text-success'>income type saved successfully</span>";

    } catch (PDOException $e) {
        echo "<span class='text-danger'>Error: expense type not saved</span>";
        // For debugging only:
        // echo $e->getMessage();
    }
}

function deleteIncomeType() {
    global $db;

    $type_id = intval($_POST['income_type_id'] ?? 0);

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
        echo "<span class='text-danger'>Error: Cannot delete income type with linked transactions</span>";
        return;
    }
    

    try {
        $sql = $db->prepare("DELETE FROM tbl_income_type WHERE income_type_id = :id");
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
    case 'income-type-list':
        incomeTypeList();
        break;
    case 'save_income_type';
    saveNewIncomeType();
         break;
    case 'delete-income-type':
        deleteIncomeType();
        break;
        
    default:
        echo 'Invalid action';
        break;
}
?>


