<?php // Add tenant invoice
 
 if(ISSET($_POST['add_tenant_invoice'])){
         try{
             $t_id = $_POST['tenant_name'];
             $invoice_fees1 = $_POST['invoice_fees'];
             $inv_desc = $_POST['comment'];
             $roomid = $_POST['room_idd'];
             $date = $_POST['date'];
             $todaysdate = date("Y-m-d H:i:s");
             
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             $sql = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_id = '$t_id'");
             $sql->execute();
             $fetch = $sql->fetch();
             $tenant_ref_no = $fetch['ten_ref_no'];
             	$currency_id=$fetch['currency_id'];
             		  $li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
            $li_quIPaidNMoney->execute(); 
            $newDMoney=$li_quIPaidNMoney->fetch();
         $converted=$newDMoney['intoR'];
             $invoice_fees=$invoice_fees1*$converted;
            // $unit_value=
            // generate invoice
            
            $year =  date('Y');
            $year = substr( $year, -2);
            $li_qu = $db->prepare("SELECT * FROM tbl_invoice ORDER BY invoice_id DESC LIMIT 1");
            $li_qu->execute();
            $fli = $li_qu->fetch();
            $li = str_pad($fli['invoice_id'], 6, "0", STR_PAD_LEFT);
            $new_inv_id = $year.'-'.$li;
 
             $sql = "INSERT INTO `tbl_invoice`(`inv_ref_no`, `invoice_fees`,`invoice`,`invoice_date`,`inv_desc`,`cpny_ID`,`auto`,`inv_date`,`unit_value`,`done_by`,`inv_room_id`)
             VALUES ('$tenant_ref_no', '$invoice_fees','$new_inv_id','$date','$inv_desc','$company','no','$todaysdate','".$converted."', '".$_SESSION['USER_ID']."','".$roomid."')";
             $conn->exec($sql);
             $lastId = $conn->lastInsertId();
             
                   
          ?>
      <script>
              jQuery(function validation(){
        swal("Well done! ", "Invoice  Save.", "success", {
        button: "ok",
          });
        });
        </script>
  <?php
             
         }catch(PDOException $e){
             $msg = $e->getMessage();
         }
         
     }
  
 ?>
 
    <!--update Invoice-->
     <?php
     if(ISSET($_POST['update_invoice'])){
         try{
             $invoice_id = $_POST['invoice_id'];
             $amount1 = $_POST['invoice_amount'];
             $invoice_date = $_POST['invoice_date'];
             $invoice_comment = $_POST['invoice_comment'];
             
             	  $li_quIPInvoice = $db->prepare("SELECT  * FROM   tbl_invoice where invoice_id ='".$invoice_id."' ");
            $li_quIPInvoice->execute(); 
            $Invoice=$li_quIPInvoice->fetch();
            $reff=$Invoice['inv_ref_no'];
            
             $li_quIPInvoiceCC = $db->prepare("SELECT  * FROM   tbl_tenants where ten_ref_no ='".$reff."' ");
            $li_quIPInvoiceCC->execute(); 
            $InvoiceCC=$li_quIPInvoiceCC->fetch();
            // $reff=$Invoice['inv_ref_no'];
             
             	$currency_id=$InvoiceCC['currency_id'];
             		  $li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
            $li_quIPaidNMoney->execute(); 
            $newDMoney=$li_quIPaidNMoney->fetch();
         $converted=$newDMoney['intoR'];
             $invoice_fees=$invoice_fees1*$converted;
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             
              $amount = $amount1*$converted;
              
              
              
               $li_quIPchanged = $db->prepare("SELECT  * FROM   tbl_invoice where invoice_id ='".$invoice_id."' ");
            $li_quIPchanged->execute(); 
            $changed=$li_quIPchanged->fetch();
             $date1=date("Y-m-d");
             
             $sql12 = "INSERT INTO `tbl_changed_invo`(`amount`, `done_by`,`due`)
             VALUES ('".$changed['invoice_fees']."', '".$_SESSION['USER_ID']."','$date1' )";
             $conn->exec($sql12);
             
             $sql = "UPDATE `tbl_invoice` SET  `unit_value`='".$converted."', `invoice_fees` = '$amount', `invoice_date` = '$invoice_date', `inv_desc` = '$invoice_comment' WHERE `invoice_id` = '$invoice_id'";
             $conn->exec($sql);
 
                   ?>
      <script>
              jQuery(function validation(){
        swal("Well done! ", "Invoice  Changed.", "success", {
        button: "ok",
          });
        });
        </script>
  <?php
             echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=invoice">';
             
         }catch(PDOException $e){
             echo $e->getMessage();
         }
     }
     ?>   
 <!-- delete PACKAGE-->
     <?php
      if(ISSET($_GET['del_invoice_id'])){
         $invoice_id = $_GET['del_invoice_id'];
         
          $li_qu = $db->prepare("SELECT * FROM tbl_invoice where invoice_id='".$invoice_id."'");
            $li_qu->execute();
            $fli = $li_qu->fetch();
            
             $sql = "INSERT INTO `tbl_invoice_b`(`inv_ref_no`, `invoice_fees`,`invoice`,`invoice_date`,`inv_desc`,`cpny_ID`,`auto`,`inv_date`)
             VALUES ('".$fli['inv_ref_no']."', '".$fli['invoice_fees']."','".$fli['invoice']."','".$fli['invoice_date']."','".$fli['inv_desc']."','".$fli['cpny_ID']."',
             '".$fli['auto']."','".$fli['inv_date']."')";
             $conn->exec($sql);
         
         $sql = $conn->prepare("DELETE from `tbl_invoice` WHERE `invoice_id`='$invoice_id' ");
         $sql->execute();
         
         $msge = "Canceled Successfully!";
         echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=invoice">';
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
    		<strong>Done!</strong>  <?php echo $msge; ?> 
    	</div>
        <?php
           }

           $currentMonth= date("Y-m-d");
        ?>
         <ul class="nav nav-tabs">
    <li class="active"><a href="#view_inv" data-toggle="tab">Special Invoice Management(s)</a></celi>
