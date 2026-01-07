<?php 
require_once("../include/initialize.php");

$tenantRef = $_REQUEST['keyword1'] ?? '';
$tenant = $db->prepare("SELECT * FROM tbl_tenants WHERE ten_ref_no = :ref");
$tenant->execute([':ref' => $tenantRef]);
$tenantData = $tenant->fetch(PDO::FETCH_ASSOC);
$tenant_id=$tenantData['ten_id'];

// Fetch rooms assigned to tenant
$roomsStmt = $db->prepare("
    SELECT r.room_no, r.r_floor, r.price, r.room_area, r.availability ,a.assgn_status
    FROM tbl_assign a
    INNER JOIN tbl_rooms r ON r.room_id = a.room_id
    WHERE a.tent_id = :tenantId
");
$roomsStmt->execute([':tenantId' => $tenantData['ten_id']]);
$assignedRooms = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch last 5 invoices
$invoicesStmt = $db->prepare("
    SELECT invoice_id, invoice, invoice_fees, invoice_date, inv_desc 
    FROM tbl_invoice 
    WHERE inv_ref_no = :tenantRef 
    group by invoice_date,inv_ref_no
    ORDER BY invoice_date DESC 
    LIMIT 5
");
$invoicesStmt->execute([':tenantRef' => $tenantRef]);
$lastInvoices = $invoicesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch last 2 payments
$paymentsStmt = $db->prepare("
    SELECT pay_id,pay_ref_no, pay_amount, pay_due_date, pay_description,invoice_date
    FROM tbl_payment 
    WHERE pay_ref_no = :tenantRef 
    ORDER BY pay_id DESC 
    LIMIT 5
");
$paymentsStmt->execute([':tenantRef' => $tenantRef]);
$lastPayments = $paymentsStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total invoice and total payment
$totalInvoiceStmt = $db->prepare("SELECT SUM(invoice_fees) AS total_invoice FROM tbl_invoice WHERE inv_ref_no = :tenantRef");
$totalInvoiceStmt->execute([':tenantRef' => $tenantRef]);
$totalInvoice = $totalInvoiceStmt->fetchColumn();

$totalPaymentStmt = $db->prepare("SELECT SUM(pay_amount) AS total_payment FROM tbl_payment WHERE pay_ref_no = :tenantRef");
$totalPaymentStmt->execute([':tenantRef' => $tenantRef]);
$totalPayment = $totalPaymentStmt->fetchColumn();

$tenantBalance = $totalInvoice - $totalPayment;
?>

<div class="row">
    <!-- LEFT COLUMN -->
    <div class="col-md-4 col-xl-6">
 
            <div class="card-header  text-info">
                <h5 class="card-title mb-0"> <span class="fe fe-24 fe-user-check"></span> <?php echo htmlspecialchars($tenantData['te_names']); ?> </h5>
               
            </div>
             <div class="card-header  text-info">
               
                 <h5 class="card-title mb-0"> Balance : <span class="text-success"><span id="balance-area"><?php echo number_format($tenantData['rent_balance'],2)." Frw"; ?></span></span></h5>
            </div>
          
       
            <hr class="my-0" />
            <div class="card-body">
                <h6 class="card-title">Identification and  Contact Info</h6>
                <ul class="list-unstyled mb-0">
                    <li><strong>Phone:</strong> <?php echo htmlspecialchars($tenantData['ten_phone']); ?></li>
                    <li><strong>ID Number:</strong> <?php echo htmlspecialchars($tenantData['id_number']); ?></li>
                    <li><strong>TIN No:</strong> <?php echo htmlspecialchars($tenantData['tin_no']); ?></li>
                    <li><strong>Leader:</strong> <?php echo htmlspecialchars($tenantData['leader_name']); ?></li>
                </ul>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <h6 class="card-title">Financial (Rent & Utility)</h6>
                <ul class="list-unstyled mb-0">
                   <li><strong>Rent Unpaid:</strong> <span class="badge text-danger"><?php echo number_format($tenantBalance, 2); ?></span></li>

                    <li><strong>Utility Balance:</strong> <span class="badge text-danger"><?php echo number_format($tenantBalance, 2); ?></span></li>
                </ul>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <h6 class="card-title">Rooms Assigned</h6>
                <?php if(!empty($assignedRooms)): ?>
                    <ul class="list-unstyled mb-0">
                    <?php foreach($assignedRooms as $room): 
    $room_status = $room['assgn_status']; // 1 = active, 0 = inactive
?>
    <li class="mb-1">
        <strong class="badge <?php echo $room_status == 1 ? 'text-success' : 'text-danger'; ?>"><?php echo htmlspecialchars($room['room_no']); ?></strong> 
        (Floor: <?php echo htmlspecialchars($room['r_floor']); ?>, Area: <?php echo htmlspecialchars($room['room_area']); ?> m², Price: <?php echo number_format($room['price'],2); ?>)
        
    </li>
<?php endforeach; ?>

                    </ul>
                <?php else: ?>
                    <p class="text-muted">No rooms assigned.</p>
                <?php endif; ?>
            </div>
        
     

             <hr />

       
    </div>

    <!-- RIGHT COLUMN: Activities -->
    <div class="col-md-8 col-xl-6">
        <div class="card shadow-sm">
            <div class="card-header " onClick=selecttbl_admission2(<?= $tenantRef ?>)>
                <h5 class="card-title mb-0">Recent Invoice Payment Transactions </h5>
                <i class="fe fe-arrow-right"></i>
            </div>
        <div class="card-body">

  

    <!-- Last 2 Payments -->
    <h6 class="mb-3">Last 10 Payments </h6>
    <?php if(!empty($lastPayments)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Paid Invoice</th>
                       
                        <th>Paid on</th>
                        <th class="text-end">Amount </th>
                       <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $j = 1; foreach($lastPayments as $pay): ?>
                        <tr>
                            <td><?php echo $j++; ?></td>
                            <td><?php echo htmlspecialchars(date("M Y", strtotime($pay['invoice_date']))); ?></td>
                           
                             <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($pay['pay_due_date']))); ?></td>
                            <td class="text-end">
                               <?php echo number_format($pay['pay_amount'], 2); ?>
                            </td>
                            <span id="pay_id<?php echo $dep['deposit_id'];  ?>"> 
                             <td><span class="fe fe-trash text-warning" title="Approved paid" onClick="cancelPay(<?= $pay['pay_id']  ?>,<?= $tenant_id ?>,<?= $pay['pay_amount']?>,<?= $pay['pay_ref_no']?>)"></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">No payments found.</p>
    <?php endif; ?>

    <hr />
   
</div>

        </div>
    </div>
</div>

<div class="row">
 <div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
  
      <div class="card-body">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" onClick="depositANDpayment(<?= $tenant_id ?>)" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General (Deposit & Invo Paid)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onClick="recent_deposit(<?= $tenant_id ?>)" id="deposited-tab" data-toggle="tab" href="#deposited" role="tab" aria-controls="deposited" aria-selected="false">Deposit (Income)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onClick="recent_invoice(<?= $tenant_id ?>)" id="invoice-tab" data-toggle="tab" href="#invoice" role="tab" aria-controls="invoice" aria-selected="false">Invoice (Remaining)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onClick="recent_payment(<?= $tenant_id ?>)" id="invo_paid-tab" data-toggle="tab" href="#invo_paid" role="tab" aria-controls="invo_paid" aria-selected="false">Rent Payment (Invoice Paid)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onClick="invoiceAndPayment(<?= $tenant_id ?>)"  id="invo_and_pay-tab" data-toggle="tab" href="#invo_and_pay" role="tab" aria-controls="invo_and_pay" aria-selected="false">Invoice vs Payment</a>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content" id="myTabContent">

        <!-- General Tab -->
        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
            <div class="row">
                <div class="col-md-8 col-xl-12">
                    <div id="recent-general"></div>
                </div>
            </div>
        </div>

        <!-- Deposited Tab -->
        <div class="tab-pane fade" id="deposited" role="tabpanel" aria-labelledby="deposited-tab">
            <div class="row">
                <div class="col-md-8 col-xl-12">
                    <div id="recent-deposit"></div>
                </div>
            </div>
        </div>

        <!-- Invoice Tab -->
        <div class="tab-pane fade" id="invoice"  role="tabpanel" aria-labelledby="invoice-tab">
            <div class="row">
                <div class="col-md-8 col-xl-12">
                    <div id="recent-invoice"></div>
                </div>
            </div>
        </div>

        <!-- Invoice Paid Tab -->
        <div class="tab-pane fade" id="invo_paid" role="tabpanel" aria-labelledby="invo_paid-tab">
            <div class="row">
                <div class="col-md-8 col-xl-12">
                    <div id="recent-invo_paid"></div>
                </div>
            </div>
        </div>

        <!-- Invoice vs Payment Tab -->
        <div class="tab-pane fade" id="invo_and_pay" role="tabpanel" aria-labelledby="invo_and_pay-tab">
            <div class="row">
                <div class="col-md-8 col-xl-12">
                    <div id="recent-invo_and_pay"></div>
                </div>
            </div>
        </div>

    </div>

</div>
  
  

</div>
</div>
</div>





<!-- Modal (structure unchanged; only content updated) -->
<div class="modal fade" id="addexampleModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addexampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      
      <!-- Header (styled background) -->
      <div class="modal-header " >
        <small class="modal-title" id="addexampleModalCenterTitle"><span class="fe fe-24 fe-user-check"></span> <?php echo htmlspecialchars($tenantData['te_names']); ?> |  <span id="balance-area2"></span></small>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     
      <div id="invoice_pay-form" align="center"></div>

    </div>
  </div>
</div>



                                    
<script>
$(document).ready(function () {
var ten_id= <?php   echo $tenantData['ten_id']; ?>;
recent_invoice(ten_id);
balance(ten_id);

    });
function balance(val) {
   
    $("#balance-area").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: { keyword1: val, action: "balance" }, // action as string
        success: function(data) {
          
            $("#balance-area").html(data).fadeIn();
             $("#balance-area2").html(data).fadeIn();
        },
        error: function(xhr, status, error) {
            $("#balance-area").html('<span class="text-danger">Error loading balance</span>');
            console.error(error);
        }
    });
}

