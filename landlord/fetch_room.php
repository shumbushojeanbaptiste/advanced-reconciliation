<?php 
    require_once ("../include/initialize.php");
    if(isset($_POST["rm_id"]))  
    {
      $query = "SELECT * FROM tbl_rooms WHERE id = '".$_POST["rm_id"]."'";  
      $result = $conn->prepare($query);
      $result->execute();
      
      $row = $result->fetch();  
      echo json_encode($row);  
    }  
?>