</ul>
        <div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
        <br><br>
      <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo date("F Y", strtotime($currentMonth));   ?> | Invoice</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">New Invoice Record</a>
        </li>
      </ul>
  <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"> 
       <div class="table-responsive">
   <div class="module-body table">
	 <table class="table datatables table-hover" id="dataTable-1">
		
		 <thead>
			<tr>
			     <th>Invoice No</th>
                 <th>Ref No</th>
                 <th>Tenant</th>
                 <th>Amount</th>
                 <th>Invoice date</th>
                 <th>Comment</th>
                 <th>Action</th>
			</tr>
		</thead>
		<tbody>
			 <?php

               $startDate = date('Y-m-01'); // first day of current month
$endDate   = date('Y-m-t'); // last day of current month

$sql = $conn->prepare("
    SELECT *
    FROM tbl_invoice
    INNER JOIN tbl_tenants ON tbl_invoice.inv_ref_no = tbl_tenants.ten_ref_no
    WHERE tbl_invoice.auto = 'no'
      AND tbl_invoice.cpny_ID = :cpny_ID
      AND tbl_invoice.invoice_date BETWEEN :start AND :end
");
$sql->execute([
    ':cpny_ID' => $cpny_ID,
    ':start' => $startDate,
    ':end' => $endDate
]);
                 $tot_price = 0;
                 while($fetch = $sql->fetch()){
                     $unit_value=$fetch['unit_value'];
                     $tot_price = $tot_price+($fetch['invoice_fees']/$unit_value);
                     $currency_id=$fetch['currency_id'];
                       $li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
            $li_quIPaidNMoney->execute(); 
            $newDMoney=$li_quIPaidNMoney->fetch();
         $converted=$newDMoney['intoR'];
             ?>
		 <tr class="odd gradeX">
                 <td><?php echo $fetch['invoice']; ?></td>
                 <td><?php echo $fetch['inv_ref_no']; ?></td>
                 <td><?php echo $fetch['te_names']; ?></td>
                 <td ><?php echo $fetch['invoice_fees']/$fetch['unit_value']; ?></td>
                 <td><?php echo $fetch['invoice_date']; ?></td>
                 <td><?php echo $fetch['inv_desc']; ?></td>
                  <td class="cell-icon text-center">
                         <a href="#" data-toggle="modal" data-target="#update<?php echo $fetch['invoice_id']; ?>" class="edit_data"><i class="fe fe-edit"></i></a>|
                         <a href="index?file=invoice&del_invoice_id=<?php echo $fetch['invoice_id']; ?>" onclick="if(!confirm('Do you really want to Delete This invoice?'))return false;else return true;"><i class="fe fe-trash"></i> </span></a>
                     </td>
             </tr>
        <div class="modal fade bs-example-modal-sm" id="update<?php echo $fetch['invoice_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-sm">
              <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Edit Invoice</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <div class="modal-body">
                  <form id="form_validation" method="POST" action="">
                      <input type="hidden"  name="invoice_id" value="<?php $invoice_id = $fetch['invoice_id']; echo $fetch['invoice_id']?>"/>
                      <?php
                         $sql1 = $conn->prepare("SELECT * FROM `tbl_invoice` WHERE `invoice_id`='$invoice_id'");
                         $sql1->execute();
                         $fetch1 = $sql1->fetch();
                     ?>
                     <div class="form-group">
                         <label class="form-label"> Invoice No</label>
                         <div class="form-line">
                            <input type="text" class="form-control" disabled value="<?php echo $fetch1['invoice']?>" name="invoice_no" required style="width: 100%;">
                         </div>
                     </div>
                     
                      <div class="form-group form-float">
                         <label class="form-label"> Ref no </label>
                         <div class="form-line">
                            <input type="text" class="form-control" disabled value="<?php echo $fetch1['inv_ref_no']?>" name="invoice_ref_no" required style="width: 100%;">
                         </div>
                     </div>
                     
                     <div class="form-group form-float">
                         <label class="form-label"> Amount </label>
                         <div class="form-line">
                            <input type="text" class="form-control" value="<?php echo $fetch1['invoice_fees']/$fetch['unit_value']?>" name="invoice_amount" required style="width: 100%;">
                         </div>
                     </div>
                     
                     <div class="form-group form-float">
                         <label class="form-label"> Invoice Date </label>
                         <div class="form-line">
                            <input type="date" class="form-control" value="<?php echo $fetch1['invoice_date']?>" name="invoice_date" required style="width: 100%;">
                         </div>
                     </div>
                     <div class="form-group form-float">
                         <label class="form-label"> Comment </label>
                         <div class="form-line">
                            <input type="text" class="form-control" value="<?php echo $fetch1['inv_desc']?>" name="invoice_comment" required style="width: 100%;">
                         </div>
                     </div>
                 </div>
                       <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                     <button type="submit" name="update_invoice" class="btn btn-primary">Submit</button>
                   </div>
                 </form><?php
     }
 ?>
              </div>
           </div>
         </div>
		 </tbody>
		  <tfoot>
            <tr>  
              <td >Tot.</td>
              <td  colspan="2" ></td>
              <td  align="left"><?php echo $tot_price;?></td>
              <td ></td>
              <td ></td>
              <td ></td>
            </tr>
          </tfoot>
	</table>
 </div>
 </div>
 </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
  <div class="row">
    <div class="col-md-12">
      <form role="form" id="add_form" action="" method="POST">
        <div class="card shadow mb-4">
          <div class="card-body">

            <!-- First row -->
            <div class="form-row mb-3">
              <div class="form-group col-md-6">
                <label for="tenant_ref">Tenant name</label>
                <select id="tenant_ref" name="tenant_name" class="form-control select2" required>
                  <option></option>
                  <?php
                    $sql = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_staff = $company AND ten_ref_no NOT IN (SELECT tenant_id FROM tbl_tenant_leave GROUP BY tenant_id) ORDER BY ten_id ASC");
                    $sql->execute();
                    while($fetch = $sql->fetch()){
                  ?>
                    <option value="<?php echo $fetch['ten_id']?>">
                      <?php echo $fetch['te_names']." [".$fetch['ten_phone']."]"; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="amount">Amount</label>
                <input type="number" name="invoice_fees" id="amount" class="form-control" placeholder="Amount" required/>
              </div>
            </div>

            <!-- Second row -->
            <div class="form-row mb-3">
              <div class="form-group col-md-6">
                <label for="room_idd">Room name</label>
                <select name="room_idd" id="room_idd" class="form-control select2" required>
                  <option selected disabled>-- choose room id --</option>
                </select>
                <br>
                <label for="date">Date</label>
                <input type="date" class="form-control" name="date" id="date" />
              </div>
              <div class="form-group col-md-6">
                <label for="comment">Comments</label>
                <textarea rows="3" name="comment" id="comment" placeholder="Enter Comment" class="form-control"></textarea>
              </div>
            </div>

            <!-- Submit button row -->
            <div class="form-row">
              <div class="form-group col-md-3"></div>
              <div class="form-group col-md-6 text-center">
                <button type="submit" name="add_tenant_invoice" class="btn btn-primary btn-block"> next and Save</button>
              </div>
              <div class="form-group col-md-3"></div>   
            </div>

          </div> <!-- /.card-body -->
        </div> <!-- /.card -->
      </form>

      <div name='display_specs' id='display_specs'></div>

    </div> <!-- /.col-md-12 -->
  </div> <!-- /.row -->
</div> <!-- /.tab-pane -->


        </div>
</div> <!-- end section -->
    
        </div>
    </div>
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#tenant_ref').change(function () {
        var tenant_reff = $(this).val(); // simpler and cleaner
        $.get('load_roomsspecial.php', { tenant_ref: tenant_reff }, function (data) {
            $('#room_idd').html(data);
        });
    });
});
</script>