function recent_invoice(val) {

    $("#recent-invoice").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: { keyword1: val, action: "invoices" }, // action as string
        success: function(data) {
            $("#recent-invoice").html(data).fadeIn();
        },
        error: function(xhr, status, error) {
            $("#recent-invoice").html('<span class="text-danger">Error loading recent deposits</span>');
            console.error(error);
        }
    });
}

function recent_deposit(val) {
   
    $("#recent-deposit").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: { keyword1: val, action: "recent" }, // action as string
        success: function(data) {
            $("#recent-deposit").html(data).fadeIn();
        },
        error: function(xhr, status, error) {
            $("#recent-deposit").html('<span class="text-danger">Error loading recent deposits</span>');
            console.error(error);
        }
    });
}
function recent_payment(val) {
   
    $("#recent-invo_paid").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: { keyword1: val, action: "recent-payment" }, // action as string
        success: function(data) {
            $("#recent-invo_paid").html(data).fadeIn();
        },
        error: function(xhr, status, error) {
            $("#recent-invo_paid").html('<span class="text-danger">Error loading recent deposits</span>');
            console.error(error);
        }
    });
}

function makeDeposit(ten_id,names){

 $("#deposit-form").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/deposit-form.php",
        data: {action: "deposit-form",ten_id : ten_id , names : names }, // action as string
        success: function(data) {
            $("#deposit-form").html(data).fadeIn();
            balance(ten_id);
        },
        error: function(xhr, status, error) {
            $("#deposit-form").html('<span class="text-danger">Missed</span>');
            console.error(error);
        }
    });
}

