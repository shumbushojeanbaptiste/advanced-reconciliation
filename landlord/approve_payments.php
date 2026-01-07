<?php
require_once("../include/initialize.php");

header("Content-Type: application/json");

// Get JSON data from AJAX request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['approve_all']) || $data['approve_all'] !== true) {
    echo json_encode(["error" => "Invalid request."]);
    exit;
}
$prefix='CHIC';
$code=$prefix."-".date("YmdHis")."-".strtoupper(substr(md5(uniqid(mt_rand(), true)),0,6));echo $code." (Length: ".strlen($code).")";

 

// Update all pending payments
$stmt = $db->prepare("UPDATE tbl_payment SET status='1',trans_code='$code' WHERE status='2'");
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Fetch the updated payment details
    $sqluser1 = $db->prepare("
        SELECT SUM(pay_amount) AS pay_amount, te_names, ten_ref_no 
        FROM tbl_payment p 
        INNER JOIN tbl_tenants t ON p.pay_ref_no = t.ten_ref_no 
        WHERE p.status = 1 
        GROUP BY p.pay_ref_no
    ");
    $sqluser1->execute();
    $rowuser1 = $sqluser1->fetch();

    if ($rowuser1) {
        $names = $rowuser1['te_names'];
        $cp_name_char = 'ITECPAY';
        $payment_fees2 = $rowuser1['pay_amount'];
        $mbz = ' RWF';
        $company_number = '0733919903';
       $phone12=$rowuser1['ten_ref_no'];

        $msg = "Mukiriya wacu Mwiza $names CHIC irabamenyesha ko amafaranga angana $payment_fees2 $mbz yakiriwe kuri Konti yayo. Murakoze ";

        $smsData = [
            "sender" => $cp_name_char,
            "recipients" => $phone12,
            "message" => $msg,
        ];

        $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
        $username = "twagiramungus";
        $password = "M00dle!!@@";

        // Initialize cURL request 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($smsData));
        
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }

    echo json_encode(["success" => "All pending payments successfully approved!"]);
} else {
    echo json_encode(["error" => "No pending payments found."]);
}
?>

?>
