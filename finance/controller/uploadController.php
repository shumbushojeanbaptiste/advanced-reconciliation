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

    if(!isset($_FILES['file_name']) || $_FILES['file_name']['error'] != 0){
        echo json_encode(['success'=>false, 'message'=>'No file uploaded', 'progress'=>0]);
        return;
    }

    $file = $_FILES['file_name']['tmp_name'];

    if(($handle = fopen($file, "r")) === false){
        echo json_encode(['success'=>false, 'message'=>'Failed to open file', 'progress'=>0]);
        return;
    }

    $batchSize = 50; // number of rows per batch
    $rows = [];
    $total = 0;
    $inserted = 0;

    // Read CSV header
    $header = fgetcsv($handle, 1000, ","); // adjust delimiter if needed

    while(($data = fgetcsv($handle, 1000, ",")) !== false){
        $row = array_combine($header, $data); // map CSV header to values
        $rows[] = $row;
        $total++;

        // Insert/update in batch
        if(count($rows) >= $batchSize){
            processBatch($rows);
            $inserted += count($rows);
            $progress = round(($inserted / $total) * 100);
            echo json_encode(['success'=>true, 'message'=>"Processing...", 'progress'=>$progress]);
            $rows = [];
            ob_flush(); flush(); // send progress to client
        }
    }

    // Process remaining rows
    if(count($rows) > 0){
        processBatch($rows);
        $inserted += count($rows);
    }

    fclose($handle);

    echo json_encode(['success'=>true, 'message'=>"Upload complete! $inserted rows processed.", 'progress'=>100]);
}

// Process each batch
function processBatch($rows){
    global $db;

    foreach($rows as $row){
        $transaction_code = $row['transaction_code'];
        $transaction_type = $row['transaction_type'];
        $amount           = $row['amount'];
        $transaction_date = $row['transaction_date'];
        $reference        = $row['reference'];

        // check if amount is numeric
        if(!is_numeric($amount)){
            continue; // skip invalid row
        }
        // check if date is valid
        if(!strtotime($transaction_date)){
            continue; // skip invalid row
        }




        // Check if transaction_code exists
        $check = $db->prepare("SELECT transaction_id FROM tbl_bank_transaction WHERE transaction_code = ?");
        $check->execute([$transaction_code]);
        $exists = $check->fetch(PDO::FETCH_ASSOC);

        if($exists){
            // Update
            $stmt = $db->prepare("UPDATE tbl_bank_transaction SET transaction_type=?, amount=?, transaction_date=?, reference=? WHERE transaction_code=?");
            $stmt->execute([$transaction_type, $amount, $transaction_date, $reference, $transaction_code]);
        } else {
            // Insert
            $stmt = $db->prepare("INSERT INTO tbl_bank_transaction (transaction_code, transaction_type, amount, transaction_date, reference, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$transaction_code, $transaction_type, $amount, $transaction_date, $reference]);
        }
    }
}
