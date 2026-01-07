<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once ("../../include/initialize.php");


if ($_SERVER['REQUEST_METHOD'] == 'GET' && preg_match('/\/rooms/', $_SERVER['REQUEST_URI']) && isset($_GET['company_id'])) {
    $company_id=$_GET['company_id'];
    $sqlQuery="SELECT *FROM tbl_rooms WHERE company_id='$company_id'";
    $statement=$db->query($sqlQuery);
  
    $allFound=array();
      if ($statement->rowCount() != 0) {
             
            while($res = $statement->fetch()){
                $isOccupied=false;
                if($res["availability"]==4){
                    $isOccupied=true;
                }
               $allFound[]=array("id"=>$res["room_id"],"name"=>$res["room_no"],"isOccupied"=>$isOccupied); 
            }
      }
    
    echo json_encode($allFound);
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && preg_match('/\/invoices/', $_SERVER['REQUEST_URI'])&& isset($_GET['company_id'])) {
     $company_id=$_GET['company_id'];
    $sqlQuery="SELECT *FROM tbl_invoice WHERE cpny_ID='$company_id'";
    $statement=$db->query($sqlQuery);
  
    $allFound=array();
      if ($statement->rowCount() != 0) {
             
            while($res = $statement->fetch()){
                $date_str = $res["inv_date"];
                list($year, $month) = explode('-', $date_str);
                $days_in_month = date('t', strtotime("$year-$month-01"));
                $year_month = "$year-$month";
               $allFound[]=array("id"=>$res["invoice_id"],"amount"=>$res["invoice_fees"],"month"=>$year_month); 
            }
      }
    
    echo json_encode($allFound);
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && preg_match('/\/due_invoices/', $_SERVER['REQUEST_URI'])&& isset($_GET['date']) && isset($_GET['company_id'])) {
      $company_id=$_GET['company_id'];
  
    $due = $_GET['date'];
    $date_str = $due;
     list($year, $month) = explode('-', $date_str);
    $days_in_month = date('t', strtotime("$year-$month-01"));
    $year_month = "$year-$month";
  $year_month = trim($year_month, "\"' \t\n\r\0\x0B");
 
    $sqlQuery="SELECT * FROM tbl_invoice WHERE  cpny_ID='$company_id' AND `invoice_date` LIKE '%".$year_month."%'";
     //echo json_encode($sqlQuery);
    $statement=$db->query($sqlQuery);
  
    $allFound=array();
      if ($statement->rowCount() != 0) {
             
            while($res = $statement->fetch()){
        
               $allFound[]=array("id"=>$res["invoice_id"],"amount"=>$res["invoice_fees"],"month"=>$year_month); 
            }
      }
      
    echo json_encode($allFound);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && preg_match('/\/payments/', $_SERVER['REQUEST_URI']) && isset($_GET['company_id'])) {
     $company_id=$_GET['company_id'];
    $sqlQuery="select *from tbl_payment where tbl_payment.pay_ref_no in(SELECT tbl_invoice.inv_ref_no from tbl_invoice where tbl_invoice.inv_ref_no =tbl_payment.pay_ref_no and tbl_invoice.cpny_ID ='$company_id')";
     //echo json_encode($sqlQuery);
    $statement=$db->query($sqlQuery);
  
    $allFound=array();
      if ($statement->rowCount() != 0) {
             
            while($res = $statement->fetch()){
        
               $allFound[]=array("id"=>$res["invoice_id"],"amount"=>$res["invoice_fees"],"month"=>$year_month); 
            }
      }
      
    echo json_encode($allFound);
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && preg_match('/\/due_payments/', $_SERVER['REQUEST_URI'])&& isset($_GET['date']) && isset($_GET['company_id'])) {
    
      $company_id=$_GET['company_id'];
  
    $due = $_GET['date'];
    $date_str = $due;
     list($year, $month) = explode('-', $date_str);
    $days_in_month = date('t', strtotime("$year-$month-01"));
    $year_month = "$year-$month";
    $year_month = trim($year_month, "\"' \t\n\r\0\x0B");
    $sqlQuery="select *from tbl_payment where tbl_payment.pay_ref_no in(SELECT tbl_invoice.inv_ref_no from tbl_invoice where tbl_invoice.inv_ref_no =tbl_payment.pay_ref_no and tbl_invoice.cpny_ID ='$company_id') and tbl_payment.pay_due_date like '%".$year_month."%'";
     //echo json_encode($sqlQuery);
    $statement=$db->query($sqlQuery);
  
    $allFound=array();
      if ($statement->rowCount() != 0) {
             
            while($res = $statement->fetch()){
        
               $allFound[]=array("id"=>$res["invoice_id"],"amount"=>$res["invoice_fees"],"month"=>$year_month); 
            }
      }
      
    echo json_encode($allFound);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && preg_match('/\/login/', $_SERVER['REQUEST_URI'])) {

    ob_start();
     $conn=$db;
    $login_data = json_decode(file_get_contents("php://input"));
    if ($login_data==null){
        $login_data=file_get_contents("php://input");
        $login_data = str_replace("%40", "@", $login_data);
        // $login_data = json_decode($login_data);
      
    }
        
    $username = $login_data->username;
    $password = $login_data->password;
    if($username==null || $password==null){
        parse_str($login_data, $login_array);

        $username = $login_array['username'];
        $password = $login_array['password'];
        
    }

  
    try {
      $pwd = md5($password);
        $sth = $db->query("SELECT *FROM tbl_users_login WHERE username='$username' AND role_id=3 AND password = '$pwd'");
        if ($sth->rowCount() != 0) {
            $res = $sth->fetch();
           
                $role = $res['role_id'];
                $profile = $res['profile_id'];
                $f_name = $res['f_name'];
                $l_name = $res['l_name'];
                $user_phone = $res['telephone'];
                $lgn_id = $res['login_id'];
                $side_id = $res['side'];
                $username=$res['username'];
                $user_data = array(
                    'acc_id' => $lgn_id,
                    'l_name' => $l_name,
                    'f_name' => $f_name,
                    'username' => $username,
                    'role_id' => $role,
                    'co_id' => $profile,
                    'side' => $side_id,
                    'mobile' => $user_phone
                );
        
            echo json_encode($user_data);
                
        } else {
            throw new Exception("Invalid username.");
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        http_response_code(401);
        error_log("Login data: " . print_r($login_data, true));
         exit;
        //echo json_encode(array("error" => "Authentication failed."));
    }
}


?>