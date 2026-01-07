<?php
// Ensure database connection ($db) is defined before using it
 require_once ("../include/initialize.php");
$payment_id = $_POST['pay_id']; // Corrected from $_fetch
$ten_ref = $_POST['tenant_ref'];
$bank = $_POST['bank'];
$slip_no = $_POST['slip_no'];
$payment_fees = $_POST['payment_fees'];
$payment_fees2 = $_POST['payment_fees'];
$payment_desc = $_POST['comment'];
$date = $_POST['date'];
$dateooo=$date;
$roomid=$_POST['roomid1'];
$date1=$date;
$todaysdate = date("Y-m-d H:i:s");
$feesAMT = $payment_fees;
$chicFees = $_POST['payment_fees'];
$checkadv=0;
$checknormal=0;
$totalLoanAmount = $_POST['blncename'];
$check = 0; // Initialize check
echo $roomid;
// $data = [
//     'transid' => null,
//     'assign' => null,
//     'amount' => null,
//     'mode' => null
// ];

// $cash = [
//     'payment_fees' => isset($_POST['payment_fees']) ? $_POST['payment_fees'] : 0,
// ];
// $total = $_POST['payment_fees'];
// $remainingAmount = 0;
//     // Fetch transactions for the client
//     $sqltrnas = $conn->prepare("SELECT * FROM tbl_invoice WHERE inv_ref_no = :inv_ref_no order and inv_room_id='' by serve_id asc");
//     $sqltrnas->bindParam(':inv_ref_no', $ten_ref, PDO::PARAM_STR);
//     $sqltrnas->execute();
//     while ($rowtrans = $sqltrnas->fetch(PDO::FETCH_ASSOC)) {
      
       
//         $transid = $rowtrans['invoice_id'];
//         $transDate = $rowtrans['invoice_date'];
//         $transamount = $rowtrans['invoice_fees'];
//         $roomid = $rowtrans['inv_room_id'];
//         $tenant = $rowtrans['inv_ref_no'];
//         $trans_code = $rowtrans['invoice'];
//         $serve_id=$rowtrans['serve_id'];
//         $amountTrans = 0;

//               $yearMonth = date("Y-m", strtotime($transDate));

//       // Get the total paid amount for each transaction
//         $sqlpaid = $conn->prepare("SELECT sum(pay_amount) as pay_amount FROM  tbl_payment where pay_ref_no='$ten_ref' and pay_room_id='$roomid' and invoice_date like '$yearMonth%' and trans_code='$trans_code' and status=1 ");
//         $sqlpaid->execute();
//         while ($rowpaid = $sqlpaid->fetch()) { // Use associative array fetching
//             $paidAmount = $rowpaid['pay_amount'];
//             $amountTrans += $paidAmount;
            
//         }
        

//         // Calculate remaining amount
//         $remainingAmount = $transamount - $amountTrans;

//         if ($remainingAmount > 0) {
            
//             $cashContributions = [];
//           // echo "weeee";

//             // Process cash to cover remaining amount
//             foreach ($cash as $cash_id => &$cash_amount) {
                  
//                 if ($cash_amount > 0 && $remainingAmount > 0) {
//                     if ($remainingAmount >= $cash_amount) {
//                         // Use the full cash amount
//                         $cashContributions[] = [
//                             'mode' => $bank,
//                             'amount' => $cash_amount,
//                             'remainingAmount' => $remainingAmount,
//                             'transid' => $transid,
//                             'transDate' => $transDate,
//                             'trans_code' => $trans_code,
//                             'roomid' => $roomid,
//                             'serve_id'=>$serve_id,
//                             'tenant' => $tenant
//                         ];
//                         $remainingAmount -= $cash_amount;
//                         $cash_amount = 0; // Cash is exhausted
//                     } else {
//                         // Use part of the cash amount
//                         $cashContributions[] = [
//                             'mode' => $bank,
//                             'amount' => $remainingAmount,
//                             'remainingAmount' => 0,
//                             'transid' => $transid,
//                             'transDate' => $transDate,
//                             'trans_code' => $trans_code,
//                             'roomid' => $roomid,
//                              'serve_id'=>$serve_id,
//                             'tenant' => $tenant
//                         ];
//                         $cash_amount -= $remainingAmount;
//                         $remainingAmount = 0; // Fully covered the transaction
//                     }
//                 }

