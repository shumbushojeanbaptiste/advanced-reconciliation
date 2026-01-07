<?php 

 require_once ("../include/initialize.php");
 try{
             $cancel = $_POST['cancel1'];
             $pay_id = $_REQUEST['pay_id'];
             $slip= $_POST['slip'];
             $amount = 0;
             $status = 0;
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             
            //  $sql = "UPDATE `tbl_payment` SET `pay_amount` = '$amount', `pay_description` = '$cancel', `status` = '$status' WHERE `pay_id` = '$pay_id'";
            
            $li_quIPchanged = $db->prepare("SELECT  * FROM   tbl_payment where pay_id ='".$pay_id."' ");
            $li_quIPchanged->execute(); 
            $changed=$li_quIPchanged->fetch();
            
            $date1=date("Y-m-d");
            $sql12 = "INSERT INTO `tbl_pay_cancel`(`amount`, `rec_by`,`due`)
             VALUES ('".$changed['pay_amount']."', '".$_SESSION['USER_ID']."','$date1' )";
             $conn->exec($sql12);
             
            
             $sql = "DELETE FROM tbl_payment WHERE `pay_id` = '$pay_id'"; 
             $conn->exec($sql);
   
     
             
         }catch(PDOException $e){
             echo $e->getMessage();
         }
echo 1;

?>