<?php  include'../include/initialize.php'; ?>

   <div class="table-responsive">
   <div class="module-body table">
	<table class="table table-hover" width="100%">
		<thead>
		    <?php
			$floor_code=$_REQUEST['floor_code'];  ?>
		  
			<tr> <th>#</th>
                 <!--<th>Ref No</th>-->
                 <th>Tenant Names <?PHP echo $floor_code ?></th>
                 <!--<th>Tenant phone</th>-->
                 <!--<th>Pay By Month</th>-->
                 <!--<th>coution</th>-->
                 
                 <th>Monthly Amount</th>
                 
                 
			</tr>
		</thead>
		<tbody>
   
			 <?php
			$floor_code=$_REQUEST['floor_code'];
                 // fetch 
                $sql = $db->prepare("SELECT * FROM tbl_tenants  WHERE ten_staff='".$company."' and ten_id  in (select tent_id from tbl_assign where assgn_status=1 and room_id  in (select room_id from tbl_rooms where r_floor='".$floor_code."')  group by tent_id)  and ten_id not in (select tenant_id from tbl_tenant_leave group by tenant_id) ");
                $sql->execute();
                $aa=0;
                $totalInvoice =0;
                $totalPayment =0;
                $totalBalance =0;
                $genBalance =0;
                
                //outer loop
                $totalAMT=0;
                $tdiscount=0;
                while($fetch = $sql->fetch()) {
                     
                     $aa++;
                     $ten_id = $fetch['ten_id'];
                     $ten_ref = $fetch['ten_ref_no'];
                     
             ?>
		 <tr class="odd gradeX">
		     <td><?php  echo $aa; ?></td>
            
                 <td><?php echo $fetch['te_names']; ?></td>
                 
                 
                
                 <td>
                   
                     
                       <?php 
                     $sql12 = $conn->prepare("SELECT SUM(amount) AS total FROM tbl_assign
                     
                                
                     WHERE tbl_assign.tent_id = '".$ten_id."' and assgn_status = 1 ");
                        $sql12->execute(array()); 
                        $rowcount = $sql12->rowCount();
                       
                         $row12 = $sql12->fetch(PDO::FETCH_ASSOC);
                         $tot_assign =$row12['total'];
                       
                                 
                        $sql13 = $conn->prepare("SELECT SUM(discount) AS disc FROM tbl_assign
                        INNER JOIN tbl_rooms ON tbl_rooms.room_id=tbl_assign.room_id  
                        WHERE tent_id = '".$ten_id."' ");
                        $sql13->execute(array()); 
                        $rowcount13 = $sql13->rowCount();
                       
                       $row13 = $sql13->fetch(PDO::FETCH_ASSOC);
                         $tot_disc = $row13['disc'];
                        
                        
                       $paymon = $row12['total'] - $row13['disc'];
                       
                       $tot_paymon = $tot_assign-$tot_disc;
                       $totalAMT=$totalAMT+$tot_paymon;
                       echo number_format($tot_assign);
                     ?>
                 </td>
                
              
            
             </tr>

        <?php
             }
         ?>

		 </tbody>
		  <tfoot>
            <tr >  
              <td colspan="2">Total</td>
              <!--<td align="left">-->
                  <?php //echo number_format($tot_paymon).".00";?>
            <!--</td>-->
            
              <td align="left">
                  <?php echo number_format($totalAMT);?>
            </td>
        
           
              
            </tr>
          </tfoot>
	    </table>
    </div>
 </div>