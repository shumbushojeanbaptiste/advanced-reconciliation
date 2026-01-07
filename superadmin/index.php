<?php
require_once ("../include/initialize.php");
if($sess_id == '' or $phone == ''){
    echo"<script>window.location.replace('../index')</script>";
}

$content='dashboard.php';
$view = (isset($_GET['file']) && $_GET['file'] != '') ? $_GET['file'] : '';

switch ($view) {

	case 'home' :
        $title="Dashboard";
		$content='dashboard.php';	
		break;

	case 'new_user' :
        $title="New User";
		$content='new_user.php';	
		break;

	case 'view_users' :
        $title="View Users";
		$content='view_users.php';	
		break;

	default :
	    $title="Home";
		$content ='dashboard.php';
     }

    require_once("../theme/navigation.php");
?>

