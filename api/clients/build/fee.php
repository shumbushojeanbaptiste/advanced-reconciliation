
<?php


    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    include_once '../source/dao.php';
    include_once '../class/tenants.php';
    
    //include'dataj.json';
    
    // $db = new PDO('mysql:host=localhost;dbname=xoderw_xd;charset=utf8', 'xoderw_uxd','(;lr#5j~5Sx=');
    // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $db->query('SET SESSION SQL_BIG_SELECTS=1');
    // $database = new Database();
    // $db = $database->getConnection();
    // $payTen = new Tenant($db);
    $data = json_decode(file_get_contents("php://input"));
    
     $database = new Database();
    $db = $database->getConnection();
    $payTen = new Tenant($db);
     $tras="00022-128197-050-112022";
    //   $tCode = $tras;
    $tCode = $data->trans_code;
    $payRef = $data->pay_ref_no;
    // $payRef = $_GET['pay_ref_no'];
    $slip = $data->slip_no;
    //  $slip = "99987654";
    ###### VALIDATE REF ######
    $transQuery = $db->prepare("SELECT * FROM tbl_tenants 
        WHERE   tbl_tenants.ten_ref_no ='".$payRef."' group by tbl_tenants.ten_id ");
    $transQuery->execute();
    if($transQuery->rowCount()>0){
       $rowTen = $transQuery->fetch();
       $ref = 1;
       $ten_phone = $rowTen['ten_phone'];
       $te_names = $rowTen['te_names'];
      $Mont = $data->pay_amount;
    // $Mont = 280;
       $mySlip = $data->slip_no;
    }
    else{
       $ref = 0; 
        echo json_encode("Money saved.");
    }
    
    ###### VALIDATE TWICE
    $sql2 = $db->prepare("SELECT * FROM `tbl_payment` WHERE trans_code = '".$tCode."' || slip_no = '".$slip."'");
    $sql2->execute(array()); // execute the query with parameter $code...
    if ( $rowcount = $sql2->rowCount() > 0)// if there is data for that payment number...
    {
    // $dup = 1;
    $dup = 0;
    
    }
    else{ 
        $dup = 0;
         
        
        
    }
        
    //CONDITION
    
    if($ref==0){
       http_response_code(400);
        echo json_encode(
            array("message" => "Invalid")
        );  
    }
    
    elseif($dup==1){
        http_response_code(404);
         echo json_encode(
            array("message" => "Duplicate")
        ); 
    }
    
    else{
    $desc="payment from access bank";
   
    $payTen->trans_code =$tCode;  //$data->trans_code
    $payTen->pay_ref_no =$payRef;// $data->pay_ref_no
    $payTen->bank_id = 15;
    $payTen->slip_no =$data->slip_no;
    $payTen->pay_due_date = date('Y-m-d');
    $payTen->rent_date = date('Y-m-d');
    $payTen->pay_amount = $Mont;//$data->pay_amount
    $payTen->pay_description = $desc;//$data->pay_description
    $payTen->pay_trm = date('Y-m-d H:i:s');
    $payTen->pay_user = 9;
    $payTen->status = 1;
    $payTen->c_value = 1;
    $payTen->api_bank = 1;
    
    if($payTen->createPayment()){
        http_response_code(201);
        echo json_encode(
            array("message" => "Success")
        );
        
        $sender="CHIC Ltd";
        $sms = "Dear Customer $te_names, you have paid successfully, slip no.#$mySlip  amount $Mont, Tel: 0788620612";
        
        //echo "success";
//         $ten_phone="0788644687";
//     	$data=array(
//                       "sender"=>"$sender",
//                         "recipients"=>"$ten_phone",
//                         "message"=>"$sms",
//                 );
// 		$url="https://www.intouchsms.co.rw/api/sendsms/.json";
//             $data=http_build_query($data);
//             $username="twagiramungus";
//             $password="M00dle!!";
        
//             $ch=curl_init();
//             curl_setopt($ch,CURLOPT_URL,$url);
//             curl_setopt($ch,CURLOPT_USERPWD,$username.":".$password);
//             curl_setopt($ch,CURLOPT_POST,true);
//             curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//             curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
//             curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
//             $result=curl_exec($ch);
//             $httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
//             curl_close($ch);    
    }else{
        http_response_code(400);
        echo json_encode(
            array("message" => "Fail")
        );
    }
    
    }
?>