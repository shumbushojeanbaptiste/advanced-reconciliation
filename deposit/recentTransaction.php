<?php 
require_once("../include/initialize.php");

// Fetch recent deposits
function recent() {
    global $db; // ensure $db is accessible

    $tenantRef = $_POST['keyword1'] ?? '';

    // Fetch last 5 deposits
    $depositsStmt = $db->prepare("
        SELECT tbl_bank.bank_name,deposit_id,tbl_bank.bank_id, dep_amount, deposited_from, due_to, recorded_by, recorded_at, status, description,tbl_deposit.ten_id
        FROM tbl_deposit
        left join tbl_bank on tbl_bank.bank_id = tbl_deposit.bank_id
       
        WHERE ten_id = :ten_id
        ORDER BY recorded_at DESC
        LIMIT 100
    ");
    $depositsStmt->execute([':ten_id' => $tenantRef]);
    $lastDeposits = $depositsStmt->fetchAll(PDO::FETCH_ASSOC);


  $stmt = $db->prepare("
    SELECT COALESCE(rent_balance, 0) AS rent_balance
    FROM tbl_tenants
    WHERE ten_id = :ten_id
");
$stmt->execute([':ten_id' => $tenantRef]);
$balance = $stmt->fetchColumn();
?>

<!-- Recent Deposits -->
<div class="card-header">
    <h5 class="card-title mb-0">Recent Deposits  # <?php echo number_format($balance); ?> <span class="fe fe-arrow-right" onClick="recent_deposit(<?php echo $tenantRef ?>)"></span> </h5>
</div>


    <?php if(!empty($lastDeposits)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Done At</th>
                        <th> Account</th>
                        <th>Description</th>
                        <th>Deposited On</th>
                        <th class="text-end">Amount (Frw)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                          $limit=$balance;
                    
                    foreach($lastDeposits as $dep): 
                      
                        
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            
                            <td><?php echo htmlspecialchars(date("d M Y", strtotime($dep['recorded_at']))); ?></td>
                             <td><b><?php echo htmlspecialchars($dep['bank_name']); ?></b></td>
                            <td><?php echo htmlspecialchars($dep['description']); ?></td>
                            <td><?php echo htmlspecialchars($dep['due_to']); ?></td>
                            <td class="text-end"><?php echo number_format($dep['dep_amount'], 2); ?></td>


<td>
<span id="deposit_id<?php echo $dep['deposit_id'];  ?>"> 
<?php if ($limit >= $dep['dep_amount']): ?>
<span class="fe fe-trash text-danger" title="Below limit — can delete" onClick="cancelDeposit(<?php echo $dep['deposit_id']  ?>,<?php echo $dep['ten_id']?>,<?php echo $dep['dep_amount']?>)"></span>
<?php else: ?>
<span class="fe fe-check text-success" title="Approved deposit"></span>
<?php endif; ?>
</span>
</td>

                            <?php   $limit=$limit-$dep['dep_amount']; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">No deposits found.</p>
    <?php endif; ?>

<?php 
}

// Fetch tenant balance
function internalBalance($ten_id) {
    global $db;

    $tenantRef = $ten_id ?? '';
    
    // Example: calculate general balance from deposits and payments
     $stmt = $db->prepare("
        SELECT COALESCE(rent_balance, 0) AS rent_balance
        FROM tbl_tenants
        WHERE ten_id = :ten_id
    ");
    $stmt->execute([':ten_id' => $tenantRef]);

    $balance =  $rentBalance = $stmt->fetchColumn();
    echo $balance;
}


function balance() {
    global $db;

    $tenantRef = $_POST['keyword1'] ?? '';
    
    // Example: calculate general balance from deposits and payments
     $stmt = $db->prepare("
        SELECT COALESCE(rent_balance, 0) AS rent_balance
        FROM tbl_tenants
        WHERE ten_id = :ten_id
    ");
    $stmt->execute([':ten_id' => $tenantRef]);

    $balance =  $rentBalance = $stmt->fetchColumn();
    echo number_format($balance);
}

function saveDeposit() {
    global $db; // PDO connection

    // Collect POST data
    $ten_id = $_POST['ten_id'] ?? null;
    $bank_id = $_POST['bank_id'] ?? null;
    $deposit_amount = $_POST['deposit_amount'] ?? 0;
    $bank_slip = $_POST['bank_slip'] ?? '';
    $deposit_date = $_POST['deposit_date'] ?? date('Y-m-d');
    $deposit_desc = $_POST['deposit_desc'] ?? '';
    $deposited_from = $_POST['deposited_from'] ?? '';
    $recorded_by = $_POST['recorded_by'] ?? '';

    // Basic validation
    if (empty($recorded_by)) {
        echo json_encode(['status' => 0, 'message' => 'Unknown depositor, please logout and sign in again']);
        exit;
    }

    if (empty($ten_id) || $deposit_amount <= 0 || empty($bank_id)) {
        echo json_encode(['status' => 0, 'message' => 'Invalid deposit details']);
        exit;
    }

    try {
        // Start transaction
        $db->beginTransaction();

        // 1. Insert into tbl_deposit
        $stmt = $db->prepare("INSERT INTO tbl_deposit 
            (ten_id, dep_amount, bank_id, deposited_from, due_to, recorded_by, recorded_at, status, description, ref_code) 
            VALUES 
            (:ten_id, :dep_amount, :bank_id, :deposited_from, :due_to, :recorded_by, NOW(), :status, :description, :ref_code)");

        // Generate a unique reference code
        $ref_code = 'TxT' . date('YmdHis') . rand(100, 999);

        $stmt->execute([
            ':ten_id' => $ten_id,
            ':dep_amount' => $deposit_amount,
            ':bank_id' => $bank_id,
            ':deposited_from' => $deposited_from,
            ':due_to' => $deposit_date, 
            ':recorded_by' => $recorded_by,
            ':status' => 1,
            ':description' => $deposit_desc,
            ':ref_code' => $ref_code
        ]);

        // 2. Update tenant rent balance
        $stmt2 = $db->prepare("UPDATE tbl_tenants 
            SET rent_balance = rent_balance + :dep_amount 
            WHERE ten_id = :ten_id");

        $stmt2->execute([
            ':dep_amount' => $deposit_amount,
            ':ten_id' => $ten_id
        ]);


      $stmt20 = $db->prepare("
    SELECT ten_phone, te_names 
    FROM tbl_tenants 
    WHERE ten_id = :ten_id 
    LIMIT 1
");
$stmt20->execute([':ten_id' => $ten_id]);
$tenPhone = $stmt20->fetch(PDO::FETCH_ASSOC);

if ($tenPhone) {
    $names = $tenPhone['te_names'];
    $phone = $tenPhone['ten_phone'];
    $cp_name_char = "ITECPAY"; // ✅ fixed variable name (was cp_name_cha)

    // Compose message
    $message = "Dear $names, "
             . "This is to confirm that your payment of " . number_format($deposit_amount, 2) 
             . " has been successfully received on our account. "
             . "Reference Code: $ref_code. "
             . "We appreciate your prompt payment and continued trust in our services.";

    // Prepare SMS payload
    $data = [
        "sender" => $cp_name_char,
        "recipients" => $phone,
        "message" => $message,
    ];

    // IntouchSMS API credentials
    $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
    $username = "twagiramungus";
    $password = "M00dle!!@@";

    // Send SMS via cURL
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_USERPWD => "$username:$password",
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_POSTFIELDS => http_build_query($data),
    ]);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "cURL error: " . curl_error($ch);
    }

    curl_close($ch);

    // Optional: decode response for logging
    $response = json_decode($result, true);
    // You can log or verify $response['success'] if provided by the API
}


        // Commit transaction
        $db->commit();

        echo json_encode(['status' => 1, 'message' => 'Deposit saved successfully', 'ref_code' => $ref_code, 'amount' => number_format($deposit_amount)." "]);
         
      
    } catch (PDOException $e) {
        // Rollback on error
        $db->rollBack();
        error_log("Deposit Error: " . $e->getMessage());
        echo json_encode(['status' => 0, 'message' => 'Failed to save deposit: ' . $e->getMessage()]);
    }
}

function removeDeposit() {
    global $db;

    $ten_id = $_POST['ten_id'] ?? null;
    $deposit_id = $_POST['deposit_id'] ?? null;
    $amount = floatval($_POST['amount'] ?? 0);

    // Basic validation
    if (empty($deposit_id) || empty($ten_id) || $amount <= 0) {
        echo json_encode([
            'status' => 0,
            'message' => 'Invalid deposit details provided. Please refresh and try again.'
        ]);
        exit;
    }

    try {
        // Begin transaction
        $db->beginTransaction();

        // 1️⃣ Check if deposit exists
        $stmt = $db->prepare("SELECT dep_amount FROM tbl_deposit WHERE deposit_id = :deposit_id AND ten_id = :ten_id LIMIT 1");
        $stmt->execute([':deposit_id' => $deposit_id, ':ten_id' => $ten_id]);
        $deposit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$deposit) {
            $db->rollBack();
            echo json_encode(['status' => 0, 'message' => 'Deposit not found or already removed.']);
            exit;
        }

        $refundAmount = floatval($deposit['dep_amount']);

        // 2️⃣ Update tenant balance (refund the amount)
        $updateBalance = $db->prepare("
            UPDATE tbl_tenants 
            SET rent_balance = rent_balance - :refundAmount
            WHERE ten_id = :ten_id
        ");
        $updateBalance->execute([':refundAmount' => $refundAmount, ':ten_id' => $ten_id]);

        // 3️⃣ Delete the deposit record
        $deleteDeposit = $db->prepare("DELETE FROM tbl_deposit WHERE deposit_id = :deposit_id");
        $deleteDeposit->execute([':deposit_id' => $deposit_id]);

        // Commit transaction
        $db->commit();

        echo json_encode([
            'status' => 1,
            'message' => 'Deposit removed successfully and amount refunded to balance.',
            'refunded_amount' => number_format($refundAmount, 2)
        ]);
        exit;

    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode([
            'status' => 0,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
        exit;
    }
}
function invoices(){
   
    global $db; // ensure $db is accessible

    $tenantRef = $_POST['keyword1'] ?? '';

    $stmstmtTent = $db->prepare("
    SELECT ten_ref_no
    FROM tbl_tenants
    WHERE ten_id = :ten_id limit 1
");
 $stmstmtTent->execute([':ten_id' => $tenantRef]);
 $ten_ref = $stmstmtTent->fetchColumn();


    $invoicesStmt = $db->prepare("
    SELECT 
        r.room_no,
        i.invoice_id,
        i.invoice,
        i.invoice_fees,
        i.inv_desc,
        i.invoice_date,
        i.inv_room_id,
        i.inv_date,
        i.done_by,
        i.paid,

        -- Calculate total payments for this invoice
        COALESCE(SUM(p.pay_amount), 0) AS total_paid,

        -- Calculate remaining balance (invoice total - total payments)
        (i.invoice_fees - COALESCE(SUM(p.pay_amount), 0)) AS remaining_balance

    FROM tbl_invoice AS i
    LEFT JOIN tbl_rooms AS r ON r.room_id = i.inv_room_id
    LEFT JOIN tbl_payment AS p 
        ON p.invoice_id = i.invoice_id
        AND p.pay_ref_no = i.inv_ref_no
        AND p.invoice_date = i.invoice_date

    WHERE i.inv_ref_no = :inv_ref_no 
      AND i.invoice_fees != 0

    GROUP BY 
        i.invoice_id, r.room_no, i.invoice, i.invoice_fees, 
        i.inv_desc, i.invoice_date, i.inv_room_id, 
        i.inv_date, i.done_by, i.paid

    ORDER BY i.invoice_id asc
    LIMIT 100
");
$invoicesStmt->execute([':inv_ref_no' => $ten_ref]);
$lastInvoices = $invoicesStmt->fetchAll(PDO::FETCH_ASSOC);



  $stmt = $db->prepare("
    SELECT COALESCE(rent_balance, 0) AS rent_balance
    FROM tbl_tenants
    WHERE ten_id = :ten_id 
");
$stmt->execute([':ten_id' => $tenantRef]);
$balance = $stmt->fetchColumn();
?>

<!-- Recent Deposits -->
<div class="card-header">
    <h5 class="card-title mb-0">Recent Invoice  # <?php echo number_format($balance); ?> <span class="fe fe-arrow-right" onClick="recent_invoice(<?php echo $tenantRef ?>)"></span> </h5>
</div>


    <?php if(!empty($lastInvoices)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Monthly</th>
                        
                         <th>Shops</th>
                        <th>Description</th>
                        <th>Generated On</th>
                        <th class="text-end">Amount (Frw)</th>
                        <th>Remain (Frw)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                          $limit=$balance;
                    
                    foreach($lastInvoices as $dep): 
                       
                        
                        ?>
                        <tr >
                            <td><?php echo $i++; ?></td>
                            
                            <td><b><?php echo htmlspecialchars(date("F Y", strtotime($dep['invoice_date']))); ?></b></td>
                             <td><b><?php echo htmlspecialchars($dep['room_no']); ?></b></td>
                            <td><?php echo htmlspecialchars($dep['inv_desc']); ?></td>
                            
                            <td><?php echo htmlspecialchars($dep['inv_date']); ?></td>
                            <td class="text-end"><?php echo number_format($dep['invoice_fees'], 2); ?></td>
                            <td><b><?php echo "".number_format($dep['remaining_balance'],2); ?></b></td>

                            
  

<td>
<span id="invoice_id<?php echo $dep['invoice_id'];  ?>"> 
<?php if ($limit > 0 && $dep['remaining_balance'] > 0): ?>
      
<span class="fe fe-plus fe-20 text-danger" title="Below limit — can delete"  data-toggle="modal" data-target="#addexampleModalCenter" onClick="payInvo(<?php echo $dep['invoice_id']  ?>,<?php echo $tenantRef?>,<?php echo $dep['remaining_balance']?>,'<?php echo $dep['invoice_date']?>',<?php echo $limit ?>,'<?php echo $ten_ref ?>','<?= $dep['inv_room_id'] ?>')"></span>
<?php   $limit=$limit-$dep['remaining_balance']; ?>
<?php else: 
    if($dep['remaining_balance'] ==0){?>
<span class="fe fe-check text-success" title="Approved deposit"></span>
<?php 
}
endif; ?>
</span>
</td>

                          
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">No deposits found.</p>
    <?php endif; ?>

<?php  

}

function savePayment() {
    global $db; // PDO connection

    $ten_id = $_POST['ten_id'] ?? null;
    $invo_id = $_POST['invo_id'] ?? null;
    $pay_amount = floatval($_POST['pay_amount'] ?? 0);
    $invo_date = $_POST['invo_date'] ?? null;
    $pay_desc = $_POST['pay_desc'] ?? '';
    $recorded_by = $_POST['recorded_by'] ?? '';
    $pay_mode = $_POST['pay_mode'] ?? 1;
    $pay_ref_no = $_POST['ten_ref'] ?? null;
    $pay_due_date= $_POST['pay_due_date'] ?? date("Y-m-d");
    $room_id = $_POST['room_id'];

    // Basic validation
    if (empty($recorded_by)) {
        echo json_encode(['status' => 0, 'message' => 'Unknown depositor, please logout and sign in again']);
        exit;
    }

    if (empty($ten_id) || $pay_amount <= 0 || empty($invo_id)) {
        echo json_encode(['status' => 0, 'message' => 'Payment Amount must be greater than Zero']);
        exit;
    }

    try {
        // Start transaction
        $db->beginTransaction();

        // 1️⃣ Fetch tenant balance
        $stmt = $db->prepare("SELECT rent_balance FROM tbl_tenants WHERE ten_id = :ten_id FOR UPDATE");
        $stmt->execute([':ten_id' => $ten_id]);
        $tenant = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tenant) {
            throw new Exception('Tenant not found');
        }

        $current_balance = floatval($tenant['rent_balance']);

        // 2️⃣ Validate balance
        if ($pay_amount > $current_balance) {
            $db->rollBack();
            echo json_encode([
                'status' => 0,
                'message' => 'Payment amount exceeds available balance. Current balance: ' . number_format($current_balance, 2)
            ]);
            exit;
        }

        // 3️⃣ Insert into tbl_payment
        $insert = $db->prepare("
            INSERT INTO tbl_payment 
            (trans_code, pay_ref_no, invoice_date, pay_amount, pay_description, pay_user, pay_mode, rec_by, invoice_id, pay_due_date,pay_room_id,status)
            VALUES 
            (:trans_code, :pay_ref_no, :invoice_date, :pay_amount, :pay_description, :pay_user, :pay_mode, :rec_by, :invoice_id,:pay_due_date,:pay_room_id, 1)
        ");

        $trans_code = uniqid('TXN');

        $insert->execute([
            ':trans_code' => $trans_code,
            ':pay_ref_no' => $pay_ref_no,
            ':invoice_date' => $invo_date,
            ':pay_amount' => $pay_amount,
            ':pay_description' => $pay_desc,
            ':pay_user' => $recorded_by,
            ':pay_mode' => $pay_mode,
            ':rec_by' => $recorded_by,
            ':invoice_id' => $invo_id,
            ':pay_due_date' => $pay_due_date,
            ':pay_room_id' => $room_id
        ]);

        // 4 Update tenant balance
        $update = $db->prepare("
            UPDATE tbl_tenants 
            SET rent_balance = rent_balance - :amount 
            WHERE ten_id = :ten_id
        ");
        $update->execute([
            ':amount' => $pay_amount, 
            ':ten_id' => $ten_id
        ]);

        // 5️⃣ Commit transaction
        $db->commit();

      
 echo json_encode([
    'status' => 1,
    'message' => 'Payment recorded successfully',
    'transaction' => $trans_code . ' #' . number_format($pay_amount, 2)
]);


    } catch (Exception $e) {
        // Rollback on failure
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        echo json_encode([
            'status' => 0,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

function removePayment(){
  global $db;

    $ten_id = $_POST['ten_id'] ?? null;
    $pay_id = $_POST['pay_id'] ?? null;
    $amount = floatval($_POST['amount'] ?? 0);

    // Basic validation
    if (empty($pay_id) || empty($ten_id) || $amount <= 0) {
        echo json_encode([
            'status' => 0,
            'message' => 'Invalid deposit details provided. Please refresh and try again.'
        ]);
        exit;
    }

    try {
        // Begin transaction
        $db->beginTransaction();

        // 1️⃣ Check if deposit exists
        $stmt = $db->prepare("SELECT pay_amount FROM tbl_payment WHERE pay_id = :pay_id ");
        $stmt->execute([':pay_id' => $pay_id]);
        $paystsm = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$paystsm) {
            $db->rollBack();
            echo json_encode(['status' => 0, 'message' => 'Payment not found or already removed.']);
            exit;
        }

        $refundAmount = floatval($paystsm['pay_amount']);

        // 2️⃣ Update tenant balance (refund the amount)
        $updateBalance = $db->prepare("
            UPDATE tbl_tenants 
            SET rent_balance = rent_balance + :refundAmount
            WHERE ten_id = :ten_id
        ");
        $updateBalance->execute([':refundAmount' => $refundAmount, ':ten_id' => $ten_id]);

        // 3️⃣ Delete the deposit record
        $deleteDeposit = $db->prepare("DELETE FROM tbl_payment WHERE pay_id = :pay_id");
        $deleteDeposit->execute([':pay_id' => $pay_id]);

        // Commit transaction
        $db->commit();

        echo json_encode([
            'status' => 1,
            'message' => 'Payment removed successfully and amount refunded to balance.',
            'refunded_amount' => number_format($refundAmount, 2)
        ]);
        exit;

    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode([
            'status' => 0,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
        exit;
    }
  

}

function recentPayment(){

     global $db; // ensure $db is accessible

    $tenantRef = $_POST['keyword1'] ?? '';

    $stmstmtTent = $db->prepare("
    SELECT ten_ref_no
    FROM tbl_tenants
    WHERE ten_id = :ten_id limit 1
");
 $stmstmtTent->execute([':ten_id' => $tenantRef]);
 $ten_ref = $stmstmtTent->fetchColumn();

 // Fetch last 2 payments
$paymentsStmt = $db->prepare("
    SELECT pay_room_id,room_no,pay_id,pay_ref_no,trans_code	, pay_amount, pay_due_date, pay_description,invoice_date
    FROM tbl_payment AS p
    LEFT JOIN tbl_rooms AS r ON r.room_id = p.pay_room_id
    WHERE pay_ref_no = :tenantRef 
    ORDER BY pay_id DESC 
    
");
$paymentsStmt->execute([':tenantRef' => $ten_ref]);
$lastPayments = $paymentsStmt->fetchAll(PDO::FETCH_ASSOC);

?>

            <div class="card-header " onClick=recent_payment(<?= $tenantRef ?>)>
                <h5 class="card-title mb-0">Recent Invoice Payment Transactions </h5>
                <i class="fe fe-arrow-right"></i>
            </div>
      

  

 
   
    <?php if(!empty($lastPayments)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th> TransactionID</th>
                        <th>Paid Invoice</th>
                         <th>Shop</th>
                       
                        <th>Paid on</th>
                        <th>Description</th>
                        <th class="text-end">Amount </th>
                       <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $j = 1; foreach($lastPayments as $pay): ?>
                        <tr>
                            <td><?php echo $j++; ?></td>	
                             <td><?php echo htmlspecialchars(($pay['trans_code'])); ?></td>
                            <td><?php echo htmlspecialchars(date("M Y", strtotime($pay['invoice_date']))); ?></td>
                            <td><b><?php echo htmlspecialchars(($pay['room_no'])); ?></b></td>
                             <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($pay['pay_due_date']))); ?></td>
                              <td><?php echo htmlspecialchars(($pay['pay_description'])); ?></td>
                            <td class="text-end">
                               <?php echo number_format($pay['pay_amount'], 2); ?>
                            </td>
                            <span id="pay_id<?php echo $dep['deposit_id'];  ?>"> 
                             <td><span class="fe fe-trash text-warning" title="Approved paid" onClick="cancelPay(<?= $pay['pay_id']  ?>,<?= $tenant_id ?>,<?= $pay['pay_amount']?>,<?= $pay['pay_ref_no']?>)"></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">No payments found.</p>
    <?php endif; ?>

    <hr />
   

<?php

}

function depositAndPayment(){
     global $db; // ensure $db is accessible

    $tenantRef = $_POST['keyword1'] ?? '';
    $ten_id =$tenantRef;
  //ten reference
    $stmstmtTent = $db->prepare("
    SELECT ten_ref_no
    FROM tbl_tenants
    WHERE ten_id = :ten_id limit 1
");
 $stmstmtTent->execute([':ten_id' => $tenantRef]);
 $ten_ref = $stmstmtTent->fetchColumn();
    // Fetch last 5 deposits
$query = "
    SELECT 
        'Deposit' AS record_type,
        d.deposit_id AS record_id,
        b.bank_name,
        b.bank_id,
        d.dep_amount AS amount,
        d.deposited_from AS source,
        d.due_to AS destination,
        d.recorded_by,
        d.recorded_at AS record_date,
        d.status,
        d.description,
        d.ten_id,
        d.ref_code AS transaction_code
    FROM tbl_deposit AS d
    LEFT JOIN tbl_bank AS b ON b.bank_id = d.bank_id
    WHERE d.ten_id = :ten_id

    UNION ALL

    SELECT 
        'Payment' AS record_type,
        p.pay_id AS record_id,
        r.room_no AS bank_name, -- just for naming consistency
        NULL AS bank_id,
        p.pay_amount AS amount,
        p.trans_code AS source,
        p.pay_ref_no AS destination,
        NULL AS recorded_by,
        p.pay_trm AS record_date,
        NULL AS status,
        p.pay_description AS description,
        NULL AS ten_id,
        p.trans_code AS transaction_code

    FROM tbl_payment AS p
    LEFT JOIN tbl_rooms AS r ON r.room_id = p.pay_room_id
    WHERE p.pay_ref_no = :tenantRef

    ORDER BY record_date ASC
";

 $stmt = $db->prepare($query);
$stmt->execute([
    ':ten_id' => $ten_id,
    ':tenantRef' => $ten_ref
]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);


   


  $stmt = $db->prepare("
    SELECT COALESCE(rent_balance, 0) AS rent_balance
    FROM tbl_tenants
    WHERE ten_id = :ten_id
");
$stmt->execute([':ten_id' => $tenantRef]);
$balance = $stmt->fetchColumn();
?>

<!-- Recent Deposits -->
<div class="card-header">
    <h5 class="card-title mb-0">Deposit Vs Payment Transaction   # <?php echo number_format($balance); ?> <span class="fe fe-arrow-right" onClick="recent_deposit(<?php echo $tenantRef ?>)"></span> </h5>
</div>


    <?php if(!empty($records)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>#Ref Code#</th>
                        <th> Action Type </th>
                        <th>Done At</th>
                        <th>Account</th>
                      
                       
                        <th class="text-end">Deposit (Frw)</th>
                         <th class="text-end">Invoice Paid (Frw)</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                          $balance=0;;
                    
                    foreach($records as $dep): 
                      if($dep['record_type']=="Payment"){
                        $payment = $dep['amount'];
                        $deposit = 0;
                      }
                    else if($dep['record_type']=="Deposit"){
                        $payment = 0;
                        $deposit = $dep['amount'];
                      }
                       $balance= $balance + ($deposit -  $payment);
                      
                        
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                             <td><b><?php echo "#".htmlspecialchars($dep['transaction_code']); ?></b></td>
                             <td><?php echo htmlspecialchars($dep['record_type']); ?></td>
                            <td><?php echo htmlspecialchars(date("d/m/Y H:i:s", strtotime($dep['record_date']))); ?></td>
                            <td><b><?php echo htmlspecialchars($dep['bank_name']); ?></b></td>
                            
                           
                            <td class="text-success"><?php echo number_format($deposit, 2); ?></td>
                            <td class="text-danger"><?php echo number_format( $payment, 2); ?></td>

                            <td ><b><?php echo number_format( $balance, 2); ?></b></td>

                           
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">No deposits found.</p>
    <?php endif; ?>

<?php 

}

function invoiceVsPayment(){
        global $db; // ensure $db is accessible

    $tenantRef = $_POST['keyword1'] ?? '';
    $ten_id =$tenantRef;
  //ten reference
    $stmstmtTent = $db->prepare("
    SELECT ten_ref_no
    FROM tbl_tenants
    WHERE ten_id = :ten_id limit 1
");
 $stmstmtTent->execute([':ten_id' => $tenantRef]);
 $ten_ref = $stmstmtTent->fetchColumn();
    // Fetch last 5 deposits
$query = "

        SELECT 
        'Invoice' AS record_type,
        i.invoice_id AS record_id,
        r.room_no AS bank_name,
        NULL AS bank_id,
        i.invoice_fees AS amount,
        i.invoice AS source,
        i.inv_ref_no AS destination,
        i.done_by AS recorded_by,
        i.inv_date AS record_date,
        i.paid AS status,
        i.inv_desc AS description,
        NULL AS ten_id,
        i.inv_ref_no AS transaction_code
    FROM tbl_invoice AS i
    LEFT JOIN tbl_rooms AS r ON r.room_id = i.inv_room_id
    WHERE i.inv_ref_no = :tenantRef

     UNION ALL

    SELECT 
        'Payment' AS record_type,
        p.pay_id AS record_id,
        r.room_no AS bank_name,
        NULL AS bank_id,
        p.pay_amount AS amount,
        p.trans_code AS source,
        p.pay_ref_no AS destination,
        NULL AS recorded_by,
        p.pay_trm AS record_date,
        NULL AS status,
        p.pay_description AS description,
        NULL AS ten_id,
        p.trans_code AS transaction_code
    FROM tbl_payment AS p
    LEFT JOIN tbl_rooms AS r ON r.room_id = p.pay_room_id
    WHERE p.pay_ref_no = :tenantRef

   


    ORDER BY record_date ASC
";


 $stmt = $db->prepare($query);
$stmt->execute([
    
    ':tenantRef' => $ten_ref
]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);


   


  $stmt = $db->prepare("
    SELECT COALESCE(rent_balance, 0) AS rent_balance
    FROM tbl_tenants
    WHERE ten_id = :ten_id
");
$stmt->execute([':ten_id' => $tenantRef]);
$balance = $stmt->fetchColumn();
?>

<!-- Recent Deposits -->
<div class="card-header">
    <h5 class="card-title mb-0">Invoice Vs Payment Transaction   # <?php echo number_format($balance); ?> <span class="fe fe-arrow-right" onClick="recent_deposit(<?php echo $tenantRef ?>)"></span> </h5>
</div>


    <?php if(!empty($records)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>#Ref Code#</th>
                        <th> Action Type </th>
                        <th>Done At</th>
                        <th>Shops</th>
                      
                       
                        <th class="text-end">Deposit (Frw)</th>
                         <th class="text-end">Invoice Paid (Frw)</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                          $balance=0;;
                    
                    foreach($records as $dep): 
                      if($dep['record_type']=="Payment"){
                        $payment = $dep['amount'];
                        $deposit = 0;
                      }
                    else if($dep['record_type']=="Invoice"){
                        $payment = 0;
                        $deposit = $dep['amount'];
                      }
                       $balance= $balance + ($deposit -  $payment);
                      
                        
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                             <td><b><?php echo "#".htmlspecialchars($dep['transaction_code']); ?></b></td>
                             <td><?php echo htmlspecialchars($dep['record_type']); ?></td>
                            <td><?php echo htmlspecialchars(date("d/m/Y H:i:s", strtotime($dep['record_date']))); ?></td>
                            <td><b><?php echo htmlspecialchars($dep['bank_name']); ?></b></td>
                            
                           
                            <td class="text-success"><?php echo number_format($deposit, 2); ?></td>
                            <td class="text-danger"><?php echo number_format( $payment, 2); ?></td>

                            <td ><b><?php echo number_format( $balance, 2); ?></b></td>

                           
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">No deposits found.</p>
    <?php endif; ?>

<?php 

}

function monthDeposit(){

  global $db; // ensure $db is accessible

    $month = $_POST['month'] ?? '';

    // Fetch last 5 deposits
    $depositsStmt = $db->prepare("
        SELECT te_names,tbl_bank.bank_name,ref_code,deposit_id,tbl_bank.bank_id, dep_amount, deposited_from, due_to, recorded_by, recorded_at, status, description,tbl_deposit.ten_id
        FROM tbl_deposit
        left join tbl_bank on tbl_bank.bank_id = tbl_deposit.bank_id
        left join tbl_tenants on tbl_tenants.ten_id = tbl_deposit.ten_id
       
        WHERE date_format(due_to,'%Y-%m') = :month
        ORDER BY recorded_at DESC
        
    ");
    $depositsStmt->execute([':month' => $month]);
    $lastDeposits = $depositsStmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!-- Recent Deposits -->
<div class="card-header">
    <h5 class="card-title mb-0"><b><?= htmlspecialchars(date("F Y", strtotime($month))) ?></b> Deposits  # <span class="fe fe-arrow-right" ></span> </h5>
</div>


    <?php if(!empty($lastDeposits)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th> TransactionID</th>
                        <th> Client(s)</th>
                        
                        <th> Bank</th>
                       
                        <th>Deposited On</th>
                        <th class="text-end">Amount (Frw)</th>
                       <th>Done At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                          $limit=0;
                          $total_deposit=0;
                    
                    foreach($lastDeposits as $dep): 
                      
                        $total_deposit=$total_deposit+$dep['dep_amount'];
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            
                             <td><?php echo htmlspecialchars($dep['ref_code']); ?></td>
                            
                            <td><?php echo htmlspecialchars($dep['te_names']); ?></td>
                             <td><b><?php echo htmlspecialchars($dep['bank_name']); ?></b></td>
                           
                            <td><?php echo htmlspecialchars($dep['due_to']); ?></td>
                            <td class="text-end"><?php echo number_format($dep['dep_amount'], 2); ?></td>
                             <td><?php echo htmlspecialchars(date("d/m/Y H:i:s", strtotime($dep['recorded_at']))); ?></td>



                          
                        </tr>
                    <?php endforeach; ?>

                     <tr>
                            <td colspan="5" class="text-center">Total </td>
                            
                            
                            <td class="text-end"><?php echo number_format($total_deposit, 2)." Frw"; ?></td>
                             <td></td>



                          
                        </tr>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No deposits found.</p>
    <?php endif; ?>

<?php 

}

 function monthInvoice(){
    global $db; // ensure $db is accessible

    $month = $_POST['month'] ?? '';



    $invoicesStmt = $db->prepare("
    SELECT 
        r.room_no,
        t.te_names,
        i.invoice_id,
        i.invoice,
        i.invoice_fees,
        i.inv_desc,
        i.invoice_date,
        i.inv_room_id,
        i.inv_date,
        i.done_by,
        i.paid,

        -- Calculate total payments for this invoice
        COALESCE(SUM(p.pay_amount), 0) AS total_paid,

        -- Calculate remaining balance (invoice total - total payments)
        (i.invoice_fees - COALESCE(SUM(p.pay_amount), 0)) AS remaining_balance

    FROM tbl_invoice AS i
    LEFT JOIN tbl_rooms AS r ON r.room_id = i.inv_room_id
    LEFT JOIN tbl_tenants AS t ON t.ten_ref_no = i.inv_ref_no
    LEFT JOIN tbl_payment AS p 
        ON p.invoice_id = i.invoice_id
        AND p.pay_ref_no = i.inv_ref_no
        AND p.invoice_date = i.invoice_date

    WHERE date_format(i.invoice_date,'%Y-%m') = :invoice_date 
      

    GROUP BY 
        i.invoice_id, r.room_no, i.invoice, i.invoice_fees, 
        i.inv_desc, i.invoice_date, i.inv_room_id, 
        i.inv_date, i.done_by, i.paid

    ORDER BY i.inv_room_id,i.invoice_id asc
    
");
$invoicesStmt->execute([':invoice_date' => $month]);
$lastInvoices = $invoicesStmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!-- Recent Deposits -->
<div class="card-header">
    <h5 class="card-title mb-0"><b><?= htmlspecialchars(date("F Y", strtotime($month))) ?></b> Invoices   <span class="fe fe-arrow-right" onClick="recent_invoice(<?php echo $tenantRef ?>)"></span> </h5>
</div>


    <?php if(!empty($lastInvoices)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                      
                         <th>Shops</th>
                        <th>Clients</th>
                          <th>Generated On</th>
                        
                        
                        <th>Invo Amount (Frw)</th>
                      
                        <th class="text-end">Paid Amount (Frw)</th>
                        <th>Remain (Frw)</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                          $limit=0;
                          $total_invo=0;
                           $total_remain=0;
                    
                    foreach($lastInvoices as $dep): 
                       
                        $total_invo=$total_invo+$dep['invoice_fees'];
                         $total_remain= $total_remain+ $dep['remaining_balance'];
                        ?>
                        <tr >
                            <td><?php echo $i++; ?></td>
                           
                             <td><b><?php echo htmlspecialchars($dep['room_no']); ?></b></td>
                              <td><?php echo htmlspecialchars($dep['te_names']); ?></td>
                            
                            <td><b><?php echo htmlspecialchars(date("d/m/Y H:i:s", strtotime($dep['inv_date']))); ?></b></td>
                            
                           <td class="text-end"><?php echo number_format($dep['invoice_fees'], 2); ?></td>
                            <td class="text-end"><?php echo number_format($dep['total_paid'], 2); ?></td>
                            <td><b><?php echo "".number_format($dep['remaining_balance'],2); ?></b></td>

                            
  

<td>

</td>

                          
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-center">Total</td>
                            <td><?php echo number_format($total_invo,2); ?></td>
                            <td></td>
                             <td><b><?php echo number_format($total_remain,2); ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No invoice found.</p>
    <?php endif; ?>

<?php  

 }

 function paidInvoice(){
       global $db; // ensure $db is accessible

    $month = $_POST['month'] ?? '';



$paymentsStmt = $db->prepare("
    SELECT 
        p.pay_id,
        p.trans_code,
        p.pay_ref_no,
        
       
        p.pay_due_date,
        p.rent_date,
        p.invoice_date,
        p.pay_amount,
        
        p.pay_trm,
   
      
      
       
       
      
        p.rec_by,
        p.pay_room_id AS pay_room_id,
     
       
        p.invoice_id,
        r.room_no,
        t.te_names
    
      
     
    FROM tbl_payment AS p
    
    LEFT JOIN tbl_rooms AS r 
        ON p.pay_room_id = r.room_id
    LEFT JOIN tbl_tenants AS t 
        ON p.pay_ref_no = t.ten_ref_no
    WHERE DATE_FORMAT(p.invoice_date, '%Y-%m') = :invoice_date
    GROUP BY 
        p.pay_id, r.room_no, t.te_names, p.pay_amount, 
        p.invoice_date, p.pay_description, p.pay_mode
    ORDER BY p.room_id, p.pay_id ASC
");

$paymentsStmt->execute([':invoice_date' => $month]);
$lastPayments = $paymentsStmt->fetchAll(PDO::FETCH_ASSOC);




?>

<!-- Recent Deposits -->
<div class="card-header">
    <h5 class="card-title mb-0"><b>Paid Invoice For <?= htmlspecialchars(date("F Y", strtotime($month))) ?></b>   <span class="fe fe-arrow-right" onClick="recent_invoice(<?php echo $tenantRef ?>)"></span> </h5>
</div>


    <?php if(!empty($lastPayments)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>#receipt code</th>
                         <th>Shops</th>
                        <th>Clients</th>
                         
                        
                        
                      
                      
                        <th class="text-end">Amount (Frw)</th>
                        <th>Done at</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                          $limit=0;
                          $total_invo=0;
                           $total_remain=0;
                    
                    foreach($lastPayments as $dep): 
                       
                        $total_invo=$total_invo+$dep['pay_amount'];
                        
                        ?>
                        <tr >
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($dep['trans_code']); ?></td>
                             <td><b><?php echo htmlspecialchars($dep['room_no']); ?></b></td>
                              <td><?php echo htmlspecialchars($dep['te_names']); ?></td>
                            
                           
                            
                           
                            <td class="text-end"><?php echo number_format($dep['pay_amount'], 2); ?></td>
                          <td><b><?php echo htmlspecialchars(date("d/m/Y H:i:s", strtotime($dep['pay_trm']))); ?></b></td>

                            
  

<td>

</td>

                          
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-center">Total</td>
                            <td><?php echo number_format($total_invo,2); ?></td>
                             <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No invoice found.</p>
    <?php endif; ?>

<?php  

 }

 function unAssign(){
    global $db;

    $room_id = $_POST['room_id'] ?? null;


    // Basic validation
    if (empty($room_id)) {
        echo json_encode([
            'status' => 0,
            'message' => 'Invalid deposit details provided. Please refresh and try again.'
        ]);
        exit;
    }

    try {
        // Begin transaction
        $db->beginTransaction();

        

        // 2️⃣ Update tenant balance (refund the amount)
        $updateAssign = $db->prepare("
            UPDATE tbl_assign
            SET assgn_status = 0
            WHERE room_id = :room_id
        ");
        $updateAssign->execute([':room_id' => $room_id]);

        // 3️⃣ Delete the deposit record
        $roomUpdate = $db->prepare("
        UPDATE  tbl_rooms 
        SET availability = 3 
        WHERE room_id = :room_id");
        $roomUpdate->execute([':room_id' => $room_id]);

        // Commit transaction
        $db->commit();

        echo json_encode([
            'status' => 1,
            'message' => 'Shop romoved successfully.'
        ]);
        exit;

    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode([
            'status' => 0,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
        exit;
    }

 }

 function advanceInvoice(){
    
    global $db; // ensure $db is accessible

   $ten_id = $_POST['keyword1'] ?? '';
$month = $_POST['month'] ?? '';

// Get tenant ref number
$stmstmtTent = $db->prepare("
    SELECT ten_ref_no 
    FROM tbl_tenants
    WHERE ten_id = :ten_id LIMIT 1
");
$stmstmtTent->execute([':ten_id' => $ten_id]);
$ten_ref = $stmstmtTent->fetchColumn();

$shopStmt = $db->prepare("
SELECT
r.room_id,
r.room_no, 
r.r_floor, 
r.price, 
r.room_area,
r.availability,
a.assgn_status

    FROM tbl_assign a
    INNER JOIN tbl_rooms r ON r.room_id = a.room_id
    WHERE a.tent_id = :tenantId
    
");
$shopStmt->execute([':tenantId' => $ten_id]);
$result = $shopStmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!-- Recent Deposits -->



    <?php if(!empty($result)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Monthly <?= $month."#".$ten_ref ?></th>
                        
                         <th>Shops</th>
                       
                        <th class="text-end">Amount | Price (Frw)</th>
                        
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; 
                          $limit=0;
                    
                    foreach($result as $data): 
                       $room_id=$data['room_id'];
                       $room_no=$data['room_no'];
                       $totaInvoStmt = $db->prepare("
   SELECT sum(i.invoice_fees) as amount FROM  tbl_invoice i 
    WHERE i.inv_room_id = :inv_room_id AND DATE_FORMAT(i.invoice_date,'%Y-%m') = :month and auto= 'yes' ");
$totaInvoStmt->execute([':inv_room_id' => $data['room_id'],':month' => $month]);
$totalRoomInvo = $totaInvoStmt->fetchColumn();

 $status=1;
if($totalRoomInvo == 0 and  $totalRoomInvo ==0){
    $totalRoomInvo=$data['price'];
    if($totalRoomInvo != 0){
    $status=0;
    }
    

    $textcolor='';
}
else {
 $status=1;
  $textcolor='';  
}
                        
                        
                        ?>
                        <tr class="<?= $textcolor ?>">
                            <td><?php echo $i++; ?></td>
                            
                            <td><b><?php echo htmlspecialchars(date("F Y", strtotime($month))); ?></b></td>
                             <td><b><?php echo htmlspecialchars($room_no); ?></b></td>
                           
                            
                            
                            <td class="text-end"><?php echo number_format($totalRoomInvo, 2); ?></td>
                            
                            
  

<td>
<?php if ($status==1){ ?>
<span class="fe fe-check text-success" title="Approved deposit"></span>
<?php } else {
    ?>
    <spann id="spinner<?= $room_id ?>"></span>
<span class="fe fe-plus text-warning" style="cursor: pointer" title="Generate invoice"  onClick="saveInvo('<?= $ten_id ?>',<?= $totalRoomInvo ?>,'<?= $month ?>','<?= $ten_ref  ?>','<?= $room_id ?>')"></span>
   
   <?php

}

?>
</td>

                          
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">No data found.</p>
    <?php endif; ?>

<?php  


 }

function generateInvoice() {
    global $db; 
    global $USER_ID;

    // 1. Collect Inputs
    $ten_id    = $_POST['ten_id'] ?? null;
    $month     = $_POST['month'] ?? null;   // Expect YYYY-MM
    $amount    = floatval($_POST['amount'] ?? 0);
    $ten_ref   = $_POST['ten_ref'] ?? null;
    $room_id   = $_POST['room_id'] ?? null;
    $invo_desc = $_POST['invo_desc'] ?? ('Generated on ' . date("Y-m-d"));

    // Company
    $cpny_ID = 9;
    $date_time = date("Y-m-d H:i:s");

    // Generate invoice number
    $invoice_code ="IXN". date("YmdHis")."#".$ten_id;
    $digits=5;
$rnd = rand(pow(10, $digits-1), pow(10, $digits)-1); 
$reference=$rnd.$ten_ref;


    // Build real server URL
   $server_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' 
    ? "https://" 
    : "http://") . $_SERVER['SERVER_NAME'];


    // Link text (invoice link)
    $invoice_link = $server_url . "/xode-invoice?code={$reference}&month={$month}";

    // Validation
    if (!$USER_ID) {
        echo json_encode(['status' => 0, 'message' => 'Unknown depositor, please login again']);
        exit;
    }

    try {
        $db->beginTransaction();

        // 2. Insert Invoice
        $inv_sql = "
            INSERT INTO tbl_invoice 
            (invoice, invoice_fees, unit_value, inv_desc, invoice_date, 
             inv_ref_no, inv_room_id, cpny_ID, auto, inv_date, inv_message, done_by)
            VALUES
            (:invoice, :fees, :unit_value, :desc, :inv_date,
             :ref, :room_id, :cpny, 'yes', :date_time, '1', :done_by)
        ";

        $stmt_inv = $db->prepare($inv_sql);
        $stmt_inv->execute([
            ':invoice'    => $invoice_code,
            ':fees'       => $amount,
            ':unit_value' => 1,
            ':desc'       => $invo_desc,
            ':inv_date'   => $month . "-01", // Convert YYYY-MM to YYYY-MM-01
            ':ref'        => $ten_ref,
            ':room_id'    => $room_id,
            ':cpny'       => $cpny_ID,
            ':date_time'  => $date_time,
            ':done_by'    => $USER_ID
        ]);

        // 3. Insert Invoice Link
        $link_sql = "
            INSERT INTO tbl_invo_link 
            (li_date, link_text, ten_ref, receiced_phone, invo_type, co_id, sms)
            VALUES
            (:date, :link_text, :ref, :phone, 1, :co_id, :sms)
        ";

        $stmt_link = $db->prepare($link_sql);
        $stmt_link->execute([
            ':date'       => date("Y-m-d"),
            ':link_text'  => $invoice_link,
            ':ref'        => $ten_ref,
            ':phone'      => $_POST['phone'] ?? '', // optional
            ':co_id'      => $cpny_ID,
            ':sms'        => $invo_desc
        ]);

        $db->commit();

        echo json_encode([
            'status' => 1,
            'message' => 'Invoice generated successfully',
            'invoice_code' => $invoice_code,
            'invoice_link' => $invoice_link
        ]);

    } catch (Exception $e) {
        if ($db->inTransaction()) $db->rollBack();

        echo json_encode([
            'status' => 0,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
function generateAutoInvoice() {
    
    global $db;
    global $USER_ID;
    global $smsusername;
    global $smspwd;
    $month = $_POST['month'];
     
    try{
    $db->beginTransaction();
    $ACCNO = "4075200001050";
    $server_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? "https://" : "http://") . $_SERVER['SERVER_NAME'];

    $generated = [];   // Return all invoices created

    // 1. Fetch all active tenants with rooms
    $q = $db->query("
        SELECT a.assgn_id, a.room_id, a.tent_id, a.company_id,
               t.ten_ref_no, t.te_names, t.ten_phone, t.rent_balance,
               r.price AS room_price, t.rent_status
        FROM tbl_assign a
        JOIN tbl_tenants t ON a.tent_id = t.ten_id
        JOIN tbl_rooms r ON a.room_id = r.room_id
        WHERE a.assgn_status = 1  limit 30
    ");

    while ($row = $q->fetch(PDO::FETCH_ASSOC)) {

        $tenant_id = $row['tent_id'];
        $room_id   = $row['room_id'];
        $phone     = $row['ten_phone'];
        $tenantRef = $row['ten_ref_no'];
        $tenantName= $row['te_names'];
        $rent_status= $row['rent_status'];
        $invp_description = " Rent invoive for ".$month;
        $invoice_code ="IXN". date("YmdHis")."#".$tenant_id;

      $sql = $db->prepare("
    SELECT 
        COALESCE(SUM(invoice_fees), 0) AS total_invoice,
        (SELECT COALESCE(SUM(pay_amount), 0) 
         FROM tbl_payment 
         WHERE pay_ref_no = ?) AS total_payment
    FROM tbl_invoice
    WHERE inv_ref_no = ?
");
$sql->execute([$tenantRef, $tenantRef]);
$data = $sql->fetch(PDO::FETCH_ASSOC);

$umwenda = $data['total_invoice'] - $data['total_payment'];


        // 2. Get contract
        $ctr = $db->prepare("SELECT * FROM tbl_ten_contracts WHERE tenant_id=? AND cntr_status=1 LIMIT 1");
        $ctr->execute([$tenant_id]);
        $contract = $ctr->fetch(PDO::FETCH_ASSOC);

        $m_interval = $contract ? $contract['m_interval'] : 1;

        // 3. Room contract price if exists
        $rc = $db->prepare("SELECT * FROM tbl_c_room WHERE room_id=? AND croom_status=1 LIMIT 1");
        $rc->execute([$room_id]);
        $room_contract = $rc->fetch(PDO::FETCH_ASSOC);

        $price = $room_contract ? $row['room_price'] : $row['room_price'];
        $total_invoiced=$price*$m_interval;

        // 4. Create invoice months list
        $months = [];
        $start_month = new DateTime($month . "-01");

        for ($i=0; $i < $m_interval; $i++) {
            $m = clone $start_month;
            $m->modify("+$i month");
            $months[] = $m->format("Y-m");
        }

        $total_invoice_amount = 0;

        foreach ($months as $invM) {

            $months=$invM ."-01";
            // 5. Check if invoice already exists
            $chk = $db->prepare("SELECT 1 FROM tbl_invoice WHERE inv_ref_no=? AND inv_room_id=? AND date_format(invoice_date,'%Y-%m')=? LIMIT 1");
            $chk->execute([$tenantRef, $room_id, $invM]);
            if ($chk->rowCount() > 0) continue;

            // 6. Insert invoice
            $stmt = $db->prepare("
                INSERT INTO tbl_invoice (invoice, invoice_fees, inv_desc, inv_ref_no, inv_room_id, auto, inv_date, done_by,invoice_date)
                VALUES (?,?,?,?,?,'yes',NOW(),?,?)
            ");
            $stmt->execute([$invoice_code, $price, $invp_description, $tenantRef, $room_id, $USER_ID,$months]);

            $total_invoice_amount += $price;

            // store return
            $generated[] = [
                "tenant" => $tenantName,
                "invoice_month" => $invM,
                "room_id" => $room_id,
                "amount" => $price
            ];
        }

        // 7. Skip SMS if no invoice created for this tenant
        if ($total_invoice_amount == 0) continue;

        $unpaid_before = $umwenda;
        $total_due = $total_invoice_amount + $unpaid_before;

        // 8. Generate invoice link
          $digits=5;
        $rnd = rand(pow(10, $digits-1), pow(10, $digits)-1); 
        $reference=$rnd.$tenantRef;
        $invoice_link = $server_url . "/xode-invoice?code=$reference&month=$month";

        // 9. Create SMS message
        $msg = "Mukiriya wacu $tenantName, CHIC Ltd irakwibutsa kwishyura ubukode bungana $total_invoice_amount Frw ando unpaid balance is $unpaid_before Frw, total :$total_due Frw  Acc No $ACCNO. details on : $invoice_link";

        // 10. Save SMS log
        $log = $db->prepare("
            INSERT INTO tbl_invo_link (li_date, link_text, ten_ref, receiced_phone, invo_type, sms)
            VALUES (CURDATE(), ?, ?, ?, 1, ?)
        ");
        $log->execute([$invoice_link, $tenantRef, $phone, $msg]);

        // 11. Send SMS
        // --- same CURL code you already have ---

        if($total_due > $row['rent_balance'] and $rent_status == 0){
              $headersms="ITECPAY";
            $data=array(
                      "sender"=>"$headersms",
                        "recipients"=>"$phone",
                        "message"=>"$msg",
                );
        
            $url="https://www.intouchsms.co.rw/api/sendsms/.json";
            $data=http_build_query($data);
            $username=$smsusername;
            $password=$smspwd;
        
            $ch=curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_USERPWD,$username.":".$password);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
            $result=curl_exec($ch);
            $httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);   
            curl_close($ch);
        }
    }

 $db->commit();
 echo json_encode([
    "status" =>1,
    "message" => "Auto invoice generated",
    "total_invoices" => count($generated),   // <-- Add this
    "invoices" => $generated
]);


    }
    catch (Exception $e) {
        if ($db->inTransaction()) $db->rollBack();

        echo json_encode([
            'status' => 0,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }

}






// Handle AJAX action
$action = $_POST['action'] ?? '';

switch($action) {
    case 'recent':
        recent();
        break;
    case 'balance':
        balance();
        break;
    case 'save-deposit':
        saveDeposit();
        break;
    case 'remove-deposit':
        removeDeposit();
        break;
    case 'invoices':
        invoices();
        break;
    case 'save-payment':
        savePayment();
        break;
    case 'remove-payment':
        removePayment();
        break;
    case 'recent-payment':
        recentPayment();
        break;
    case 'deposit-and-payment':
        depositAndPayment();
        break;
    case 'invoice-and-payment':
        invoiceVsPayment();
        break;
    case 'deposit-list':
        monthDeposit();
        break;
    case 'invoice-list':
        monthInvoice();
        break;
    case 'paid-invoice-list';
        paidInvoice();
        break;
    case 'un-assign-shop':
        unAssign();
         break;
    case 'custom-invo':
        advanceInvoice();
         break;
    case 'generate-invo':
        generateInvoice();
        break;
    case 'save-all-invoice':
        generateAutoInvoice();
        break;
        
    default:
        echo 'Invalid action';
        break;
}
?>


