
 <?php
 require_once ("../include/initialize.php");
	$date_year = $_REQUEST['year_id'];
	$company = $_REQUEST['company'];
	$date_mnth = $_REQUEST['month_Id'];
	$dte = $date_year."-".$date_mnth;

  $month=$dte;
    // $sql_us = $db->prepare("SELECT SUM(invoice_fees) as sm FROM `tbl_invoice` WHERE tbl_invoice.cpny_ID=$company AND DATE_FORMAT(invoice_date, '%Y-%m') ='".$dte."' AND inv_ref_no in (SELECT ten_ref_no FROM tbl_tenants GROUP BY ten_ref_no)");
    // $sql_us->execute();
    // $fetch_us = $sql_us->fetch();
    
    // $sql_us1 = $db->prepare('SELECT SUM(`pay_amount`) as sm FROM `tbl_payment` WHERE date_format(`pay_due_date`,"%Y-%m")="'.$dte.'" and `pay_user`="'.$company.'"  AND pay_ref_no in (SELECT ten_ref_no FROM tbl_tenants GROUP BY ten_ref_no)');
    // $sql_us1->execute();
    // $fetch_us1 = $sql_us1->fetch();

    $totalInvoiceStmt = $db->prepare("
    SELECT 
        COALESCE(SUM(invoice_fees), 0) AS total_invoice
    
        
    FROM tbl_invoice
    WHERE DATE_FORMAT(invoice_date, '%Y-%m') = :invoice_date
");
$totalInvoiceStmt->execute([':invoice_date' => $month]);
$totalInvoice = $totalInvoiceStmt->fetch(PDO::FETCH_ASSOC);

$total_invoice = $totalInvoice['total_invoice'];



$totalDepositStmt = $db->prepare("
    SELECT 
        COALESCE(SUM(dep_amount), 0) AS total_deposit
      
       
    FROM tbl_deposit
    WHERE DATE_FORMAT(due_to, '%Y-%m') = :deposit_date
");
$totalDepositStmt->execute([':deposit_date' => $month]);
$totalDeposit = $totalDepositStmt->fetch(PDO::FETCH_ASSOC);

$total_deposit = $totalDeposit['total_deposit'];


$totalPaymentStmt = $db->prepare("
    SELECT 
        COALESCE(SUM(pay_amount), 0) AS total_payment
        
      
    FROM tbl_payment
    WHERE DATE_FORMAT(invoice_date, '%Y-%m') = :payment_date
");
$totalPaymentStmt->execute([':payment_date' => $month]);
$totalPayment = $totalPaymentStmt->fetch(PDO::FETCH_ASSOC);

$total_payment = $totalPayment['total_payment'];





$unpaidBalance= $total_invoice-$total_payment;

?>
 <div class="card-body" id="">
  <table class="table table-hover table-borderless mt-n3 mb-n1">
    <thead>
      <tr>
        <th>NO</th>
        <th>Total (<b><?= date("M Y", strtotime($month)) ?></b>)</th>
        <th> Amount (Frw)</th>
      </tr>
    </thead>
    <tbody>
      <tr title="show Invoice details" data-toggle="modal" data-target="#modal-invodetails" onClick="invoice_details('<?php  echo $dte ?>','invoice')">
        <td>1</td>
        <th scope="col">Deposit (<b><?= date("M Y", strtotime($month)) ?></b>)</th>
        <td><?php echo number_format($total_deposit,2); ?></td>
        <td><span class="dot dot-lg bg-warning mr-2"></span></td>
      </tr>
      <tr title="show Invoice details" data-toggle="modal" data-target="#modal-invodetails" onClick="invoice_details('<?php  echo $dte ?>','invoice')">
        <td>2</td>
        <th scope="col">Invoice (<b><?= date("M Y", strtotime($month)) ?></b>)</th>
        <td><?php echo number_format($total_invoice,2); ?></td>
        <td><span class="dot dot-lg bg-warning mr-2"></span></td>
      </tr>
      <tr title="show payment details" data-toggle="modal" data-target="#modal-invodetails" onClick="invoice_details('<?php  echo $dte ?>','payment')">
        <td>3</td>
        <th scope="col">Paid invoice (<b><?= date("M Y", strtotime($month)) ?></b>)</th>
        <td><?php echo number_format($total_payment); ?></td>
        <td><span class="dot dot-lg bg-success mr-2"></span></td>
      </tr>
      <tr title="show balance details" data-toggle="modal" data-target="#modal-invodetails" onClick="invoice_details('<?php  echo $dte ?>','balance')">
        <td>4</td>
        <th scope="col">Balance (<b><?= date("M Y", strtotime($month)) ?></b>)</th>
        <td><?php if($unpaidBalance > 0 ){ echo number_format($unpaidBalance,2); ?></td>
        <td><span class="dot dot-lg bg-primary mr-2"></span></td> <?php }else{  ?> <span class="text-danger"><?php echo number_format(-$unpaidBalance,2);?></span> 
        <td><span class="dot dot-lg bg-danger mr-2"></span></td> <?php } ?>
      </tr>
    </tbody>
  </table>
</div> <!-- .card-body -->