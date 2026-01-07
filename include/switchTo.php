<?php  include'config.php'; ?>
<?PHP  
$cpn=$_REQUEST['c_id'];
$USER=$_REQUEST['user_id'];
$PART=$_REQUEST['switchToother'];
// tbl_users_login.profile_id

	$sql = $conn->prepare("SELECT * FROM `tbl_users_login` WHERE `login_id` = '$USER' ");
	$sql->execute();
	$fetchus = $sql->fetch();
	$u_id = $fetchus['profile_id'];

// 	$sql = $conn->prepare("SELECT * FROM `tbl_admins` WHERE `adm_id` = '$u_id' ");
// 	$sql->execute();
// 	$fetch = $sql->fetch();
// 	$company_id=$fetch['company_id'];
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    			$sql = "UPDATE `tbl_admins` SET `company_id` = '".$PART."' WHERE `adm_id` = '".$u_id."' ";
    			$didq = $db->prepare($sql);
    			$didq->execute();






?>