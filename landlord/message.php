<?php
// ========================================================================
// ✅ 1. CONFIG & FUNCTIONS
// ========================================================================

// DB connection already exists: $conn, $db
// VARIABLES already exist: $cpny_ID, $smsusername, $smspwd

// ✅ SMS Sender Function
function sendSMS($sender, $phone, $message, $smsusername, $smspwd) {
    if (!$phone || strlen($phone) < 10) return false;

    // Fix problematic unicode quotes for InTouch API
    $message = str_replace(["’","‘"],"'", $message);
    $message = str_replace(["“","”"],'"', $message);

    $postData = http_build_query([
        "sender"     => $sender,
        "recipients" => $phone,
        "message"    => $message
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.intouchsms.co.rw/api/sendsms/.json");
    curl_setopt($ch, CURLOPT_USERPWD, "$smsusername:$smspwd");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $result = curl_exec($ch);
    curl_close($ch);

    return true;
}

// ✅ Tenant Balance Calculator
function getTenantBalance($conn, $ten_ref) {
    $inv = $conn->prepare("SELECT SUM(invoice_fees/unit_value) FROM tbl_invoice WHERE inv_ref_no = :r");
    $inv->execute([':r' => $ten_ref]);
    $totalInv = $inv->fetchColumn() ?: 0;

    $pay = $conn->prepare("SELECT SUM(pay_amount/c_value) FROM tbl_payment WHERE pay_ref_no = :r");
    $pay->execute([':r' => $ten_ref]);
    $totalPay = $pay->fetchColumn() ?: 0;

    return $totalInv - $totalPay;
}

// ========================================================================
// ✅ 2. COMPANY SETTINGS (Loaded Once)
// ========================================================================
$admin = $db->prepare("SELECT phone FROM tbl_admins WHERE company_id = :id");
$admin->execute([':id' => $cpny_ID]);
$company_number = $admin->fetchColumn();

$companyInfo = $db->prepare("SELECT msg_char, short_name, side FROM tbl_company WHERE cpny_ID = :id");
$companyInfo->execute([':id' => $cpny_ID]);
$c = $companyInfo->fetch();

$cp_name_char = $c['msg_char'];
$short_name   = $c['side'];
$short_name2  = $c['short_name'];


// ========================================================================
// ✅ 3. SEND TO ALL TENANTS
// ========================================================================
if (isset($_POST['sending_all_save'])) {

    $tenants = $conn->prepare("
        SELECT t.ten_id, t.ten_ref_no, t.te_names, t.ten_phone
        FROM tbl_tenants t
        INNER JOIN tbl_assign a ON a.tent_id = t.ten_id
        WHERE t.ten_staff = :c AND a.assgn_status = 1 AND t.rc = 0
        GROUP BY t.ten_id limit 3
    ");
    $tenants->execute([':c' => $cpny_ID]);

    while ($t = $tenants->fetch()) {

        $ten_ref = $t['ten_ref_no'];
        $Tname   = $t['te_names'];
        $phone   = $t['ten_phone'];

        $balance = getTenantBalance($conn, $ten_ref);
        if ($balance <= 0) continue;

        $bal = number_format($balance);

        // ✅ SMS FORMAT PER COMPANY
        if (in_array($cpny_ID, [6,7,8])) {
            $msg = "Mukiriya wacu $Tname, turakwibutsa ko Utubereyemo umwenda kuri $short_name ungana $bal Frw. Tel: $company_number";
        }
        elseif ($cpny_ID == 9) {
            $msg = "Mukiriya wacu, $short_name irakwibutsa kwishyura ubukode bungana $bal Frw kuri Code: $ten_ref. Tel: $company_number";
            $conn->prepare("UPDATE tbl_tenants SET rc=1 WHERE ten_ref_no=:r")->execute([':r'=>$ten_ref]);
        }
        elseif ($cpny_ID == 13) {
            $msg = "Mukiriya wacu $short_name2 irakwibutsa kwishyura ubukode bungana $bal Frw kuri Account 100080726265 (BK). Tel: $company_number";
        }
        else {
            $msg = "Mukiriya wacu $short_name2 irakwibutsa kwishyura ubukode bungana $bal Frw mukoresheje code $ten_ref. Tel: $company_number";
        }

        sendSMS($cp_name_char, $phone, $msg, $smsusername, $smspwd);
    }

    $msg = "Messages Successfully Sent to All Clients!";
}


// ========================================================================
// ✅ 4. SEND TO ONE TENANT (Popup Modal)
// ========================================================================
if (isset($_POST['send'])) {

    $ten_id = $_POST['tenant'];
    $message = $_POST['tenSMS'];

    $p = $conn->prepare("SELECT ten_phone FROM tbl_tenants WHERE ten_id=:id");
    $p->execute([':id'=>$ten_id]);
    $phone = $p->fetchColumn();

    if ($phone) {
        sendSMS($cp_name_char, $phone, $message, $smsusername, $smspwd);
        $msg = "Message Sent Successfully!";
    }
}

?>


<title>Send SMS</title>



</head>
<body>

<div class="container-fluid mt-4 card">

<?php if(!empty($msg)): ?>
<div class="alert alert-success"><strong><?= $msg; ?></strong></div>
<?php endif; ?>






<!-- ========================================================================
 ✅ 5. TENANTS TABLE + MODALS
========================================================================= -->
<div class="table-responsive">
<table class="table table-hover" style="font-size: 11px">
<thead>
<tr>
    <th>#</th>
    <th>Ref</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Balance</th>
    <th>SMS</th>
</tr>
</thead>

<tbody>

<?php
$i = 1;
$tenants = $conn->prepare("
    SELECT t.ten_id, t.ten_ref_no, t.te_names, t.ten_phone
    FROM tbl_tenants t
    INNER JOIN tbl_assign a ON a.tent_id = t.ten_id
    WHERE t.ten_staff = :c AND a.assgn_status = 1
    GROUP BY t.ten_id
");
$tenants->execute([':c'=>$cpny_ID]);

while ($t = $tenants->fetch()):
    $ten_id  = $t['ten_id'];
    $ten_ref = $t['ten_ref_no'];
    $name    = $t['te_names'];
    $phone   = $t['ten_phone'];

    $balance = getTenantBalance($conn, $ten_ref);
    $bal = number_format($balance);

    // SMS Preview Text
    if (in_array($cpny_ID, [6,7,8])) {
        $sms = "Mukiriya wacu $name, turakwibutsa ko Utubereyemo umwenda kuri $short_name ungana $bal Frw. Tel: $company_number";
    }
    elseif ($cpny_ID == 13) {
        $sms = "Mukiriya wacu $short_name2 irakwibutsa kwishyura ubukode bungana $bal Frw kuri Account 100080726265 muri BK. Tel: $company_number";
    }
    else {
        $sms = "Mukiriya wacu $short_name2 irakwibutsa kwishyura ubukode bungana $bal Frw mukoresheje code $ten_ref. Tel: $company_number";
    }
?>

<tr>
    <td><?= $i++; ?></td>
    <td><?= $ten_ref; ?></td>
    <td><?= $name; ?></td>
    <td><?= $phone; ?></td>
    <td><?= $bal; ?></td>
    <td>
      <?php if($bal>1){ ?>
        <a href="#" data-toggle="modal" data-target="#sms<?= $ten_id; ?>">
            <i class="fa fa-envelope"></i> 
        </a>
        <?php }else{
          ?>
           <i class="fa fa-check"></i> 
          <?php
        } ?>
    </td>
</tr>

<!-- ✅ MODAL -->
<div class="modal fade" id="sms<?= $ten_id; ?>">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
    <h5>SMS to <?= $name; ?></h5>
    <button class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
    <form method="POST">
        <input type="hidden" name="tenant" value="<?= $ten_id; ?>">
        <textarea class="form-control" rows="4" readonly><?= $sms; ?></textarea>
        <input type="hidden" name="tenSMS" value="<?= htmlspecialchars($sms, ENT_QUOTES); ?>">
        
        <button name="send" class="btn btn-warning mt-3">Send</button>
    </form>
</div>

</div>
</div>
</div>

<?php endwhile; ?>

</tbody>
</table>

<form method="POST">
    <button type="submit" name="sending_all_save" class="btn btn-primary mb-3 text-right">
        Send to ALL Tenants
    </button>
</form>
</div>
</div>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
