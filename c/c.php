<?php
ob_start();
$this_file = str_replace('\\', '/', __File__) ;
$doc_root = $_SERVER['DOCUMENT_ROOT'];

$web_root =  str_replace (array($doc_root, "c/c.php") , '' , $this_file);
$server_root = str_replace ('c/c.php' ,'', $this_file);

define ('web_root' , $web_root);
define('server_root' , $server_root);

error_reporting(0);
date_default_timezone_set('Africa/Kigali');

//PDO Connection 
$db = new PDO('mysql:host=localhost;dbname=itecr2_qoqa;charset=utf8', 'itecr2_q','v(KuFpl?~U8J');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->query('SET SESSION SQL_BIG_SELECTS=1');

//2and PDO CONNECTION

    $db_usn = 'itecr2_q';
	$db_pwd = 'v(KuFpl?~U8J';
	$conn = new PDO( 'mysql:host=localhost;dbname=itecr2_qoqa', $db_usn, $db_pwd );
	if(!$conn){
		die("Fatal Error: Connection Failed!");
	}
	
ob_end_flush();
?>

