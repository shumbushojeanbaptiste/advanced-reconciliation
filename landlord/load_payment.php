      
      <?php require_once ("../include/initialize.php");  
      $company=$_REQUEST['company'];
      $date_from=$_REQUEST['date_from'];
      $date_to=$_REQUEST['date_to'];
      
      ?>
       <a href="export_all_payment.php?date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>&company=<?php echo $company  ?>">
    <button class="btn btn-success pull-rigth" style="margin:7px;">Export Excel</button>
    </a>
        <div class="table-responsive">
	<table class="table datatables table-hover" id="dataTable-1">
		
		<thead>
		      <tr>
		        <th colspan="9">Payment <br>
		        Date From: <?php echo $date_from; ?><br>
		        Date To: <?php echo $date_to; ?>
		        </th>
		    </tr>
			<tr>
			    <th>No.</th>
                 <th>Date</th>
			     <th>#PayNo</th>
                 <th>Ref No</th>
                 <th>Tenant</th>
                 <th>Bank Name</th>
                 <th>Slip No</th>
                 <th>Description</th>
                 <th>Amount</th>    
			</tr>
		</thead>
		<tbody>
			 <?php
			     $slip_No =0;
                 $sql = $conn->prepare("SELECT * FROM `tbl_payment` 
                 INNER JOIN `tbl_bank` ON tbl_payment.bank_id=tbl_bank.bank_id 
                 INNER JOIN tbl_tenants ON tbl_payment.pay_ref_no=tbl_tenants.ten_ref_no 
                 WHERE tbl_payment.pay_user = '".$company."' AND ( DATE_FORMAT(pay_due_date,'%Y-%m-%d') BETWEEN '".$date_from."' and '".$date_to."') AND tbl_payment.status=1 ");
                 $sql->execute();
                 $tot_paymon=0;
                 while($fetch = $sql->fetch()){
                 $slip_No++;
                 
                 
            //      $date_year = date('Y-m');
            
            $pay_due_date=$fetch['pay_due_date'];
            //  $sqlroNow=$db->prepare("SELECT SUM(pay_amount) as pytAMT FROM tbl_payment where pay_user = '".$company."' AND  pay_due_date >= '".$date_from."' and pay_due_date <='".$date_to."' and pay_ref_no='".$fetch['pay_ref_no']."' ");
           
            $sqlroNow=$db->prepare("SELECT SUM(pay_amount) as pytAMT FROM tbl_payment where pay_user = '".$company."' AND  pay_due_date = '".$pay_due_date."'  and pay_ref_no='".$fetch['pay_ref_no']."' ");
           
            $sqlroNow->execute();
            $sqlrowNow=$sqlroNow->fetch();
            // // echo $sqlrow['pay_amount'];
            // $tot_paymon=$tot_paymon+($sqlrow['pay_amount']);
            
            
            
                 
                 $tot_Pay = $tot_Pay+$sqlrowNow['pytAMT'];
                 
                 $date100 = $fetch['pay_due_date'];
                  $li_quIPInvoiceCC = $db->prepare("SELECT  * FROM   tbl_tenants where ten_ref_no ='".$fetch['pay_ref_no']."' ");
            $li_quIPInvoiceCC->execute(); 
            $InvoiceCC=$li_quIPInvoiceCC->fetch();
                 
                 		$currency_id=$InvoiceCC['currency_id'];
    		$li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
            $li_quIPaidNMoney->execute(); 
            $newDMoney=$li_quIPaidNMoney->fetch();
            $converted1=$newDMoney['intoR'];
                 
  $pre_date= date('l d/m/Y', strtotime($date100));
             ?>
		 <tr class="odd gradeX">
		         <td><strong><?php echo $slip_No;?></strong></td>
                 <td><?php echo $pre_date; ?></td>
                 <td><?php echo $fetch['trans_code']; ?></td>
                 <td><?php echo $fetch['pay_ref_no']; ?></td>
                 <td><?php echo $fetch['te_names']; ?></td>
                 <td><?php echo $fetch['bank_name']; ?></td>
                 <td><?php echo $fetch['slip_no']; ?></td>
                 <td><?php echo $fetch['pay_description'].""; ?></td>
                 <td><?php echo number_format($sqlrowNow['pytAMT']);$tot_paymon=$tot_paymon+($sqlrowNow['pytAMT']/$fetch['c_value'])?></td>
             </tr>
             
             <?php } ?>
             <tr>
             <td colspan="8">Total</td>
             <td align="left"><?php $tot_paymon2=$tot_paymon2+$tot_paymon; echo number_format($tot_paymon).".00";?></td>
             </tr>
        </div>