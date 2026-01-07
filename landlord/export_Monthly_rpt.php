<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename= ".$_REQUEST['cpny']." Monthly_report.xls");
?>
<?php
 require_once ("../include/initialize.php");

// get dates from form

$date_from = $_REQUEST['date_from'];
$monthly =$_REQUEST['monthly'];
?>

 <div class="table-responsive">
   <div class="module-body table">
	<table class="table table-hover" border="1" width="100%">
		<thead>
		     <tr>
		        <th colspan="6"><?php echo $cmp_full; ?> Monthly Report<br>
		       Month: <?php echo $date_from; ?><br>
		       
		        </th>
		    </tr>
			<tr> <th>#</th>
                 <!--<th>Ref No</th>-->
                 <th>Tenant</th>
                 <th>Tenant phone</th>
                 <th>Pay By Month</th>
                 <th>Invoice</th>
                 <th>Special Invoice</th>
                 <th>Payment</th>
                 <th>Total  Invoice</th>
                 <th>Difference</th>
                 <!--<th>Room</th>-->
			</tr>
		</thead>
		<tbody>
   
			 <?php
			
                 // fetch 
                $sql = $db->prepare("SELECT * FROM tbl_tenants  WHERE ten_staff='".$company."'  and ten_id not in (select tenant_id from tbl_tenant_leave group by tenant_id) ");
                $sql->execute();
                $aa=0;
                 while($fetch = $sql->fetch()) {
                     
                     $aa++;
                     $ten_id = $fetch['ten_id'];
                     $ten_ref = $fetch['ten_ref_no'];
                     
             ?>
		 <tr class="odd gradeX">
		     <td><?php  echo $aa; ?></td>
                 <!--<td><?php echo $fetch['ten_ref_no']; ?></td>-->
                 <td><?php echo $fetch['te_names']; ?></td>
                 <td><?php echo $fetch['ten_phone']; ?></td>
                 <td>
                     <?php 
                     $sql12 = $conn->prepare("SELECT SUM(amount) AS total FROM `tbl_assign` WHERE tent_id = '".$ten_id."' ");
                        $sql12->execute(array()); 
                        $rowcount = $sql12->rowCount();
                        if ($rowcount > 0)// if there is data for that payment number...
                        {
                         $row12 = $sql12->fetch(PDO::FETCH_ASSOC);
                         $tot_assign = $tot_assign + $row12['total'];
                        }
                                 
                        $sql13 = $conn->prepare("SELECT SUM(discount) AS disc FROM `tbl_assign` WHERE tent_id = '".$ten_id."' ");
                        $sql13->execute(array()); 
                        $rowcount13 = $sql13->rowCount();
                        if ($rowcount13 > 0)// if there is data for that payment number...
                        {
                         $row13 = $sql13->fetch(PDO::FETCH_ASSOC);
                         $tot_disc = $tot_disc + $row13['disc'];
                        }
                        
                       $paymon = $row12['total'] - $row13['disc'];
                       
                       $tot_paymon = $tot_assign-$tot_disc;
                       echo $paymon;
                     ?>
                     </td>
                 <td>
                     <?php
                     $sql2 = $conn->prepare("SELECT SUM(invoice_fees) AS totalinv FROM `tbl_invoice` WHERE inv_ref_no = '".$ten_ref."' AND auto = 'yes' AND DATE_FORMAT(invoice_date,'%Y-%m')= '$monthly' ");
                        $sql2->execute(array()); // execute the query with parameter $code...
                                $rowcount = $sql2->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $row1 = $sql2->fetch(PDO::FETCH_ASSOC);
                                $tot_inv_yes = $tot_inv_yes + $row1['totalinv'];
                                $totinv2 = $row1['totalinv'];
                                echo number_format($row1['totalinv']).".00";
                                 }
                                 else{
                                     echo "0";
                                 }
                     ?>
                 </td>
                 <td>
                     <?php
                     $sql3 = $conn->prepare("SELECT SUM(invoice_fees) AS totalinv2 FROM `tbl_invoice` WHERE inv_ref_no = '".$ten_ref."' AND auto = 'no'  AND DATE_FORMAT(invoice_date,'%Y-%m')= '$monthly'  ");
                        $sql3->execute(array()); // execute the query with parameter $code...
                                $rowcount = $sql3->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $row2 = $sql3->fetch(PDO::FETCH_ASSOC);
                                $tot_inv_no = $tot_inv_no+$row2['totalinv2'];
                                $invAmount = $row2['totalinv2'];
                                echo number_format($row2['totalinv2']).".00";
                                 }
                                 else{
                                     echo "0";
                                 }
                     ?>
                 </td>
                 <td>
                     <?php
                     $sql4 = $conn->prepare("SELECT SUM(pay_amount) AS totalpay FROM `tbl_payment` WHERE pay_ref_no = '".$ten_ref."' AND DATE_FORMAT(rent_date,'%Y-%m')='$monthly' ");
                        $sql4->execute(array()); // execute the query with parameter $code...
                                $rowcount = $sql4->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $row3 = $sql4->fetch(PDO::FETCH_ASSOC);
                                $payment = $row3['totalpay'];
                                $tot_pay = $tot_pay + $row3['totalpay'];
                                
                                echo number_format($row3['totalpay']).".00";
                                 }
                                 else{
                                     echo "0";
                                 }
                     ?>
                 </td>
                 <td>
                     <?php
                     $tot_inv = $totinv2 + $invAmount;
                     $gen_total = $gen_total+$tot_inv;
                     echo number_format($tot_inv).".00";
                     ?>
                </td>
                
                <td>
                     <?php
                     $tot_diff = $tot_inv - $payment;
                     $diff_total = $diff_total+$tot_diff;
                     echo number_format($tot_diff).".00";
                     ?>
                </td>
                <!--<td>-->
                    <?php  
                //      $sqlass = $db->prepare("SELECT * FROM tbl_assign inner join tbl_rooms on tbl_rooms.room_id = tbl_assign.room_id  WHERE tbl_assign.company_id='".$company."'  and tbl_assign.tent_id='".$ten_id."' ");
                //             $sqlass->execute();
                //             while($fetch_tenroom = $sqlass->fetch()){
        				    // echo $fetch_tenroom['room_no']." <br> ";
        				    //  }
                    ?>
                <!--</td>-->
             </tr>

        <?php
             }
         ?>

		 </tbody>
		  <tfoot>
            <tr >  
              <td colspan="3">Total</td>
              <td align="left"><?php echo number_format($tot_paymon).".00";?></td>
              <td align="left"><?php echo number_format($tot_inv_yes).".00";?></td>
              <td align="left"><?php echo number_format($tot_inv_no).".00";?></td>
              <td align="left"><?php echo number_format($tot_pay).".00"; ?></td>
              <td align="left"><?php echo number_format($gen_total).".00"; ?></td>
              <td align="left"><?php echo number_format($diff_total).".00"; ?></td>
              <!--<td></td>-->
            </tr>
          </tfoot>
	    </table>
    </div>
 </div>