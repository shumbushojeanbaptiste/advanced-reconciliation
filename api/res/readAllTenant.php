<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/dao.php';
    include_once '../class/tenants.php';
    $database = new Database();
    $db = $database->getConnection();
    $allTen = new Tenant($db);
    $stmt = $allTen->getTenants();
    $tenCount = $stmt->rowCount();

    echo json_encode($tenCount);
    if($tenCount > 0){
        
        $tenantArr = array();
        $tenantArr["body"] = array();
        $tenantArr["tenCount"] = $tenCount;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "ten_id" => $ten_id,
                "ten_ref_no" => $ten_ref_no,
                "te_names" => $te_names,
                "tin_no" => $tin_no,
                "ten_phone" => $ten_phone,
                "leave_stts" => $leave_stts
            );
            array_push($tenantArr["body"], $e);
        }
        echo json_encode($tenantArr);
    }
    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>