//                 // Stop processing if the remaining amount is zero
//                 if ($remainingAmount <= 0) {
//                     break;
//                 }
//             }

//             // Insert payments into the database
//             foreach ($cashContributions as $contribution) {
//             //  echo  $contribution['roomid']." ".$contribution['remainingAmount']."  <br>";
//             //normal payment
//              $company=1;
//             $remain=$contribution['amount'];
//             $transid=$contribution['transid'];
//             $new_pay_id=$contribution['trans_code'];
//             $inv_date=$contribution['transDate'];
//             $roomid=$contribution['roomid'];
//              $roomidd=$contribution['roomid'];
//             $ten_ref=$contribution['tenant'];
//             $company=1;
//             $converted=1;
//             $serveid=$contribution['serve_id'];
//           // echo $amounnt."hhjhjh";
//         //   $sqlpyt = $conn->prepare("INSERT INTO `tbl_payment_live`(`trans_code`, `pay_ref_no`, `bank_id`, `slip_no`,
//         //     `pay_due_date`, `rent_date`, `pay_amount`, `pay_description`,
//         //     `pay_trm`, `pay_user`, `status`, `c_value`,
//         //     `pay_mode`, `rec_by`, `pay_room_id`, `serve_id`)
//         //     values('$trans_code','$tenant','$bank','$slip_no',
//         //     '$date','$transDate','$amount','$payment_desc',
//         //     '$todaysdate','$user',1,1,
//         //     '$bank','$todaysdate','$roomid',4)");
//         //     $sqlpyt->execute();
        
//         $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by,pay_room_id, serve_id,invoice_date)
//              VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$dateooo.'","'.$remain.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_date.'","'.$converted.'","'.$_SESSION['USER_ID'].'","'.$roomidd.'","'.$serveid.'","'.$inv_date.'")';
//              $conn->exec($sql);
//             // $lastId = $conn->lastInsertId(); 
//             if($sql){
//               $checknormal=1;
//             }
            
//             }
//         }
//     }











// // Ensure database connection ($db) is defined before using it
//  require_once ("../include/initialize.php");
//  //check if there is pending item
// $sqlslippending = $conn->prepare("SELECT * FROM  tbl_payment where status='2' ");
// $sqlslippending->execute();
// $countslippending=$sqlslippending->rowCount();
// if($countslippending==0){
// $payment_id = $_POST['pay_id']; // Corrected from $_fetch
// $ten_ref = $_POST['tenant_ref'];
// $bank = $_POST['bank'];
// $slip_no = $_POST['slip_no'];
// $payment_fees = $_POST['payment_fees'];
// $payment_fees2 = $_POST['payment_fees'];
// $payment_desc = $_POST['comment'];
// $date = $_POST['date'];
// $dateooo=$date;
// $date1=$date;
// $todaysdate = date("Y-m-d H:i:s");
// $feesAMT = $payment_fees;
// $chicFees = $_POST['payment_fees'];
// $checkadv=0;
// $checknormal=0;
// $totalLoanAmount = $_POST['blncename']; // Ensure this is defined or fetched from DB
// //check slip number
// $sqlslip = $conn->prepare("SELECT * FROM  tbl_payment where slip_no='$slip_no' ");
// $sqlslip->execute();
// $countslip=$sqlslip->rowCount();
// $countslip1=0;
// if($countslip1==0){
    

// $check = 0; // Initialize check
// $data = [
//     'transid' => null,
//     'assign' => null,
//     'amount' => null,
//     'mode' => null
// ];

// $cash = [
//     'payment_fees' => isset($_POST['payment_fees']) ? $_POST['payment_fees'] : 0,
// ];
// $total = $_POST['payment_fees'];
// $remainingAmount = 0;
//     // Fetch transactions for the client
//     $sqltrnas = $conn->prepare("SELECT * FROM tbl_invoice WHERE inv_ref_no = :inv_ref_no order by serve_id asc");
//     $sqltrnas->bindParam(':inv_ref_no', $ten_ref, PDO::PARAM_STR);
//     $sqltrnas->execute();
     

//     while ($rowtrans = $sqltrnas->fetch(PDO::FETCH_ASSOC)) {
      
       
//         $transid = $rowtrans['invoice_id'];
//         $transDate = $rowtrans['invoice_date'];
//         $transamount = $rowtrans['invoice_fees'];
//         $roomid = $rowtrans['inv_room_id'];
//         $tenant = $rowtrans['inv_ref_no'];
//         $trans_code = $rowtrans['invoice'];
//         $serve_id=$rowtrans['serve_id'];
//         $amountTrans = 0;

