<style>
    .modal { display:none; }
    .modal-title {color: #111;}
    td {
        font-size: 12px;
    }
    .title{
        margin:10px;
        font-size:15px;
    }
    .form-group{
        display: inline-flex;
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
    	<div class="alert alert-danger" role="alert">
        <span class="fe fe-minus-circle fe-16 mr-2"></span> <?php echo $msge; ?> </div>
        <?php
           }
        ?>
 <div class="module">
 <ul class="nav nav-tabs">
    <li class="active"><a href="#" data-toggle="tab">New Payment</a></li>
</ul>
<br>
</div><!--/.module-->
</div><!--/.content-->
</div><!--/.span9-->
<div class="row">
<div class="col-md-12"><br><br>
<div class="col-md-12"><br><br>

<form class="form-horizontal row-fluid"  role="form" id="add_form" action="" method="POST" name="frmPayRecord" onSubmit="return validateform()">
    <p id="messagepy" style='display:none;'></p>
  <div class="card shadow mb-4">
    <div class="card-body">
        <br><br>
      <div class="form-row">
          
        <div class="form-group col-md-2">
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationCustom3">Tenant name</label>
           <select id="tenant_ref" name="tenant_ref" class="form-control select2"  required>
                      <option></option>     
                 <?php
                     $sql = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_staff = $company and ten_id not in (select tenant_id from tbl_tenant_leave group by tenant_id)");
                     $sql->execute();
                     while($fetch = $sql->fetch()){
                ?>
                 <option value="<?php echo $fetch['ten_ref_no']?>"><?php echo $fetch['te_names']?></option>
                 
                 <?php
                     }
                 ?>      
                 </select>
                
        </div> 
        <!-- form-group -->
        <div class="col-md-3 mb-3">
          <label for="multi-select2">Bank/Account</label>
          <select id="bank" name="bank" class="form-control select2" required>
               <option></option>
             <?php
                 $sql = $conn->prepare("SELECT * FROM `tbl_bank`");
                     $sql->execute();
                     while($fetch = $sql->fetch()){
                 ?>
         
                     <option value="<?php echo $fetch['bank_id']?>"><?php echo $fetch['bank_name']?></option>
                 
                 <?php
                     }
                 ?>      
                 </select>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div id="displayRoom"></div>
      <div id="balanceamount"></div>
      <div class="form-row">
        <div class="form-group col-md-2">
        </div>
        <div class="col-md-3 mb-3">
          <!--<label for="simple-select2">Slip No</label>-->
          <!-- <input type="text" name="slip_no" id="slip_no" class="form-control" placeholder="Slip Number" />-->
          <label for="simple-select2">Date</label>
          <input type="date" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d')?>" max="<?php echo date('Y-m-d')?>" required />
        </div> <!-- form-group -->
        <div class="col-md-3 mb-3">
          <label for="multi-select2">Comments</label>
          <textarea rows="3" name="comment" id="comment" placeholder="Enter Comment" class="form-control" ></textarea>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div class="form-row">
        <div class="form-group col-md-2">
        </div>
        <div class="col-md-3 mb-3">
          <button type="submit" name="add_tenant_payment" id="add_tenant_payment" class="btn btn-primary btn-sm">Save</button>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      </form>
  <div name='display_specs' id='display_specs'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->
<style>
    .pending-payments {
        background-color: #f8f9fa; /* Light gray background */
        padding: 20px;           /* Space around the content */
        border-radius: 10px;     /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }

    .pending-payments table {
        background-color: #ffffff; /* White background for table */
    }

    .pending-payments th, .pending-payments td {
        text-align: center; /* Center-align text in table cells */
        vertical-align: middle;
    }

    .pending-payments .footer-buttons button {
        width: 150px; /* Consistent button width */
    }

    .pending-payments .footer-buttons {
        display: flex;
        justify-content: flex-end;
    }
</style>
<?php
$sqluser = $db->prepare("SELECT * FROM tbl_payment p INNER JOIN tbl_tenants t ON p.pay_ref_no=t.ten_ref_no left join tbl_rooms r on p.pay_room_id=r.room_id WHERE status='2' order by p.invoice_date desc");
$sqluser->execute();
$countuser=$sqluser->rowCount();
if($countuser>0){
            ?>
<div class="pending-payments p-4" style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll" /></th>
                <th>Tenant Name</th>
                <th>Pay Date</th>
                <th>Invoice date</th>
                <th>Room No</th>
                <th>Amount</th>
                <th>Bank Mode</th>
            </tr>
        </thead>
        <tbody id="pendingPaymentBody">
            <?php
            $totaAmount=0;
            
            while ($rowUser = $sqluser->fetch()) {
                $sqlbank = $db->prepare("SELECT * FROM tbl_bank WHERE bank_id=?");
                $sqlbank->execute([$rowUser['bank_id']]);
                $rowbank = $sqlbank->fetch();
                $adate = $rowUser['invoice_date']; // Static full timestamp
                $rentdate = date("Y-M", strtotime($adate));
                $totaAmount+=$rowUser['pay_amount'];
                $paydate=$rowUser['pay_due_date'];
            ?>
                <tr>
                    <td><input type="checkbox" class="selectRow" value="<?php echo $rowUser['pay_id']; ?>" /></td>
                    <td><?php echo $rowUser['te_names']; ?></td>
                    <td><?php echo $paydate; ?></td>
                    <td><?php echo $rentdate; ?></td>
                    <td><?php echo $rowUser['room_no']; ?></td>
                    <td><?php echo number_format($rowUser['pay_amount'], 0); ?></td>
                    <td><?php echo $rowbank['bank_name']; ?></td>
                </tr>
                
            <?php } ?>
            <tr>
                <td colspan='5'></td>
                <td><?php echo number_format($totaAmount,0); ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div class="footer-buttons text-end mt-3">
        <button 
            id="deletePending" 
            style="background-color: #dc3545; color: white; padding: 5px 10px; font-size: 12px; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">
            Reject
        </button>
        <button 
            id="approvePending" 
            style="background-color: #28a745; color: white; padding: 5px 10px; font-size: 12px; border: none; border-radius: 5px; cursor: pointer;">
            Approve
        </button>
    </div>
</div>
<?php } ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- iziToast CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<!-- iziToast JS -->
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#add_form').submit(function(event){
            	$("#add_tenant_payment").after('<div id="loader11a"><img src="../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');   
            event.preventDefault();
            $.ajax({
                url: "load_rentPayment_room.php",
                method: "post",
                data: $('form').serialize(),
                dataType: "text",
                success: function(strDate){
                     $('#loader11a').slideUp(910, function () {
                    $("#loader11a").remove();
                });
                    $('#messagepy').text(strDate);
                    var msg = $('#messagepy').text().trim();
                    if (msg === 'Done') {
                        pop_up_success("branch added successfully!");
                        setTimeout(function(){
                        window.location.reload();
                    }, 1000); 
                     } else  if(msg=='Exist'){
                        pop_wrong("Slip number already exists");
                    }
                    else  if(msg=='pending'){
                        pop_wrong("This slip has already been used");
                    }
                    else  if(msg=='same room not'){
                        pop_wrong("Please enter a different room.");
                    }
                }
            });
        });
    });

    function pop_wrong(feedback) {
        iziToast.warning({
            title: 'Ooops',
            message: feedback,
            position: 'topRight'
        });
    }

    function pop_up_success(feedback) {
        iziToast.success({
            title: 'ChIC',
            message: feedback,
            position: 'topRight'
        });
    }
