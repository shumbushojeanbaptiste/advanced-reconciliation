<?php
    require_once ("../include/initialize.php");
    if(isset($_POST["tnt_id"]))
    {
        $query = "SELECT * FROM tbl_invoice_settings where tenant_id = '".$_POST["tnt_id"]."' AND paid = 8";
        $result = $conn->prepare($query);
        $result->execute();
        if($result->rowCount() > 0) {
            $row = $result->fetch();
            $row['status'] = 1;
        }
        else {
            $query = "SELECT tt.id, tt.first_name, tt.last_name, tt.phone, tt.email, tt.address, tc.room_id, tc.contract_type, ts.status_name FROM tbl_tenants tt INNER JOIN tbl_contracts tc ON tt.id = tc.tenant_id INNER JOIN tbl_status ts ON tc.contract_type = ts.id WHERE tt.id = '".$_POST["tnt_id"]."'";
            $result = $conn->prepare($query);
            $result->execute();
            $row = $result->fetch();
        }
        echo json_encode($row);
    }
?>