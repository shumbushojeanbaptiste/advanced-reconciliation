<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=general_report.xls");
?>
<?php

 require_once ("../include/initialize.php");



      $company=$_REQUEST['company'];
      $date_from=$_REQUEST['date_from'];
      $date_to=$_REQUEST['date_to'];
      
?>

  <div class="table-responsive">
	<table border="1" class="table datatables table-hover" id="dataTable-1">
		
		<thead>
		      <tr>
		        <th colspan="7">Payment <br>
		        Date From: <?php echo $date_from; ?><br>
		        To: <?php echo $date_to; ?>
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
                 WHERE tbl_payment.pay_user = '".$company."' AND ( DATE_FORMAT(pay_due_date,'%Y-%m-%d') BETWEEN '".$date_from."' and '".$date_to."') AND ten_staff='".$company."' AND tbl_payment.status!=0 order by pay_due_date asc");
                 $sql->execute();
                 while($fetch = $sql->fetch()){
                 $slip_No++;
                 
                 $tot_Pay = $tot_Pay+$fetch['pay_amount'];
                 
                  $date100 = $fetch['pay_due_date'];
                  
                    $li_quIPInvoiceCC = $db->prepare("SELECT  * FROM   tbl_tenants where ten_ref_no ='".$fetch['pay_ref_no']."' ");
            $li_quIPInvoiceCC->execute(); 
            $InvoiceCC=$li_quIPInvoiceCC->fetch();
                 
                 		$currency_id=$InvoiceCC['currency_id'];
    		$li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
            $li_quIPaidNMoney->execute(); 
            $newDMoney=$li_quIPaidNMoney->fetch();
            $converted1=$newDMoney['intoR'];
  $pre_date= date('d-m-Y', strtotime($date100));
             ?>
		 <tr class="odd gradeX">
		         <td><strong><?php echo $slip_No;?></strong></td>
                 <td><?php echo $pre_date; ?></td>
                 <td><?php echo $fetch['trans_code']; ?></td>
                 <td><?php echo $fetch['pay_ref_no']; ?></td>
                 <td><?php echo $fetch['te_names']; ?></td>
                 <td><?php echo $fetch['bank_name']; ?></td>
                 <td><?php echo $fetch['slip_no']; ?></td>
                 <td><?php echo $fetch['pay_description']; ?></td>
                 <td><?php echo $fetch['pay_amount']/$fetch['c_value']; ?></td>
             </tr>
             <?php } ?>
        </div>