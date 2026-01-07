<?php
 require_once ("../include/initialize.php");
 
 /**
  * 
  * get dates from form
  */
   
    
    $monthly =$year_id."-".$_REQUEST['monthly'];
    $monthly33 =$_REQUEST['monthly'];
    $mtn=$_REQUEST['monthly'];
?>
    
    <div class="table-responsive">
   <div class="module-body table">
		<table class="table datatables" width="100%" id="dataTable-1">
		<thead>
		     <tr>
		        <th colspan="3"><?php echo $cmp_full; ?> Monthly Report<br>
		       Month: <?php   $monthly22= date('F Y',strtotime($monthly33)); echo $monthly22; ?><br>
		       
		        </th>
		        <td colspan="4"></td>
		        <td colspan="3">
		            <a href="export_RecoverList.php?monthly=<?php echo $monthly33; ?>" align="right">
    <button class="btn btn-success pull-left" style="margin:7px;">Export Excel</button>
    </a>
		        </td>
		    </tr>
			<tr> <th>#</th>
			       <th>SHOP No</th>
                 <th><b></b>Tenant name <?php //echo $monthly33;?></th>
                  <th><b>Contract date</b></th>
                 <th><b>Months Fees</b></th>
                 <th><b>Interval Months</b></th>
                 <th><b>Total Fees</b></th>
                 <th><b>Recovered</b></th>
                 <th><b>Balance</b></th>
                 <th><b>Payment date</b></th>
                
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
		
		$actualy=0;
		$totarec=0;
		$recoveredList=0;
		$totaLbalanced=0;
		/**
		 * fetching required data  
		 * and year(next_date) ='".$onlyYear."'
		 * 
		 */
		 
    $sql = $db->prepare("SELECT *FROM `tbl_next_pay` WHERE month(next_date) = '".$onlyMonth."' and comp_id ='".$company."' 
    and contract_id in (select contr_id from tbl_ten_contracts where cpn_id='".$company."' and cntr_status=1)");
    $sql->execute();

    while($fetch = $sql->fetch()){
        $counter++;
        $totaLTopay=$totaLTopay+$fetch['invoice_fees'];
        $sqlInvoice = $db->prepare("select *from tbl_tenants where  ten_id ='".$fetch['tenant_id']."'");
        $sqlInvoice->execute();
        $theTenants =$sqlInvoice->fetch();
        $next_date=$fetch['next_date'];
        ##Romm
        $sql12room = $conn->prepare("SELECT room_id  FROM tbl_assign
        WHERE tbl_assign.tent_id = '".$fetch['tenant_id']."' and assgn_status IN (1,0) and    room_id in (select room_id from tbl_c_room where contr_id='".$fetch['contract_id']."') ");
        $sql12room->execute(array()); 
        $rowcountroom = $sql12room->rowCount();
        $row12room = $sql12room->fetch(PDO::FETCH_ASSOC);
        $room_id=$row12room['room_id'];
        
        $sql12roomName = $conn->prepare("SELECT room_no  FROM tbl_rooms
        WHERE tbl_rooms.room_id = '".$room_id."' ");
        $sql12roomName->execute(array()); 
        $rowcountroomName = $sql12roomName->rowCount();
        $row12roomName = $sql12roomName->fetch(PDO::FETCH_ASSOC);
        
        ##end room
        
        ### actuary AMT      
              
        
         $sql12 = $conn->prepare("SELECT SUM(amount) AS total FROM tbl_assign
                     
                                
                     WHERE tbl_assign.tent_id = '".$fetch['tenant_id']."' and assgn_status =1 and  room_id in (select room_id from tbl_c_room where contr_id='".$fetch['contract_id']."') ");
                        $sql12->execute(array()); 
                        $rowcount = $sql12->rowCount();
                    //   room_id in (select room_id from tbl_c_room where contr_id='".."')
                         $row12 = $sql12->fetch(PDO::FETCH_ASSOC);
                         $tot_assign =$row12['total'];
                       
                                 
                        $sql13 = $conn->prepare("SELECT SUM(discount) AS disc FROM tbl_assign
                        INNER JOIN tbl_rooms ON tbl_rooms.room_id=tbl_assign.room_id  
                        WHERE tent_id = '".$fetch['tenant_id']."' and tbl_assign.room_id in (select room_id from tbl_c_room where contr_id='".$fetch['contract_id']."' ) ");
                        $sql13->execute(array()); 
                        $rowcount13 = $sql13->rowCount();
                       
                       $row13 = $sql13->fetch(PDO::FETCH_ASSOC);
                         $tot_disc = $row13['disc'];
                        
                        
                       $paymon = $row12['total'] - $row13['disc'];
                       
                       
        
        
        ## end of ACtuary amt
        
        
        // contract period
        // $totaLTopay=$totaLTopay+$fetch['invoice_fees'];
        // contract_id in (select contr_id
        // and contr_id='".$fetch['contract_id']."'

        $contractMonths = $db->prepare("select *from tbl_ten_contracts where  tenant_id ='".$fetch['tenant_id']."' and contr_id='".$fetch['contract_id']."' ");
        $contractMonths->execute();
        $theContractMonth =$contractMonths->fetch(); 
        
        $date =$fetch['next_date'];
        $date1 =$theContractMonth['c_start_date'];
        
        $month =substr($date,7,8);
        $day =substr($date1,9);
        $dateToPrint =$month."/".$day;
        $nextDate =str_replace("-", "/", $date);
    

    ?>
		    <tr class="odd gradeX">
		    <td><?php  echo $counter;?></td>
		    <td><?php echo $row12roomName['room_no'] ?></td>
            <td><?php  echo  $theTenants['te_names'];?></td>
             
             <?php 
             /**
              * 
              * tenants invoices
              *  and year(invoice_date) ='".$onlyYear."'
              */
              ?>
              
              
            <?php
                $invoiceFees = $db->prepare("select * from tbl_invoice where inv_ref_no ='".$theTenants['ten_ref_no']."' 
                and  month(invoice_date) = '".$onlyMonth."' and inv_room_id='".$room_id."'");
                $invoiceFees->execute();
                $theInvoiceFees =$invoiceFees->fetch();
                
                
                $payFess = $db->prepare("select sum(pay_amount) as payfees from tbl_payment where pay_ref_no ='".$theTenants['ten_ref_no']."' 
                and  month(rent_date) = '".$onlyMonth."'");
                $payFess->execute();
                $thepayFess =$payFess->fetch();
               
                
                $dataInvo =$invoiceFees->rowCount();
                if($dataInvo>0)
                {
                
                $revFees=$theInvoiceFees['invoice_fees']; 
                $paymon=$theInvoiceFees['invoice_fees']/$theContractMonth['m_interval'];
                }
                else{
                 $revFees= $paymon*$theContractMonth['m_interval']; 
                 $paymon=$paymon;
                }
                
                /**
                 * update the fees
                 * 
                 */
                 $actualy = $actualy+$paymon;
                 $totarec=$totarec+$revFees;
                 $recoveredList=$recoveredList+$thepayFess['payfees'];
                 
                 
                 $balanced=$revFees-$thepayFess['payfees'];
                 if($balanced>=0){
                    $balanced=$balanced; $bl=$balanced;
                 }
                 else{
                     $balanced=$balanced*-1; $bl=$balanced*-1;
                     $balanced="(".$balanced.")";
                 }
                 
                 $totaLbalanced=$totaLbalanced+$bl;
                 
                 $originalDate =$date1;
                 $paydate=substr($date1,0,2);
                 $newDate = date("d/m/Y", strtotime($originalDate));
                 $paydate=substr($newDate,0,2); 
             ?>
             <!--<td><?php  echo $dateToPrint;?></td>-->
             <td><?php  echo $newDate;?></td>
             <td><?php      echo number_format($paymon); ?></td>
             <td><?php  echo  $theContractMonth['m_interval'];?></td>
             <td><?php  echo  number_format($revFees);?></td>
             <td><?php  echo  number_format($thepayFess['payfees']);?></td>
             <td><?php  echo  number_format($balanced);?></td>
             <td>
              <?php
              
            //   echo $monthly33."-".$paydate;
            $newM=$monthly33."-".$paydate;
               echo $newDate2 = date("d/m/Y", strtotime($newM));
              ?>
                 
                 
             </td>
             
             </tr>
            
              
               <?php
               //looping to retrieve all rooms
               
                   }
                   ?>
                      <tr >
            <td colspan="3">Total</td>
            <td align="left"></td>
             <td align="left"><b><?php echo number_format($actualy);?></b></td>
            <td align="left"></td>
            <td align="left"><b><?php echo number_format($totarec);?></b></td>
            <td align="left"><b><?php echo number_format($recoveredList);?></b></td>
            <td align="left"><b><?php echo number_format($totaLbalanced);?></b></td>
            <th></th>
            </tr>
     	 </tbody>
	    </table>
    </div>
 </div>
 <?php
    
    ?>