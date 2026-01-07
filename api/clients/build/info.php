<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    include_once '../source/dao.php';
    include_once '../class/tenants.php';
    $database = new Database();
    $db = $database->getConnection();
    $ten = new Tenant($db);
    $ten->id = isset($_GET['id']) ? $_GET['id'] : die();  
  
    $ten->getSingleTenant();
    if($ten->ten_ref_no != null){
        // create array
        $emp_arr = array(
            "ten_id" =>  $ten->ten_id,
            "ten_ref_no" => $ten->ten_ref_no,
            "te_names" => $ten->te_names,
            "tin_no" => $ten->tin_no,
            "ten_phone" => $ten->ten_phone,
            "leave_stts" => $ten->leave_stts,
            "Amount" => $ten->Amount
        );
      
        http_response_code(201);
        echo json_encode($emp_arr);
    }
      
    else{
        http_response_code(400);
        echo json_encode("Record not found.");
    }
?>