//               $yearMonth = date("Y-m", strtotime($transDate));

//       // Get the total paid amount for each transaction
//         $sqlpaid = $conn->prepare("SELECT sum(pay_amount) as pay_amount FROM  tbl_payment where pay_ref_no='$ten_ref' and pay_room_id='$roomid' and invoice_date like '$yearMonth%' and trans_code='$trans_code' and status=1 ");
//         $sqlpaid->execute();
//         while ($rowpaid = $sqlpaid->fetch()) { // Use associative array fetching
//             $paidAmount = $rowpaid['pay_amount'];
//             $amountTrans += $paidAmount;
            
//         }
        

//         // Calculate remaining amount
//         $remainingAmount = $transamount - $amountTrans;

//         if ($remainingAmount > 0) {
            
//             $cashContributions = [];
//           // echo "weeee";

//             // Process cash to cover remaining amount
//             foreach ($cash as $cash_id => &$cash_amount) {
                  
//                 if ($cash_amount > 0 && $remainingAmount > 0) {
//                     if ($remainingAmount >= $cash_amount) {
//                         // Use the full cash amount
//                         $cashContributions[] = [
//                             'mode' => $bank,
//                             'amount' => $cash_amount,
//                             'remainingAmount' => $remainingAmount,
//                             'transid' => $transid,
//                             'transDate' => $transDate,
//                             'trans_code' => $trans_code,
//                             'roomid' => $roomid,
//                             'serve_id'=>$serve_id,
//                             'tenant' => $tenant
//                         ];
//                         $remainingAmount -= $cash_amount;
//                         $cash_amount = 0; // Cash is exhausted
//                     } else {
//                         // Use part of the cash amount
//                         $cashContributions[] = [
//                             'mode' => $bank,
//                             'amount' => $remainingAmount,
//                             'remainingAmount' => 0,
//                             'transid' => $transid,
//                             'transDate' => $transDate,
//                             'trans_code' => $trans_code,
//                             'roomid' => $roomid,
//                              'serve_id'=>$serve_id,
//                             'tenant' => $tenant
//                         ];
//                         $cash_amount -= $remainingAmount;
//                         $remainingAmount = 0; // Fully covered the transaction
//                     }
//                 }

//                 // Stop processing if the remaining amount is zero
//                 if ($remainingAmount <= 0) {
//                     break;
//                 }
//             }

//             // Insert payments into the database
//             foreach ($cashContributions as $contribution) {
//             //  echo  $contribution['roomid']." ".$contribution['remainingAmount']."  <br>";
//             //normal payment
//              $company=1;
//             $remain=$contribution['amount'];
//             $transid=$contribution['transid'];
//             $new_pay_id=$contribution['trans_code'];
//             $inv_date=$contribution['transDate'];
//             $roomid=$contribution['roomid'];
//              $roomidd=$contribution['roomid'];
//             $ten_ref=$contribution['tenant'];
//             $company=1;
//             $converted=1;
//             $serveid=$contribution['serve_id'];
//           // echo $amounnt."hhjhjh";
//         //   $sqlpyt = $conn->prepare("INSERT INTO `tbl_payment_live`(`trans_code`, `pay_ref_no`, `bank_id`, `slip_no`,
//         //     `pay_due_date`, `rent_date`, `pay_amount`, `pay_description`,
//         //     `pay_trm`, `pay_user`, `status`, `c_value`,
//         //     `pay_mode`, `rec_by`, `pay_room_id`, `serve_id`)
//         //     values('$trans_code','$tenant','$bank','$slip_no',
//         //     '$date','$transDate','$amount','$payment_desc',
//         //     '$todaysdate','$user',1,1,
//         //     '$bank','$todaysdate','$roomid',4)");
//         //     $sqlpyt->execute();
        
//         $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by,pay_room_id, serve_id,invoice_date)
//              VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$dateooo.'","'.$remain.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_date.'","'.$converted.'","'.$_SESSION['USER_ID'].'","'.$roomidd.'","'.$serveid.'","'.$inv_date.'")';
//              $conn->exec($sql);
//             // $lastId = $conn->lastInsertId(); 
//             if($sql){
//               $checknormal=1;
//             }
            
