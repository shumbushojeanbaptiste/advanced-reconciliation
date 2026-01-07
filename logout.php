<?php
require_once "./include/initialize.php"; 
 include 'hd.php';
   
    $tm=date("Y-m-d H:i:s");	
    if (isset($_SESSION['sess_id']) and isset($_SESSION['phone']))
    {
        $name=strtoupper($_SESSION['fname']." ".$_SESSION['lname']);
        session_unset();
        session_destroy();
        echo "<br /><br /><center><font face='Verdana' size='2' >Dear <b>".$name."</b>, You have successfully logged out. <br /><br /><br /></font></center>";
        echo'<meta http-equiv="refresh"'.'content="1;URL=index">';
    }
    else
    {
    echo "<br><center> <font face='Verdana' size='2' color=red>No User Data. Use your login and try!. <br/><br/><br/> </center><br><center><input type='button' value='Retry' onClick='history.go(-1)'></font></center>";
     echo'<meta http-equiv="refresh"'.'content="1;URL=index">';
    }
    
    ?>
  
<?php include 'ft.php';?>