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
// room and payment
$shopStmt = $db->prepare("
SELECT
r.room_id,
r.room_no, 
r.r_floor, 
r.price, 
r.room_area,
r.availability,
a.assgn_status

    FROM tbl_assign a
    INNER JOIN tbl_rooms r ON r.room_id = a.room_id
    WHERE a.tent_id = :tenantId
    
");
$shopStmt->execute([':tenantId' => $tenantData['ten_id']]);
$assignedShops = $shopStmt->fetchAll(PDO::FETCH_ASSOC);

//


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

// Calculate total invoice and total payment # utility
$totalbillStmt = $db->prepare("SELECT SUM(invoice_fees) AS total_invoice FROM tbl_bill WHERE ten_id = :tenantRef");
$totalbillStmt->execute([':tenantRef' => $tenantRef]);
$totalBill = $totalbillStmt->fetchColumn();

$totalPayBillStmt = $db->prepare("SELECT SUM(pay_amount) AS total_payment FROM tbl_pay_bill WHERE ten_id = :tenantRef");
$totalPayBillStmt->execute([':tenantRef' => $tenantRef]);
$totalPayBill = $totalPayBillStmt->fetchColumn();

$BilltenantBalance = $totalBill - $totalPayBill;
?>

<div class="row">
    <!-- LEFT COLUMN -->
    <div class="col-md-4 col-xl-5">
 
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

                    <li><strong>Utility Balance:</strong> <span class="badge text-danger"><?php echo number_format($BilltenantBalance, 2); ?></span></li>
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
    <div class="col-md-8 col-xl-7">
        <div class="card shadow-sm">
            <div class="card-header " onClick="selecttbl_admission2(<?= $tenantRef ?>)">
                <h5 class="card-title mb-0">Shop Financial Statement </h5>
                
            </div>
    <div class="form-group col-md-1 lblClass">
    <label for="tenantly" class="fw-bold">Filter</label>
  </div>

  <div class="form-group col-md-6">
    <input 
      type="month" 
      class="form-control" 
      name="date_to" 
      id="tenantly" 
      value="<?= date('Y-m') ?>" 
      required
    >
  </div>
   <div class="form-group col-md-6">
             <a href="javascript:void(0)" class="btn btn-outline-primary w-100"  onClick="invoice_overview('<?= $tenant_id ?>')"> <span id="spinner-invoice"></span>  <i class="fa fa-long-arrow-right"></i></a>
    </div>
        <div class="card-body">

  

    <!-- Last 2 Payments -->
   

        <div class="table-responsive mb-3">
         
                 
                       <div id="data-invoice"></div>
                 
             
        </div>
  

    <hr />
   
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
function invoice_overview(val){

    var month=$("#tenantly").val();
    
    $("#spinner-invoice").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: { keyword1: val, action: "custom-invo",month }, // action as string
        success: function(data) {
            $("#data-invoice").html(data).fadeIn();
            $("#spinner-invoice").fadeOut();
        },
        error: function(xhr, status, error) {
            $("#data-invoice").html('<span class="text-danger">Error loading recent deposits</span>');
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




function unAssign(room_id,ref_no) {
  // SweetAlert confirmation
 
  Swal.fire({
    title: "Are you sure?",
    text: "This action will remove this shop from the tenant’s account.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, remove it!",
    cancelButtonText: "Cancel"
  }).then((result) => {
    if (result.isConfirmed) {
      // show loader inside table or element
      $("#room_id" + room_id).html(
        '<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />'
      ).fadeIn();

      $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: {
          action: "un-assign-shop",
          room_id: room_id
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

          $("#room_id" + room_id).fadeOut();

          // Refresh deposit and balance views
         

          if (res.status === 1) {
            Swal.fire({
              icon: "success",
              title: "Shop Removed",
              text: res.message || " ",
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
          $("#room_id" + room_id).html('<span class="text-danger">Failed</span>');
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

function saveInvo(ten_id,amount,month,ten_ref,room_id){
    
  
     $("#spinner_room" + room_id).html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: {action: "generate-invo",ten_id : ten_id , amount : amount,month : month,ten_ref : ten_ref, room_id : room_id }, // action as string
        success: function(data) {
           
            $("#spinner_room" + room_id).html(data).fadeIn();
            invoice_overview(ten_id);
        },
        error: function(xhr, status, error) {
            $("#spinner_room" + room_id).html('<span class="text-danger">Missed</span>');
            console.error(error);
        }
    });

}



</script>