</script>



<script>
    $(document).ready(function () {
        // Select or deselect all checkboxes
        $('#selectAll').click(function () {
            $('.selectRow').prop('checked', this.checked);
        });

     $('#approvePending').click(function () {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to approve all pending payments!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve all!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'approve_payments.php',
                type: 'POST',
                data: JSON.stringify({ approve_all: true }),  // Send a flag instead of IDs
                contentType: 'application/json',
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Approved!',
                        text: response,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // Reload the page
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while approving payments.',
                    });
                }
            });
        }
    });
});


        // Reject payments
        $('#deletePending').click(function () {
            let selectedPayments = [];
            $('.selectRow:checked').each(function () {
                selectedPayments.push($(this).val());
            });

            if (selectedPayments.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one payment to reject.',
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to reject selected payments!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_payments.php',
                        type: 'POST',
                        data: { payment_ids: selectedPayments },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected!',
                                text: response,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // Reload the page
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while rejecting payments.',
                            });
                        }
                    });
                }
            });
        });
    });
</script>




        </div>

        </div>
</div> <!-- end section -->
 <script type="text/javascript">
    $(document).ready(function () {
        $('#tenant_ref').change(function () {
            var tenant_reff = $('#tenant_ref').val();         
            $.get('load_rooms.php?tenant_ref=' + $(this).val() , function (data) {
             $("#displayRoom").html(data);
            });
        });

    });
</script>
 <script type="text/javascript">
    $(document).ready(function () {
        $("#bank").change(function () {
            var tenant_reff = $('#tenant_ref').val();  
            var roomid1 = $('#roomid1').val();  
            $.get('load_balanceRoom.php?tenant_ref=' + tenant_reff+'&roomid1='+roomid1 , function (data) {
             $("#balanceamount").html(data);
            });
        });

    });
</script>
 <script type="text/javascript">
    $(document).ready(function () {
        $("#roomid1").change(function () {
            var tenant_reff = $('#tenant_ref').val();  
            var roomid1 = $('#roomid1').val();  
            $.get('load_balanceRoom.php?tenant_ref=' + tenant_reff+'&roomid1='+roomid1 , function (data) {
             $("#balanceamount").html(data);
            });
        });

    });
</script>

