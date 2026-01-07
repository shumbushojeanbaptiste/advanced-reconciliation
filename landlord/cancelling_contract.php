<?php 

 require_once ("../include/initialize.php");
 try{
             $cancel = $_POST['Ccancel1'];
             $contract_id = $_REQUEST['contract_id'];
             $slip= $_POST['slip'];
             $amount = 0;
             $status = 0;
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             
             $sql = "UPDATE `tbl_ten_contracts` SET `cntr_status` =0, `cancel_desc` = '$cancel' WHERE `contr_id` = '$contract_id'";
            //  $sql = "DELETE FROM tbl_payment WHERE `pay_id` = '$pay_id'"; 
              $conn->exec($sql);
              
              $sqlroom = "UPDATE `tbl_c_room` SET `croom_status` =0 WHERE `contr_id` = '$contract_id'";   
            //  $sql = "DELETE FROM tbl_payment WHERE `pay_id` = '$pay_id'"; 
              $conn->exec($sqlroom);
   
         $sql = "DELETE FROM tbl_c_room WHERE `contr_id` = '$contract_id'"; 
         $conn->exec($sql);
             
         }catch(PDOException $e){
             echo $e->getMessage();
         }
echo 1;

?>