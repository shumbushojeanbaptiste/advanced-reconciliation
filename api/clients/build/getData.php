


<?php

class Tenant{
    
public function __construct($db)

{
            $this->conn = $db;
}

  public function createPayment(){
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
  }
}
?>