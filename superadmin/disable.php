
<?php  

require_once ("../include/initialize.php");
	 if(ISSET($_REQUEST['disable'])){
        try{
		    
			$status_up = $_REQUEST['disable'];
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = " UPDATE tbl_admins SET adm_status=0 WHERE adm_id=$status_up; ";
			$conn->exec($sql);
			
			$msg=" User Inactivated";

		}catch(PDOException $e){
			echo $e->getMessage();
		}
		
		//echo'<meta http-equiv="refresh"'.'content="2;URL=?file=new_user">'; 
	 }
	  	if(ISSET($_REQUEST['enable'])){
        try{
		    
			$status_up = $_REQUEST['enable'];
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = " UPDATE tbl_admins SET adm_status=1 WHERE adm_id=$status_up; ";
			$conn->exec($sql);
			
			$msg=" User Activated";

		}catch(PDOException $e){
			echo $e->getMessage();
		}
		
		//echo'<meta http-equiv="refresh"'.'content="2;URL=?file=new_user">'; 
	 }
?>