<?php
//define the core paths
//Define them as absolute peths to make sure that require_once works as expected

//DIRECTORY_SEPARATOR is a PHP Pre-defined constants:
//(\ for windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define ('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');

defined('LIB_PATH') ? null : define ('LIB_PATH',SITE_ROOT.DS.'include');

//load the database configuration first.
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/session.php');

$sess_id    = $_SESSION['sess_id']    ?? '';
$role       = $_SESSION['role']       ?? '';
$lname      = $_SESSION['lname']      ?? '';
$fname      = $_SESSION['fname']      ?? '';
$phone      = $_SESSION['phone']      ?? '';
$USER_ID    = $_SESSION['USER_ID']    ?? '';
$side_value = $_SESSION['side_value'] ?? '';
    
  
  
    
$stmtrole = $db->query('SELECT * FROM  tbl_roles WHERE role_id="'.$role.'" ');
  $rowrole = $stmtrole->fetch(PDO::FETCH_ASSOC);
  $roleName=$rowrole['role_name'] ?? '';
    
?>


         