//             }
//         }
//     }
// // Calculate advance:
// if ($total < 0) {
//     $total = 0;
// }
// $advance = $total - $totalLoanAmount;

// if ($advance > 0) {
//     // Select tenant
//     $sqlten1 = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_ref_no = :ten_ref");
//     $sqlten1->bindParam(':ten_ref', $ten_ref, PDO::PARAM_STR);
//     $sqlten1->execute();
//     $rowten1 = $sqlten1->fetch();
//     $tenid = $rowten1['ten_id'];

//     // Select assigned rooms
//     $sqlten1room = $conn->prepare("SELECT * FROM `tbl_assign` WHERE tent_id = :tenid");
//     $sqlten1room->bindParam(':tenid', $tenid);
//     $sqlten1room->execute();

//     $rooms = [];

//     while ($rowten1room = $sqlten1room->fetch()) {
//         $amountroom = $rowten1room['amount'] - $rowten1room['discount'];
       
//         $rooms[] = [  // Store rooms as an array of room details
//             'id' => $rowten1room['room_id'],
//             'amount' => $amountroom
//         ];
//     }

//     // Get the last invoice
//     $sqltrnas1 = $conn->prepare("SELECT * FROM tbl_invoice WHERE inv_ref_no = :inv_ref_no AND serve_id = 4 ORDER BY invoice_id DESC LIMIT 1");
//     $sqltrnas1->bindParam(':inv_ref_no', $ten_ref, PDO::PARAM_STR);
//     $sqltrnas1->execute();
//     $lastinvoice = $sqltrnas1->fetch();
//     $lastmonth = $lastinvoice['invoice_date'];

//     // Initialize DateTime object and modify to the next month
//     $date = new DateTime($lastmonth);
//     $date->modify('+1 month');

//     // Initialize month and year
//     $month = $date->format('m');
//     $year = $date->format('Y');
//     $payments = [];
//     $securty=[];
//      do {
//         $initialAdvance = $advance; // Track advance before processing rooms
//         foreach ($rooms as $room){
//             $roomId = $room['id'];
//             $price = $room['amount'];
//             if ($advance >= $price) {
//                 // Full payment for the room
//                 $payments[] = [
//                     'room' => $roomId,
//                     'amount' => $price,
//                     'month' => $month,
//                     'year' => $year
//                 ];
//                 $advance -= $price;
//             } else if ($advance > 0) {
//                 // Partial payment for the room
//                 $payments[] = [
//                     'room' => $roomId,
//                     'amount' => $advance,
//                     'month' => $month,
//                     'year' => $year
//                 ];
//                 $advance = 0;  // Once the advance is used up, set it to 0
//             }
//         }
//         // Check if no progress was made to avoid infinite loop
//         if ($advance === $initialAdvance) {
//             break;
//         }
//         // Process security fee if advance is still available
//     if ($advance > 0 && isset($rowten1)) {
//         $tenref = $rowten1['ten_ref_no'];
//         $fee = $rowten1['fee_security'];

//         if ($advance >= $fee) {
//             $advance -= $fee;
//             $securty[] = [
//                 'tenid' => $tenref,
//                 'amount' => $fee,
//                 'month' => $month,
//                 'year' => $year
//             ];
//         } else {
//             // Deduct only available advance amount
//             $securty[] = [
//                 'tenid' => $tenref,
//                 'amount' => $advance,
//                 'month' => $month,
//                 'year' => $year
//             ];
//             $advance = 0;
//         }
//     }
        
//         // Move to the next month
//         $date->modify('+1 month');
//         $month = $date->format('m');
//         $year = $date->format('Y');
//     } while ($advance > 0);

