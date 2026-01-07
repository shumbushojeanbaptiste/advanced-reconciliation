<?php // Add tenant payment
 
 if(ISSET($_POST['add_tenant_payment'])){
         try{
             $payment_id = $_fetch['pay_id'];
             $ten_ref = $_POST['tenant_ref'];
             $bank = $_POST['bank'];
             $slip_no = $_POST['slip_no'];
             $payment_fees = $_POST['payment_fees'];
             $payment_desc = $_POST['comment'];
             $date = $_POST['date'];
             $todaysdate = date("Y-m-d H:i:s");
             
            // generate payment
            
            $year =  date('Y');
            $year = substr( $year, -2);
            $li_qu = $db->prepare("SELECT * FROM tbl_payment ORDER BY pay_id DESC LIMIT 1");
            $li_qu->execute();
            $fli = $li_qu->fetch();
            $li = str_pad($fli['pay_id'], 6, "0", STR_PAD_LEFT);
            $new_pay_id = $year.'-'.$li;
 
             $sql = "INSERT INTO `tbl_payment` (`trans_code`, `pay_ref_no`,`bank_id`,`slip_no`,`pay_due_date`,`pay_amount`,`pay_description`,`pay_trm`,`pay_user`)
             VALUES ('$new_pay_id', '$ten_ref','$bank','$slip_no','$date','$payment_fees','$payment_desc','$todaysdate','$company')";
             $conn->exec($sql);
             $lastId = $conn->lastInsertId();
             
             $msg = " Successfully Added Payment!";
             
             echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=tenant-payments">';
             
         }catch(PDOException $e){
             $msg = $e->getMessage();
         }
         
     }
  
 ?>
 
    <!--update Invoice-->
     <?php
     if(ISSET($_POST['update_payment'])){
         try{
             $pay_id = $_POST['pay_id'];
             $slip_no = $_POST['slip_no'];
             $amount = $_POST['pay_amount'];
             $date = $_POST['pay_date'];
             $pay_comment = $_POST['pay_descr'];
     
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             
             $sql = "UPDATE `tbl_payment` SET `slip_no` = '$slip_no', `pay_due_date` = '$date', `pay_amount` = '$amount', `pay_description` = '$pay_comment' WHERE `pay_id` = '$pay_id'";
             $conn->exec($sql);
   
             $msg = "Updated Successfully";
             echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=tenant-payments">';
             
         }catch(PDOException $e){
             echo $e->getMessage();
         }
     }
     ?>   
     <!-- delete PACKAGE-->
         <?php
             if(ISSET($_GET['del_pay_id'])){
             $pay_id = $_GET['del_pay_id'];
             $sql = $conn->prepare("DELETE from `tbl_payment` WHERE `pay_id`='$pay_id' ");
             $sql->execute();
             
             $msge = "Deleted Successfully!";
             echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=tenant-payments">';
         }
 ?>

<style>
    .modal { display:none; }
    .modal-title {color: #111;}
    td {
        font-size: 12px;
    }
</style>
<div class="span9">
  <div class="content">

        <?php
            if(!empty($msg)) {?>
    	<div class="alert alert-success">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Well done!</strong> <?php echo $msg; ?> 
    	</div>
        <?php
          }
           else if(!empty($msge)) {
        ?>
    	<div class="alert alert-error">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Oh snap!</strong>  <?php echo $msge; ?> 
    	</div>
        <?php
           }
        ?>
 <div class="module">
      <ul class="nav nav-tabs">
    <li class="active"><a href="#" data-toggle="tab">Single Payment Report</a></li>
</ul>
     <div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
        <br><br>
         <?php include 'new-slp.php'; ?>
         </div>
       
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->
 
 
</div><!--/.module-->

</div><!--/.content-->
</div><!--/.span9-->


<!--modal for payment update-->
<script>
    $('#myTable').DataTable( {
    responsive: true
} );
    
</script>

<script type="application/javascript">
var alphaExp = /^[a-zA-Z]+$/; //Variable to validate only alphabets
var alphaspaceExp = /^[a-zA-Z\s]+$/; //Variable to validate only alphabets and space
var numericExpression = /^[0-9]+$/; //Variable to validate only numbers
var alphanumericExp = /^[0-9a-zA-Z]+$/; //Variable to validate numbers and alphabets
var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/; //Variable to validate Email ID 

function validateform()
{
	if(document.frmPayRecord.tenant_ref.value == "")
	{
		alert("Tenant name should not be empty..");
		document.frmPayRecord.tenant_ref.focus();
		return false;
	}
	else if(document.frmPayRecord.bank.value == "")
	{
		alert("Bank should not be empty..");
		document.frmPayRecord.bank.focus();
		return false;
	}
	else if(document.frmPayRecord.bank.value == "")
	{
		alert("Bank time should not be empty..");
		document.frmPayRecord.bank.focus();
		return false;
	}
	else if(document.frmPayRecord.slip_no.value == "")
	{
		alert("Bank Slip should not be empty..");
		document.frmPayRecord.slip_no.focus();
		return false;
	}
	else if(document.frmPayRecord.amount.value == "")
	{
		alert("Amount number should not be empty..");
		document.frmPayRecord.amount.focus();
		return false;
	}
	else if(!document.frmPayRecord.amount.value.match(numericExpression))
	{
		alert("Amount number not valid..");
		document.frmPayRecord.amount.focus();
		return false;
	}
	else if(document.frmPayRecord.comment.value == "")
	{
		alert("Comment should not be empty..");
		document.frmPayRecord.comment.focus();
		return false;
	}
	
	else if(document.frmPayRecord.date.value == "")
	{
		alert("DATE should not be empty..");
		document.frmPayRecord.date.focus();
		return false;
	}

	else
	{
		return true;
	}
}
</script>