function payInvo(invo_id,ten_id,remain,invo_date,limit,ten_ref,room_id){
    

     $("#invoice_pay-form").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/invoice_pay-form.php",
        data: {action: "invoice_pay-form",ten_id : ten_id , invo_id : invo_id,remain : remain, invo_date : invo_date, limit : limit,ten_ref : ten_ref, room_id : room_id }, // action as string
        success: function(data) {
            $("#invoice_pay-form").html(data).fadeIn();
            balance(ten_id);
        },
        error: function(xhr, status, error) {
            $("#invoice_pay-form").html('<span class="text-danger">Missed</span>');
            console.error(error);
        }
    });

}

function cancelPay(pay_id, ten_id, amount, ref_no) {
  // SweetAlert confirmation
 
  Swal.fire({
    title: "Are you sure?",
    text: "This will remove the payment and refund the amount  to the tenant's balance.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, remove it!",
    cancelButtonText: "Cancel"
  }).then((result) => {
    if (result.isConfirmed) {
      // show loader inside table or element
      $("#pay_id" + pay_id).html(
        '<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />'
      ).fadeIn();

      $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: {
          action: "remove-payment",
          ten_id: ten_id,
          pay_id: pay_id,
          amount: amount
        },
        success: function (response) {
         
          let res;
          try {
            res = JSON.parse(response.trim());
          } catch (e) {
            console.error("Invalid JSON:", response);
            Swal.fire("Error", "Unexpected server response.", "error");
            return;
          }

          $("#pay_id" + pay_id).fadeOut();

          // Refresh deposit and balance views
         

          if (res.status === 1) {
            Swal.fire({
              icon: "success",
              title: "Payment Removed",
              text: res.message || "Payment removed and balance refunded.",
              showConfirmButton: false,
              timer: 3000
            });

          selecttbl_admission2(ref_no);
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed",
              text: res.message || "Could not remove deposit.",
            });
          }
        },
        error: function (xhr, status, error) {
          $("#pay_id" + deposit_id).html('<span class="text-danger">Failed</span>');
          Swal.fire("Error", "Something went wrong while processing your request.", "error");
          console.error(error);
        }
      });
    }
  });
}

function depositANDpayment(val){
    
    $("#recent-general").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: { keyword1: val, action: "deposit-and-payment" }, // action as string
        success: function(data) {
            $("#recent-general").html(data).fadeIn();
        },
        error: function(xhr, status, error) {
            $("#recent-general").html('<span class="text-danger">Error loading recent deposits</span>');
            console.error(error);
        }
    });
}

function invoiceAndPayment(val){
      
    $("#recent-invo_and_pay").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: { keyword1: val, action: "invoice-and-payment" }, // action as string
        success: function(data) {
            $("#recent-invo_and_pay").html(data).fadeIn();
        },
        error: function(xhr, status, error) {
            $("#recent-invo_and_pay").html('<span class="text-danger">Error loading recent deposits</span>');
            console.error(error);
        }
    });

}



</script>