//     // Save payments to the database (example):
//     foreach ($payments as $payment) {
//         $roomid1=$payment['room'];
//         $serveid1=4;
//         $roomAmount=$payment['amount'];
//         $dateinvo=$payment['year'].'-'.$payment['month'].'-01 00:00:00';
//         $dateinvo1=$payment['year'].'-'.$payment['month'].'-01';
//         //generate code
//           //Invoice No.
//         $year1 =  date('Y');
//         $year = substr( $year1, -2);
//         $li_qu = $db->prepare("SELECT * FROM tbl_invoice ORDER BY invoice_id DESC LIMIT 1");
//         $li_qu->execute();
//         $fli = $li_qu->fetch();
//         $li = str_pad($fli['invoice_id'], 6, "0", STR_PAD_LEFT);
//         $invoice = $year.'-'.$li;
//         #########################################
//         // Select tenant
//         $sqlten1 = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_ref_no = :ten_ref");
//         $sqlten1->bindParam(':ten_ref', $ten_ref, PDO::PARAM_STR);
//         $sqlten1->execute();
//         $rowten1 = $sqlten1->fetch();
//         $tenid1 = $rowten1['ten_id'];
//         $cmp_ID=1;
//         $unitValue=1;
//         $converted=1;
//         //room
//         $li_roomprice = $db->prepare("SELECT * FROM tbl_assign where room_id='$roomid1' and assgn_status=1 and tent_id='$tenid1'");
//         $li_roomprice->execute();
//         $fli = $li_roomprice->fetch();
//         $invoroomAmount=$fli['amount']-$fli['discount'];
//           $sql1 = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by,pay_room_id, serve_id,adv_sts,invoice_date)
//              VALUES ("'.$invoice.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$dateooo.'","'.$roomAmount.'","'.$payment_desc.'","'.$todaysdate.'","'.$cmp_ID.'","'.$dateinvo.'","'.$converted.'","'.$_SESSION['USER_ID'].'","'.$roomid1.'","'.$serveid1.'",1,"'.$dateinvo1.'")';
//              $conn->exec($sql1);
//             // $lastId = $conn->lastInsertId(); 
//             if($sql1){
//                 $checkadv=1; 
//             }
        
        
//         // $inv_insert_qu = $db->prepare("INSERT into tbl_invoice(`invoice`, `invoice_fees`, `inv_desc`, `invoice_date`, `inv_ref_no`, `cpny_ID`, `auto`,`inv_date`,`inv_message`,`unit_value`,`inv_room_id`,`done_by`,`adv_sts`)
//         //     VALUES ('".$invoice."', '".$invoroomAmount."', 'rent', '$dateinvo1', '".$ten_ref."', '$cmp_ID', 'yes', '$todaysdate','1','".$unitValue."','".$roomid1."','".$_SESSION['USER_ID']."','1')");
//         //     $inv_insert_qu->execute();   
//         //     if($inv_insert_qu){
                
//         //         $sql1 = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by,pay_room_id, serve_id,adv_sts)
//         //      VALUES ("'.$invoice.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date1.'","'.$roomAmount.'","'.$payment_desc.'","'.$todaysdate.'","'.$cmp_ID.'","'.$dateinvo.'","'.$converted.'","'.$_SESSION['USER_ID'].'","'.$roomid1.'","'.$serveid1.'",1)';
//         //      $conn->exec($sql1);
//         //     // $lastId = $conn->lastInsertId(); 
//         //     if($sql1){
//         //         $checkadv=1; 
//         //     }  
            
//         //     }
        
        
//     }
// } else {
//   // echo "No advance";
// }

// if($checkadv==1 || $checknormal==1){
//     echo "Done";
    
// }
// }
// else{
//     echo "Exist";
// }
// foreach ($securty as $sec) {
//     // echo "  ".$sec['amount'];
//     $tenid1 = $sec['tenid'];
//     $amountt = $sec['amount'];
//     $month1 = $sec['month'];
//     $year = $sec['year'];
//     $dateinvot = $year . "-" . $month1 . "-01";
//     $serveid11 = '5';
//     $roomid1 = '0';


//     $sql11 = 'INSERT INTO tbl_payment 
//         (pay_ref_no, bank_id, slip_no, pay_due_date, pay_amount, pay_description, pay_trm, pay_user, rent_date, c_value, rec_by, pay_room_id, serve_id, adv_sts, invoice_date) 
//         VALUES 
//         ("' . $tenid1 . '", "' . $bank . '", "' . $slip_no . '", "' . $dateooo . '", "' . $amountt . '", "' . $payment_desc . '", "' . $todaysdate . '", "' . $cmp_ID . '", "' . $dateinvot . '", "' . $converted . '", "' . $_SESSION['USER_ID'] . '", "' . $roomid1 . '", "' . $serveid11 . '", 1, "' . $dateinvot . '")';

//     $conn->exec($sql11);

//     if ($sql11) {
//       // echo "pooooo";
//     }
// }
// }
// else{
//     echo "pending";
// }

?>
