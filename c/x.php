<?php
ob_start();
$this_file = str_replace('\\', '/', __File__) ;
$doc_root = $_SERVER['DOCUMENT_ROOT'];

$web_root =  str_replace (array($doc_root, "include/config.php") , '' , $this_file);
$server_root = str_replace ('config/config.php' ,'', $this_file);

define ('web_root' , $web_root);
define('server_root' , $server_root);

date_default_timezone_set('Africa/Kigali');

//PDO Connection 
$db = new PDO('mysql:host=localhost;dbname=itecr2_xode;charset=utf8', 'itecr2_uxode','f%5YnF8LkJ[3');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->query('SET SESSION SQL_BIG_SELECTS=1');

//2and PDO CONNECTION

    $db_username = 'itecr2_uxode';
	$db_password = 'f%5YnF8LkJ[3';
	$conn = new PDO( 'mysql:host=localhost;dbname=itecr2_xode', $db_username, $db_password );
	if(!$conn){
		die("Fatal Error: Connection Failed!");
	}
	
ob_end_flush();
?>

