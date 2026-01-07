<?php
 require_once ("../include/initialize.php");
 
 /**
  * 
  * get dates from form
  */
    if($side_value ==2){
    $monthly =$_REQUEST['monthly'];
    $monthCons =0;
        ?>
   
    <div class="table-responsive">
   <div class="module-body table">
		<table class="table table-hover" width="100%">
		<thead>
		     <tr>
		        <th colspan="2"><?php echo $cmp_full; ?> Monthly Consume Report<br>
		       Month: <?php   $monthly22= date('M',strtotime($monthly)); echo $monthly22; ?><br>
		       
		        </th>
		        <th  colspan="2"></th>
		        <th  colspan="2">
		            <a href="export_consume.php?monthly=<?php echo $monthly; ?>">
                        <!--<button class="btn btn-success pull-left" style="margin:7px;">Export Excel</button>-->
                    </a>
		        </th>
		    </tr>
 			<tr> <th>Serial <?php // echo $monthly;?></th>
                 <th><b>Room</b> <?php //echo $monthly33;?></th>
                 <th><b>Tenant name</b></th>
                 <th><b>Previous Reading</b></th>
                 <th><b>Current Reading</b></th>
                 <th><b>Consumption Quantity</b></th>
			</tr>
		</thead>
		<tbody>
   
		<?php
		
		/**
		 * 
		 * useful variables
		 */
		 
		$totaLTopay=0;
		$counter=0;
		$time =$monthly33."-01";
		$onlyMonth =substr($monthly33,5);
		$onlyYear =substr($monthly33,0,4);
// 		echo date($onlyYear);
		$row1;
		$tot_inv_yes;
		
		/**
		 * fetching required data  
		 * and year(next_date) ='".$onlyYear."'
		 * 
		 */
		 
    $consumeMonth = $db->prepare("SELECT *FROM tbl_consume where DATE_FORMAT(cons_month, '%Y-%m') ='".$monthly."' and co_id ='".$company."'  ");
    $consumeMonth->execute();
// and qty=0
    while($theMonth = $consumeMonth->fetch()){
        $counter++;
        $monthCons=0;
        
        $consRoom = $db->prepare("select *from tbl_assign where  room_id ='".$theMonth['room_id']."' and assgn_status = :status");
        
        $consRoom->bindValue(':status', 1, PDO::PARAM_INT);
        $consRoom->execute();
        $theConsMonth =$consRoom->fetch();
        
        $consRoomname = $db->prepare("select *from tbl_rooms where  room_id ='".$theConsMonth['room_id']."'");
        $consRoomname->execute();
        $theConsMonthname =$consRoomname->fetch();
        
        $consTenant = $db->prepare("select *from tbl_tenants where ten_id ='".$theConsMonth['tent_id']."'");
        $consTenant->execute();
        $theConTenant =$consTenant->fetch();   
        
        $prevMonth = $db->prepare("SELECT *FROM tbl_consume where room_id ='".$theMonth['room_id']."' AND DATE_FORMAT(cons_month, '%Y-%m') <'".$monthly."'  and co_id ='".$company."' ORDER BY cons_month desc ");
        $prevMonth->execute();
        $thePrevious =$prevMonth->fetch();
        
        $monthCons += ($theMonth['index_number']-$thePrevious['index_number']);
        $DIFF=$theMonth['index_number']-$thePrevious['index_number'];
    
//   $inv_insert_qu77 = $db->prepare("UPDATE  tbl_consume SET qty='".$DIFF."' where cons_id='".$theMonth['cons_id']."' ");
//                         $inv_insert_qu77->execute();  
    ?>
		    <tr class="odd gradeX">
    		    <td><?php  echo $counter;?></td>
                <td><?php  echo  $theConsMonthname['room_no']."#<b>".$theMonth['room_id']."</b>" ?>  </td>
                <td><?php  echo  $theConTenant['te_names'];?></td>
                <td><?php  echo  $thePrevious['index_number'];?></td>
                <td><?php  echo  $theMonth['index_number'];?></td>
                <td><strong><?php  echo $theMonth['qty'];?></strong></td>
            </tr>
            <?php
    }
    ?>
     	 </tbody>
	    </table>
    </div>
 </div>
 <?php
            
    }
    else{
    $monthly =$year_id."-".$_REQUEST['monthly'];
    $monthly33 =$_REQUEST['monthly'];
    $mtn=$_REQUEST['monthly'];
?>
    <a href="export_MonthlyDetail_rpt.php?monthly=<?php echo $monthly; ?>&date_to=<?php echo $date_to; ?>&cpny=<?php echo $cmp_full; ?>&year_id=<?php echo $year_id  ?>">
    <!--<button class="btn btn-success pull-left" style="margin:7px;">Export Excel</button>-->
    </a>
    <div class="table-responsive">
   <div class="module-body table">
		<table class="table datatables" width="100%" id="dataTable-1">
		<thead>
		     <tr>
		        <th colspan="8"><?php echo $cmp_full; ?> Monthly Report<br>
		       Month: <?php   $monthly22= date('M',strtotime($monthly)); echo $monthly22; ?><br>
		       
		        </th>
		    </tr>
			<tr> <th>Serial</th>
                 <th><b></b>Tenant name <?php //echo $monthly33;?></th>
                 <th><b>Invoice Fees</b></th>
                 <th><b>Invoiced Months</b></th>
                 <th><b>Payment Date</b></th>
			</tr>
		</thead>
		<tbody>
   
		<?php
		
		/**
		 * 
		 * useful variables
		 */
		 
		$totaLTopay=0;
		$counter=0;
		$time =$monthly33."-01";
		$onlyMonth =substr($monthly33,5);
		$onlyYear =substr($monthly33,0,4);
// 		echo date($onlyYear);
		$row1;
		$tot_inv_yes;
		
		/**
		 * fetching required data  
		 * and year(next_date) ='".$onlyYear."'
		 * 
		 */
		 
    $sql = $db->prepare("SELECT *FROM `tbl_next_pay` WHERE month(next_date) = '".$onlyMonth."' and comp_id ='".$company."'");
    $sql->execute();

    while($fetch = $sql->fetch()){
        $counter++;
        $totaLTopay=$totaLTopay+$fetch['invoice_fees'];
        $sqlInvoice = $db->prepare("select *from tbl_tenants where  ten_id ='".$fetch['tenant_id']."'");
        $sqlInvoice->execute();
        $theTenants =$sqlInvoice->fetch();
        
        // contract period
        // $totaLTopay=$totaLTopay+$fetch['invoice_fees'];
        $contractMonths = $db->prepare("select *from tbl_ten_contracts where  tenant_id ='".$fetch['tenant_id']."'");
        $contractMonths->execute();
        $theContractMonth =$contractMonths->fetch(); 
        
        $date = substr($fetch['next_date'],5);
        
        $month =substr($date,3);
        $day =substr($date,0,2);
        $dateToPrint =$month."/".$day;
        $nextDate =str_replace("-", "/", $date);
    

    ?>
		    <tr class="odd gradeX">
		    <td><?php  echo $counter;?></td>
            <td><?php  echo  $theTenants['te_names'];?></td>
             
             <?php 
             /**
              * 
              * tenants invoices
              *  and year(invoice_date) ='".$onlyYear."'
              */
              ?>
              
              
            <?php
                $invoiceFees = $db->prepare("select *from tbl_invoice where inv_ref_no ='".$theTenants['ten_ref_no']."' 
                and  month(invoice_date) = '".$onlyMonth."'");
                $invoiceFees->execute();
                $theInvoiceFees =$invoiceFees->fetch();
                
                /**
                 * update the fees
                 * 
                 */
                 $totaLTopay += $theInvoiceFees['invoice_fees'];
             ?>
             <td><?php  echo  number_format($theInvoiceFees['invoice_fees']);?></td>
             <td><?php  echo  $theContractMonth['m_interval'];?></td>
             <td><?php  echo $dateToPrint;?></td>
             </tr>
             <tr >  
              
               <?php
               //looping to retrieve all rooms
               
                   }
                   ?>
            <td colspan="2">Total</td>
             <td align="left"><b><?php echo number_format($totaLTopay);?></b></td>
             <td></td>
            </tr>
     	 </tbody>
	    </table>
    </div>
 </div>
 <?php
    }     
    ?>