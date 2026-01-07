<?php
// Ensure the export is for Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $_REQUEST['cpny'] . ' Tenants list Report.xls"');
header('Cache-Control: max-age=0');
require_once("../include/initialize.php");

 ?>
   
<table class="table datatables" style="font-size:15px" width='100%' border=1>	
		<thead>
			<tr>
			    <th>#</th>
				<th>Names</th>
				<th>Phone</th>
				<th>TIN No</th>
				<th>Coution</th>
				<th>Monthly Amount</th>
				<th>Room</th>
			</tr>
		</thead>
		<tbody>
			<?php
		   	    $i= 0 ;
                $ten_stmt = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_staff='".$cpny_ID."' and ten_id not in (select tenant_id from tbl_tenant_leave group by tenant_id) order by ten_id asc");
                $ten_stmt->execute();
                while($fetch_ten = $ten_stmt->fetch()) {
                    $t_id = $fetch_ten['ten_id'];
                    $ten_staff = $fetch_ten['ten_staff'];
                    $ten_ref_no=$fetch_ten['ten_ref_no'];
                    
                    $sqlstm=$conn->prepare("SELECT * FROM tbl_assign WHERE tent_id='".$t_id."' and assgn_status=1");
                    $sqlstm->execute();
                    
                    
                    $rowCountRoom= $sqlstm->rowCount();
                    
                        
                    $sqlstm1=$conn->prepare("SELECT * FROM tbl_rooms WHERE room_id='".$r_id."' ");
                    $sqlstm1->execute();
                    $fetchsqlstm1=$sqlstm1->fetch();
                    $counter=$sqlstm1->rowCount();
                    if($counter<0){
                        
                    }
                        
                        $r_no=$fetchsqlstm1['room_no'];
                      
                    
                    
                    $i += 1;
                    
                
                    
                    // check if row not exist
                    if ($notf_Count == 0)  {
                        $bullet = '<label class="text-warning fa fa-circle"></label>';
                        $hidden= 0;
                        
                    } 
                    else{
                         $bullet = '<label class="text-success fa fa-circle"></label>';
                         $hidden= 1;
                    }
                    
                 if($cpny_ID==10){
                     
                    // $sqlstm1D=$conn->prepare("DELETE FROM tbl_payment WHERE pay_ref_no='".$ten_staff."' ");
                    // $sqlstm1D->execute(); 
                 }   
             ?>
			<tr class="gradeA">
			    <td class="center">
				    <?php echo $i; ?>
				</td>
			
				<td class="center">
				    <?php if($r_no==""){
				    echo $fetch_ten['te_names']." ";   
				    
				    }
				    else {
				     
				     echo $fetch_ten['te_names']." ";   
				    
				    } ?>
				</td>
				<td class="center">
				    <?php echo $fetch_ten['ten_phone']; ?>
				</td>
				<td class="center">
				<?php if($fetch_ten['tin_no'] == ""){
				     echo "-";
				    }else{
				 echo $fetch_ten['tin_no']; 
				 }?>
    			</td>
    			<td>
    			    <?php  
    			    
    			      $coution = $conn->prepare("SELECT SUM(coution_amaunt) AS totalCtion FROM tbl_ten_contracts
                      WHERE tbl_ten_contracts.tenant_id = '".$t_id."' and cntr_status=1  ");
                        $coution->execute(array()); 
                        $rowcoution = $coution->rowCount();
                        $coutiondata = $coution->fetch(PDO::FETCH_ASSOC);
                         
                         echo number_format($coutiondata['totalCtion'],0);
    			    ?>
    			</td>
    			<td>
    			    
    			      <?php 
    			      
    			      
                         
                         
                     $sql12 = $conn->prepare("SELECT SUM(price) AS total FROM tbl_assign
                     
                     INNER JOIN tbl_rooms ON tbl_rooms.room_id=tbl_assign.room_id             
                     WHERE tbl_assign.tent_id = '".$t_id."' and assgn_status=1  ");
                        $sql12->execute(array()); 
                        $rowcount = $sql12->rowCount();
                       
                         $row12 = $sql12->fetch(PDO::FETCH_ASSOC);
                         $tot_assign =$row12['total'];
                       
                                 
                        $sql13 = $conn->prepare("SELECT SUM(discount) AS disc FROM tbl_assign
                        INNER JOIN tbl_rooms ON tbl_rooms.room_id=tbl_assign.room_id  
                        WHERE tent_id = '".$t_id."' and assgn_status=1 ");
                        $sql13->execute(array()); 
                        $rowcount13 = $sql13->rowCount();
                       
                       $row13 = $sql13->fetch(PDO::FETCH_ASSOC);
                         $tot_disc = $row13['disc'];
                        
                        
                       $paymon = $row12['total'] - $row13['disc'];
                       
                       $tot_paymon = $tot_assign-$tot_disc;
                       $totalAMT=$totalAMT+$tot_paymon;
                       echo number_format($tot_paymon,0);
                       
                       
                        $sql1bill = $conn->prepare("SELECT SUM(invoice_fees) AS total_bill, SUM(pay_amount) AS total_bill_pay FROM  tbl_bill
                        INNER JOIN tbl_pay_bill ON tbl_pay_bill.ten_id=tbl_bill.ten_id  
                        WHERE tbl_bill.ten_id = '".$ten_ref_no."' ");
                        $sql1bill->execute(array()); 
                        $rowcount1bill = $sql1bill->rowCount();
                         $ten_ref_no = $sql1bill->fetch(PDO::FETCH_ASSOC);
                         
                         $balance_bill=$ten_ref_no['total_bill']-$ten_ref_no['total_bill_pay'];
                     ?>
    			</td>
    			<td class="center" style="width:150px;">
    			    <?php
    			     $sqlstm1=$conn->prepare("SELECT * FROM tbl_assign WHERE tent_id='".$t_id."' and assgn_status=1");
                    $sqlstm1->execute();
                    
                    
                    while($rowCountRoom1= $sqlstm1->fetch()){
                        $roomid=$rowCountRoom1['room_id'];
                        $sqlstm12=$conn->prepare("SELECT * FROM tbl_rooms WHERE room_id='".$roomid."' ");
                        $sqlstm12->execute();
                        $fetchsqlstm12=$sqlstm12->fetch();
                        echo $fetchsqlstm12['room_no']."  ".$fetchsqlstm12['room_area']."M<sup>2</sup>"."  ,";
                    }
                    
                    ?>
    			    
				 </td>
    			
			</tr>

			<?php
                }
			?>
		 </tbody>
	</table>
 