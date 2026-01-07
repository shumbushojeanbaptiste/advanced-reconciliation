<?php
    require_once ("../include/initialize.php");
    if(isset($_POST["cont_id"]))
    {
        $query = "SELECT tc.*, tt.first_name, tt.last_name, tt.phone, tt.email, tt.address, tr.room_name 
        FROM tbl_contracts tc INNER JOIN tbl_tenants tt ON tc.tenant_id = tt.id INNER JOIN tbl_rooms tr ON tc.room_id = tr.id WHERE contr_id = '".$_POST["cont_id"]."'";
        $result = $conn->prepare($query);
        $result->execute();

        $row = $result->fetch();
        echo json_encode($row);
    }
?>