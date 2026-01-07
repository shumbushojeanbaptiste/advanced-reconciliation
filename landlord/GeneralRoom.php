<?php
 require_once ("../include/initialize.php");

// get dates from form

$date_from = $_REQUEST['date_from'];
$date_to = $_REQUEST['date_to'];
?>
      <a href="export_bydates_rpt.php?date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>&cpny=<?php echo $cmp_full; ?>">
    <button class="btn btn-success pull-left" style="margin:7px;">Print Excel</button>
    </a>
    <div class="table-responsive">
   <div class="module-body table">
	<table class="table table-hover" width="100%">
		<thead>
		     <tr>
		        <th colspan="8"><?php echo $cmp_full; ?> General Report<br>
		        Date From: <?php echo $date_from; ?><br>
		        Date To: <?php echo $date_to; ?>
		        </th>
		    </tr>
			<tr> <th>#</th>
			 <th>Pay Date</th>
			 <th>Tenant Name</th>
             <th>Room</th>
             <th>Monthly Value</th>
             
             
                 
                 <!--<th>Monthly Invoice</th>-->
                 <!--<th>Special Invoice</th>-->
                 <!--<th>Payment</th>-->
                 <!--<th>Total  Invoice</th>-->
                 <!--<th>Difference</th>-->
                 <!--<th>Room</th>-->
			</tr>
		</thead>
		<tbody>
   
			 <?php
			
                 // fetch 
                $sql = $conn->prepare("SELECT * FROM tbl_payment WHERE  DATE_FORMAT(pay_due_date ,'%Y-%m-%d') BETWEEN '".$date_from."' AND '".$date_to."' GROUP BY DATE_FORMAT(pay_due_date ,'%Y-%m-%d') ");
                $sql->execute();
                $aa=0;
                
                
                 while($fetch = $sql->fetch()) {
              
                     $aa++;
                     
                   
             ?>
		 <tr class="odd gradeX">
		     <td><?php  echo $aa; ?></td>
		     <td><?php echo  $fetch['pay_due_date'];?></td>
		     <td><?php
		     $aref=$fetch['pay_ref_no'];
		     
		       $sql1=$conn->prepare("SELECT * FROM tbl_tenants where ten_ref_no='".$aref."'");
                     $sql1->execute();
                     while($row1=$sql1->fetch()){
                         
                        $row1['ten_id'];
                         echo $row1['te_names'];
                            $tent=$row1['ten_id'];
                         
                          $sqlass=$conn->prepare("SELECT * FROM tbl_assign WHERE tent_id='".$tent."'");
		         $sqlass->execute();
		         
		         while($rowsqlass=$sqlass->fetch()){
		             
		              $room=$rowsqlass['room_id'];
		              
		              $sqlroomname=$conn->prepare("SELECT * FROM tbl_rooms WHERE room_id='".$room."' ");
		              $sqlroomname->execute();
		              
		              while($rowsqlroomname=$sqlroomname->fetch()){
		                  
		               $roomno=$rowsqlroomname['room_no'];
		                 $roomval=$rowsqlroomname['price'];
		                  
		              }
		         }
		         
                     }
		     
		     ?></td>
		     <td>
		         <?php
		     
		     echo $roomno;
		     
		     
		        
		         ?>
		         
		     </td>
		     <td> <?php echo $roomval." " ."Frw";?></td>
                 
                 
      </tr>

        <?php
             }
         ?>

		 </tbody>
		 
	    </table>
    </div>
 </div>