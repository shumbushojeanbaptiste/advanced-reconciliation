<?php
 require_once ("../include/initialize.php");
$tenant_ref=$_REQUEST['tenant_ref'];
//tenant
$sql1 = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_ref_no = $tenant_ref ");
$sql1->execute();
$fetch1 = $sql1->fetch();
$tenid1=$fetch1['ten_id'];

    //invoice amount
    $sqlinvo = $conn->prepare("SELECT sum(invoice_fees) as invoice_fees FROM `tbl_invoice` WHERE inv_ref_no='$tenant_ref' group by inv_ref_no");
    $sqlinvo->execute();
     $invoBalance=0;
     $invopyt=0;
    $fetchinvo = $sqlinvo->fetch();
   
    //paid amount invoice
    $sqlinvopyt = $conn->prepare("SELECT sum(pay_amount) as pay_amount FROM `tbl_payment` WHERE pay_ref_no='$tenant_ref' and status=1 group by pay_ref_no");
    $sqlinvopyt->execute();
    $fetchinvopyt = $sqlinvopyt->fetch();
    $invoBalance=($fetchinvo['invoice_fees']-$fetchinvopyt['pay_amount']);
?>
<div class="form-row">
        <div class="form-group col-md-2">
        </div>
        <div class="col-md-3 mb-3">
          <label for="simple-select2">Balance</label>
           <input type="text" name="blnce" id="blnce" class="form-control" placeholder="Balance Amount"  value='<?php echo number_format($invoBalance,0); ?>' readonly/>
           <input type="text" name="blncename" id="blnce" class="form-control" placeholder="Balance Amount"  value='<?php echo $invoBalance; ?>' style='display:none;'/>
       
        </div>
        <!-- room invoice -->
        <div class="col-md-3 mb-3">
          <label for="multi-select2">Amount</label>
          <input type="number" name="payment_fees" id="amount" class="form-control" placeholder="Amount"/>
        </div> <!-- form-group -->
      </div> <!-- form-row -->