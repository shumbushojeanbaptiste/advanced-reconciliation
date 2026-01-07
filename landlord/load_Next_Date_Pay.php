<?php
 require_once ("../include/initialize.php");

// get dates from form

$year_id=$_REQUEST['year_id'];
$date_from = $_REQUEST['date_from'];
$monthly =$year_id."-".$_REQUEST['monthly'];
$monthly33 =$_REQUEST['monthly'];
$mtn=$_REQUEST['monthly'];
//echo $monthly33;
//echo Month($monthly33);
?>
      <a href="export_MonthlyDetail_rpt.php?monthly=<?php echo $monthly; ?>&date_to=<?php echo $date_to; ?>&cpny=<?php echo $cmp_full; ?>&year_id=<?php echo $year_id  ?>">
    <!--<button class="btn btn-success pull-left" style="margin:7px;">Export Excel</button>-->
    </a>
    <div class="table-responsive">
   <div class="module-body table">
		<table class="table table-hover" width="100%">
		<thead>
		     <tr>
		        <th colspan="8"><?php echo $cmp_full; ?> Monthly Report<br>
		       Month: <?php   $monthly22= date('M',strtotime($monthly)); echo $monthly22; ?><br>
		       
		        </th>
		    </tr>
			<tr> <th># </th>
                 <th><b></b>Tenant name</th>
                 <th><b>Monthly fees</b></th>
                 <th><b>Next Date</b></th>
			</tr>
		</thead>
		<tbody>
   
		<?php
		$totaLTopay=0;
		$aa=0;
		$time =$monthly33."-01";
		$time2 =substr($monthly33,5);
		echo date($time2);
		$row1;
		$tot_inv_yes;
     // fetching required data
    $sql = $db->prepare("SELECT *FROM `tbl_next_pay` 
    inner join tbl_ten_contracts on tbl_ten_contracts.tenant_id = tbl_next_pay.tenant_id
    inner join tbl_tenants on tbl_tenants.ten_id=tbl_ten_contracts.tenant_id
    WHERE (next_date) like '%".$time2."%' and tbl_next_pay.tenant_id =tbl_ten_contracts.tenant_id group by te_names order by next_date asc");
    //where DATE_FORMAT(invoice_date,'%Y-%m') ='".$monthly33."' AND tbl_invoice.cpny_ID ='".$company."' GROUP by tbl_next_pay.tenant_id
    $sql->execute();

    while($fetch = $sql->fetch()){
         $aa++;
         $totaLTopay=$totaLTopay+$fetch['invoice_fees'];
    $sqlInvoice = $db->prepare("select sum(invoice_fees) as totalinv from tbl_invoice 
                INNER JOIN tbl_tenants ON tbl_tenants.ten_ref_no=tbl_invoice.inv_ref_no
                INNER JOIN tbl_rooms on tbl_rooms.room_id=tbl_invoice.inv_room_id 
                where  company_id ='".$company."' and DATE_FORMAT(invoice_date,'%Y-%m' ) ='".$monthly33."' and tbl_invoice.inv_ref_no ='".$fetch['ten_ref_no']."'");
    $sqlInvoice->execute(array()); // execute the query with parameter $code...
                                $rowcount = $sqlInvoice->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $row1 = $sqlInvoice->fetch(PDO::FETCH_ASSOC);
                                $tot_inv_yes = $tot_inv_yes + $row1['totalinv'];
                                $totinv2 = $row1['totalinv'];
                                //echo number_format($row1['totalinv']).".00";
                                 }
                                 else{
                                     echo "0";
                                 }
    

    ?>
		  <tr class="odd gradeX">
		    <td><?php  echo $aa;?></td>
             <td><?php  echo  $fetch['te_names'];?></td>
             <td><?php  echo  $row1['totalinv'];?></td>
             <td><?php  echo  $fetch['next_date'];?></td>
             </tr>
             <tr >  
              
               <?php
               //looping to retrieve all rooms
               
                   }
                   ?>
            <td colspan="2">Total</td>
             <td align="left"><b><?php echo number_format($tot_inv_yes);?></b></td>
             <td></td>
            </tr>
	
    
    
    
     	 </tbody>
	    </table>
    </div>
 </div>