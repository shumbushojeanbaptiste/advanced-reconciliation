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
$db = new PDO('mysql:host=localhost;dbname=fin_xode;charset=utf8', 'root','');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->query('SET SESSION SQL_BIG_SELECTS=1');

//2and PDO CONNECTION

    $db_username = 'root';
	$db_password = '';
	$conn = new PDO( 'mysql:host=localhost;dbname=fin_xode', $db_username, $db_password );
	if(!$conn){
		die("Fatal Error: Connection Failed!");
	}
	
ob_end_flush();
?>
