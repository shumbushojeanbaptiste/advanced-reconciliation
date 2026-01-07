




<?php  
   require_once ("../include/initialize.php");

$ten_stmt = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_staff=9 and ten_ref_no  in (select 	pay_ref_no from  tbl_payment group by pay_ref_no) order by ten_id asc");
                $ten_stmt->execute();
                $rr=0;
                while($fetch_ten = $ten_stmt->fetch()){
                    
              $sqlstm1=$conn->prepare("SELECT * FROM tbl_payment WHERE pay_ref_no='".$fetch_ten['ten_ref_no']."' ");
                    $sqlstm1->execute();
                    $fetchsqlstm1=$sqlstm1->fetch();   
                    
    //         	$sql = "UPDATE `tbl_payment` SET `pay_user` =9 WHERE `pay_ref_no` = '".$fetch_ten['ten_ref_no']."'";
    // 			$didq = $db->prepare($sql);
    // 			$didq->execute();   
                   
                //  $ten_staff = $fetch_ten['ten_ref_no'];  
                //  echo $fetchsqlstm1['pay_amount']."<br>";   
                // $rr=$rr+$fetchsqlstm1['pay_amount'];    
                }
                
                // echo "-------------------";
                // echo $rr;
                // echo "<br>";
                // $fff="uea202013302";
                // $reg=substr($fff,0,3);
                // $reg2=substr($fff,3,4);
                // $reg3=substr($fff,7,8);
                
                // echo $reg."/".$reg2."/".$reg3;
                
                  
     $stmt = $db->query('SELECT * FROM tbl_ten_contracts WHERE tbl_ten_contracts.cntr_status=0 ');
     $stmt->execute();
     $a=0;
     while($cost=$stmt->fetch())
     {
      $a++;
      $stmt1 = $db->query('SELECT * FROM tbl_c_room WHERE `contr_id` = "'.$cost['contr_id'].'" ');
      $stmt1->execute();
      
       echo   $a."/ ".$cost['c_month'];
       
      echo "["; 
      while($cost1=$stmt1->fetch()){
          echo $cost1['room_id'].",";
      }
      
      
      echo "]";
      
    //   $sql = "DELETE FROM tbl_ten_contracts WHERE `cntr_status` = 0 "; 
    //      $conn->exec($sql);
       
      echo "<br>";
     }

?>