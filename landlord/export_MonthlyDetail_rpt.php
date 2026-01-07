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
$monthly33 =date('m',strtotime($monthly));;
$mtn=$_REQUEST['monthly'];
?>

 <div class="table-responsive">
   <div class="module-body table">
	<table class="table table-hover" border=1 width="100%">
		<thead>
		     <tr>
		        <th colspan="8"><?php echo $cmp_full; ?> Monthly Report<br>
		        Month: <?php   $monthly22= date('M',strtotime($monthly)); echo $monthly22; ?><br>
		       
		        </th>
		    </tr>
			<tr> <th># </th>
                 <!--<th>Ref No</th>-->
                 <th>Shop No/Room No</th>
                 <th>Tenant name</th>
                 <th>Monthly fees</th>
                 <th>Contract date</th>
                 <th>Expected recov AMT</th>
                 <th>Total Payment received</th>
                 <th>Balance</th>
                 <th>Payment date</th>
                 <th>Remark/comment</th>
			</tr>
		</thead>
		<tbody>
   
			 <?php
			
                 // fetch 
                $sql = $db->prepare("SELECT * FROM tbl_rooms  INNER JOIN tbl_assign ON tbl_assign.room_id=tbl_rooms.room_id 
                
                inner join  tbl_tenants on  tbl_tenants.ten_id=tbl_assign.tent_id
                 inner join tbl_ten_contracts on tbl_ten_contracts.tenant_id=tbl_tenants.ten_id
                WHERE tbl_ten_contracts.changing_reasons IS NULL AND  tbl_rooms.company_id='".$company."' and  tent_id in (select tenant_id from  tbl_next_pay where DATE_FORMAT(next_date,'%m')= '$monthly33' group by tenant_id ) GROUP BY r_floor  order by r_floor asc");
                $sql->execute();
             $tot_paymon2=0;
             $tot_inv_no2=0;
             $tot_pay2=0;
             $diffr2=0;
                 while($fetch = $sql->fetch()) {
                     
                     $aa++;
                     $ten_id = $fetch['ten_id'];
                     $ten_ref = $fetch['ten_ref_no'];
                     
                   $sqlF = $db->prepare("SELECT * FROM tbl_block  WHERE  Block_code='".$fetch['r_floor']."' and company_id='".$company."' ");
                $sqlF->execute();
                $floor= $sqlF->fetch();
             ?>
		      <tr class="odd gradeX">
		    
             <td  colspan="10"><h3><?php  echo  $floor['block_name']?> </h3></td>
             </tr>
             
             <?php  
              $sqlDetail = $db->prepare("SELECT * FROM tbl_rooms
               INNER JOIN tbl_assign ON tbl_assign.room_id=tbl_rooms.room_id
               inner join  tbl_tenants on  tbl_tenants.ten_id=tbl_assign.tent_id
               inner join tbl_ten_contracts on tbl_ten_contracts.tenant_id=tbl_tenants.ten_id
              WHERE tbl_ten_contracts.changing_reasons IS NULL  AND  tbl_rooms.company_id='".$company."' and tbl_rooms.r_floor='".$fetch['r_floor']."' and tent_id in (select tenant_id from  tbl_next_pay where DATE_FORMAT(next_date,'%m')= '$monthly33' group by tenant_id )   order by tbl_rooms.room_no ");
                $sqlDetail->execute();
                   $aa=0;
                   $tot_paymon=0;
                   $tot_inv_no=0;
                   $tot_pay=0;
             while($Detail = $sqlDetail->fetch()) {
                  $aa++;
                  
                $sqlT = $db->prepare("SELECT * FROM tbl_tenants  WHERE ten_id='".$Detail['tent_id']."' ");
                $sqlT->execute();  
                $DetailT = $sqlT->fetch(); 
                
                
                 $sqlC = $db->prepare("SELECT * FROM tbl_ten_contracts  WHERE 	tenant_id='".$Detail['tent_id']."' ");
                $sqlC->execute();  
                $DetailC = $sqlC->fetch();
                
                
             
              ?>
              <tr>
               <td><?php  echo $aa; ?></td>
               <td><?php echo $Detail['room_no']  ?></td>
               <td><?php echo $DetailT['te_names']  ?></td>
               <td><?php echo number_format($Detail['price']-$Detail['discount']); $tot_paymon=$tot_paymon+($Detail['price']-$Detail['discount']);  ?></td>
               <td><?php
               if($sqlC->rowCount()>0){
               $c_date=substr($DetailC['c_start_date'],8,11);
               $contractod=$DetailC['c_start_date'];
               //$contractod= date('d/m/Y',strtotime($contractod));
               $contractod=date('d',strtotime($contractod));
               $contractod=date("Y")."-".$_REQUEST['monthly']."-".$contractod;
               $contractod= date('d/m/Y',strtotime($contractod));
               }else{
                 $c_date="01"; 
                 $contractod="-";
                
               }
               $contact_date=$c_date."/".$mtn."/".$year_id;
               echo $contractod  ?></td>
               <td><?php 
               
                    $sql2 = $conn->prepare("SELECT SUM(invoice_fees) AS totalinv FROM `tbl_invoice` WHERE inv_ref_no = '".$DetailT['ten_ref_no']."' AND auto = 'yes' AND DATE_FORMAT(invoice_date,'%Y-%m')= '$monthly' ");
                        $sql2->execute(array()); // execute the query with parameter $code...
                                $rowcount = $sql2->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $row1 = $sql2->fetch(PDO::FETCH_ASSOC);
                                $tot_inv_yes = $tot_inv_yes + $row1['totalinv'];
                                $totinv2 = $row1['totalinv'];
                                echo number_format($row1['totalinv']).".00";
                                $tot_inv_no=$tot_inv_no+$row1['totalinv'];
                                 }
                                 else{
                                     echo "0";
                                 }
                                 
                                 
                                 
                           
               ?></td>
               <td>
                       <?php
                     $sql4 = $conn->prepare("SELECT SUM(pay_amount) AS totalpay FROM `tbl_payment` WHERE pay_ref_no = '".$DetailT['ten_ref_no']."' AND DATE_FORMAT(rent_date,'%Y-%m')='$monthly' ");
                        $sql4->execute(array()); // execute the query with parameter $code...
                                $rowcount = $sql4->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $row3 = $sql4->fetch(PDO::FETCH_ASSOC);
                                $payment = $row3['totalpay'];
                                $tot_pay = $tot_pay + $row3['totalpay'];
                                
                                echo number_format($row3['totalpay']).".00";
                               $tot_pay=$tot_pay+$row3['totalpay'];
                                 }
                                 else{
                                     echo "0";
                                 }
                                 
                       $sql4Date = $conn->prepare("SELECT * FROM `tbl_payment` WHERE pay_ref_no = '".$DetailT['ten_ref_no']."' AND DATE_FORMAT(rent_date,'%Y-%m')='$monthly' ");
                        $sql4Date->execute(array());
                        $row3Date = $sql4Date->fetch(PDO::FETCH_ASSOC);
                        
                        
                     ?>  
                   
               </td>
               <td><?php  echo number_format($row1['totalinv']-$row3['totalpay']).".00"; ?></td>
               <td><?php  echo $row3Date['pay_due_date'] ?></td>
               <td><?php echo $row3Date['pay_description']  ?></td>
              
              </tr>
              <?php
                 
             }
             ?>
    <tr >  
              <td colspan="3">Total</td>
              <td align="left"><?php $tot_paymon2=$tot_paymon2+$tot_paymon; echo number_format($tot_paymon).".00";?></td>
              <td align="left"><?php //echo number_format($tot_inv_yes).".00";?></td>
              <td align="left"><?php $tot_inv_no2=$tot_inv_no2+$tot_inv_no; echo number_format($tot_inv_no).".00";?></td>
              <td align="left"><?php $tot_pay2=$tot_pay2+$tot_pay; echo number_format($tot_pay).".00"; ?></td>
              <td align="left"><?php $diffr=$tot_inv_no-$tot_pay; $diffr2=$diffr2+$diffr; echo number_format($diffr).".00"; ?></td>
              <!--<td align="left"><?php echo number_format($diff_total).".00"; ?></td>-->
              <td></td>
              <td></td>
            </tr>
        <?php
             }
         ?>

		 </tbody>
		  <tfoot>
            <tr >  
              <td colspan="3"><h4>G.Total</h4></td>
              <td align="left"><strong><?php echo number_format($tot_paymon2).".00";?></strong></td>
              <td align="left"><strong><?php //echo number_format($tot_inv_yes2).".00";?></strong></td>
              <td align="left"><strong><?php echo number_format($tot_inv_no2).".00";?></strong></td>
              <td align="left"><strong><?php echo number_format($tot_pay2).".00"; ?></strong></td>
              <td align="left"><strong><?php echo number_format($diffr2).".00"; ?></strong></td>
              <!--<td align="left"><?php echo number_format($diff_total).".00"; ?></td>-->
              <td></td>
            </tr>
          </tfoot>
	    </table>
    </div>
 </div>