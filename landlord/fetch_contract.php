<?php
    require_once ("../include/initialize.php");
    if(isset($_POST["contract_id"]))
    {
      $query = "SELECT * FROM tbl_contract_agreements WHERE id = '".$_POST["contract_id"]."'";
      $result = $conn->prepare($query);
      $result->execute();

      $row = $result->fetch();
      echo json_encode($row);
    }
?>