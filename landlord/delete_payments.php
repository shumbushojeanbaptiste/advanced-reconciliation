<?php
require_once("../include/initialize.php");

$checkadv = 0;
$checkpyt = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentIds = $_POST['payment_ids'] ?? [];

    if (!empty($paymentIds) && is_array($paymentIds)) {
        // Prepare placeholders for IN clause
        $placeholders = implode(',', array_fill(0, count($paymentIds), '?'));
        
        // Prepare SQL statement
        $sqldel1 = $db->prepare("DELETE FROM tbl_payment WHERE status = 2 AND pay_id IN ($placeholders)");

        // Execute with array values
        if ($sqldel1->execute($paymentIds)) {
            echo "Done";
        } else {
            echo "Error: Unable to delete payments.";
        }
    } else {
        echo "No payments selected.";
    }
}
?>
