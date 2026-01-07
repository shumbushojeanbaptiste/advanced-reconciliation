<?php
 require_once ("../include/initialize.php");

// get dates from form

$year_id=$_REQUEST['year_id'];
$date_from = $_REQUEST['date_from'];
$date_to = $_REQUEST['date_to'];
$monthly =$year_id."-".$_REQUEST['monthly'];
$monthly33 =$_REQUEST['monthly'];
$mtn=$_REQUEST['monthly'];
//echo $monthly33;
?>
      <a href="export_MonthlyDetail_rpt.php?monthly=<?php echo $monthly; ?>&date_to=<?php echo $date_to; ?>&cpny=<?php echo $cmp_full; ?>&year_id=<?php echo $year_id  ?>">
    <button class="btn btn-success pull-left" style="margin:7px;">Export Excel</button>
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
                 <th>Date </th>
                 <th>Invoice Fees</th>
                 <th>Payment</th>
                 <th>Balance</th>
                 
			</tr>
		</thead>
		<tbody>
   
		<?php
		
		//total
		$tenantTotal =0;
		$payTotal=0;
		$balanceTotal=0;
		$balanceRowTotal=0;
		$balanceRow =0;
		$monthlyFeesTotal=0;
		$monthlyPayTotal=0;
		$monthlyBalTotal=0;
		$
		$aa=1;
     // fetch 
    $sql = $db->prepare(" select *from tbl_tenants 
    inner join tbl_invoice on tbl_invoice.inv_ref_no=tbl_tenants.ten_ref_no 
    where tbl_invoice.cpny_ID='".$company."' and tbl_invoice.invoice_fees > 0 group by te_names");
            $sql->execute();
             while($Detail = $sql->fetch()) {
                  $tenantTotal=0;
                  $payTotal=0;
                  $balanceRow =0;
                  $balanceRowTotal=0;
                  $total =0;
                ?>
                <tr><td><?php echo $Detail['te_names'];?></td></tr>
                
                <?php
                $sqlDates = $db->prepare("select *from tbl_invoice 
                where invoice_date between '".$date_from."' and '".$date_to."'  and tbl_invoice.inv_ref_no ='".$Detail['ten_ref_no']."' ");
                $sqlDates->execute();
                while($invoiceDates =$sqlDates->fetch()){
                    $tenantTotal=$tenantTotal+$invoiceDates['invoice_fees'];
                    $balanceRowTotal=$balanceRowTotal+$balanceRow;
                    //$payTotal=$payTotal+$invoiceDates['pay_amount'];
                    $paymentByTenants=$db->prepare("select sum(pay_amount) as totalinv from tbl_payment where tbl_payment.pay_ref_no ='".$invoiceDates['inv_ref_no']."' ");
                    $paymentByTenants->execute(array()); // execute the query with parameter $code...
                                $rowcount = $paymentByTenants->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $payed = $paymentByTenants->fetch(PDO::FETCH_ASSOC);
                                $payTotal = $payTotal + $payed['totalinv'];
                                $balanceRow =$invoiceDates['invoice_fees']-$payed['totalinv'];
                                $balanceRowTotal=$balanceRowTotal+$balanceRow;
                                //$totinv2 = $row1['totalinv'];
                                //echo number_format($row1['totalinv']).".00";
                                 }
                                 else{
                                     echo "No Rows in resultset";
                                 }
                    
                    
                    
                    
                    
                    ?>
                
                <tr>
                    <td></td>
                    <td colspan="1"><?php echo $invoiceDates['invoice_date'];?></td>
                    <td><? echo number_format($invoiceDates['invoice_fees']);?></td>
                    <td><? echo number_format($payed['totalinv']);?></td>
                    <td><? echo number_format($balanceRow) ;?></td>
                    </tr>
                <?php
                // while($paymentByT =$paymentByTenants->fetch()){
                        
                //     }
                    ?>
                    
                <?
                }
                ?>
            <tr class="odd gradeX">  
              <td colspan="2" style="font-size:10px; font-family:sans-serif"><i><b><?echo $Detail['te_names']." Details"?></b></i></td>
              <td align="left" style="font-size:15px"><b><?php  echo number_format($tenantTotal);?></b></td> 
              <td style="font-size:15px"><b><?php echo number_format($payTotal);?></b></td>
              <td style="font-size:15px"><b>
                  <?php 
                //   if($balanceRowTotal<0){
                //       $balR=abs($balanceRowTotal);
                //       echo number_format("(.$balR.)");
                //   }
                  echo number_format($balanceRowTotal) ;
                  ?>
                  </b></td></td>
              
            </tr>
            <?
            $monthlyFeesTotal=$monthlyFeesTotal+$tenantTotal;
		    $monthlyPayTotal=$monthlyPayTotal+$payTotal;
		    $monthlyBalTotal=$monthlyFeesTotal-$monthlyPayTotal;
            }
            ?>
            <tr class="odd gradeX">  
              <td colspan="2" style="font-size:15px; font-family:sans-serif"><i><b>G.Total</b></i></td>
              <td align="left" style="font-size:15px; font-family:sans-serif" ><b><?php echo number_format($monthlyFeesTotal);?></b></td> 
              <td align="left" style="font-size:15px; font-family:sans-serif"><b><?php echo number_format($monthlyPayTotal);?></b></td> 
              <td align="left" style="font-size:15px; font-family:sans-serif"><b><?php echo number_format($monthlyBalTotal);?></b></td> 
              <td></td>
              <td></td>
              
            </tr>
		 </tbody>
		  <tfoot>
          </tfoot>
	    </table>
    </div>
 </div>