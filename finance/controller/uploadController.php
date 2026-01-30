<?php
require_once("../../include/initialize.php");

$action = $_POST['action'] ?? '';

switch($action){
    case 'save_bank_file':
        saveDataFile();
        break;
    default:
        echo json_encode(['success'=>false, 'message'=>'Invalid action', 'progress'=>0]);
        break;
}
function saveDataFile(){
    global $db;

    $code       = $_POST['code'] ?? '';
    $account_id = $_POST['account_id'] ?? '';
    $from_date  = $_POST['from_date'] ?? '';
    $to_date    = $_POST['to_date'] ?? '';

    if(!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] != 0){
        echo json_encode(['success'=>false, 'message'=>'No file uploaded', 'progress'=>0]);
        return;
    }

    $file = $_FILES['csv_file']['tmp_name'];

    if(($handle = fopen($file, "r")) === false){
        echo json_encode(['success'=>false, 'message'=>'Failed to open file', 'progress'=>0]);
        return;
    }

    /** 🔥 RESET EXISTING DATA ONCE 🔥 */
    $stmt = $db->prepare(
        "DELETE FROM tbl_bank_transaction 
         WHERE reference_code = ? AND account_id = ?"
    );
    $stmt->execute([$code, $account_id]);

    $batchSize = 50;
    $rows = [];
    $total = 0;
    $inserted = 0;

    // Read header
    $header = fgetcsv($handle, 1000, ",");

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {

        if (count($data) !== count($header)) {
            continue;
        }

        $rows[] = array_combine($header, $data);
        $total++;

        if (count($rows) === $batchSize) {
            processBatch($rows, $code, $account_id, $from_date, $to_date);
            $inserted += count($rows);
            $rows = [];
        }
    }

    // Process remaining rows
    if (!empty($rows)) {
        processBatch($rows, $code, $account_id, $from_date, $to_date);
        $inserted += count($rows);
    }

    fclose($handle);

    echo json_encode([
        'success' => true,
        'message' => "Upload complete! $inserted rows processed.",
        'progress'=> 100
    ]);
}


// Process each batch
function processBatch($rows, $code, $account_id, $from_date, $to_date){
    global $db;

    $stmt = $db->prepare("
        INSERT INTO tbl_bank_transaction 
        (transaction_code, reference_code, account_id, transaction_type, 
         debit_amount, credit_amount, transaction_date, reference, 
         created_at, status, issues)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)
    ");

    foreach($rows as $row){

        $transaction_code = $row['transaction_code'];
        $transaction_type = $row['transaction_type'];
        $debit_amount     = $row['debit'];
        $credit_amount    = $row['credit'];
        $transaction_date = $row['transaction_date'];
        $reference        = $row['reference'];

        $status = 1;
        $issues = [];

        if(!is_numeric($debit_amount)){
            $issues[] = "Invalid debit amount";
            $status = 0;
        }

        if(!is_numeric($credit_amount)){
            $issues[] = "Invalid credit amount";
            $status = 0;
        }

        if(!strtotime($transaction_date)){
            $issues[] = "Invalid date format";
            $status = 0;
        }

        if(
            strtotime($transaction_date) < strtotime($from_date) ||
            strtotime($transaction_date) > strtotime($to_date)
        ){
            $issues[] = "Date out of range";
            $status = 0;
        }

        if(!in_array($transaction_type, ['income','expense'])){
            $issues[] = "Invalid transaction type";
            $status = 0;
        }

        $stmt->execute([
            $transaction_code,
            $code,
            $account_id,
            $transaction_type,
            $debit_amount,
            $credit_amount,
            $transaction_date,
            $reference,
            $status,
            implode(',', $issues)
        ]);
    }
}

