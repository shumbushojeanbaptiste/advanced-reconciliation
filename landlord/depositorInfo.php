<?php 
require_once("../include/initialize.php");

$tenantRef = $_REQUEST['keyword1'] ?? '';
$tenant = $db->prepare("SELECT * FROM tbl_tenants WHERE ten_ref_no = :ref");
$tenant->execute([':ref' => $tenantRef]);
$tenantData = $tenant->fetch(PDO::FETCH_ASSOC);

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
// $invoicesStmt = $db->prepare("
//     SELECT invoice_id, invoice, invoice_fees, invoice_date, inv_desc 
//     FROM tbl_invoice 
//     WHERE inv_ref_no = :tenantRef 
//     group by invoice_date,inv_ref_no
//     ORDER BY invoice_date DESC 
//     LIMIT 10
// ");


    $invoicesStmt = $db->prepare("
    SELECT 
        r.room_no,
        i.invoice_id,
        i.invoice,
        i.invoice_fees,
        i.inv_desc,
        i.invoice_date,
        i.inv_room_id,
        i.inv_date,
        i.done_by,
        i.paid,

        -- Calculate total payments for this invoice
        COALESCE(SUM(p.pay_amount), 0) AS total_paid,

        -- Calculate remaining balance (invoice total - total payments)
        (i.invoice_fees - COALESCE(SUM(p.pay_amount), 0)) AS remaining_balance

    FROM tbl_invoice AS i
    LEFT JOIN tbl_rooms AS r ON r.room_id = i.inv_room_id
    LEFT JOIN tbl_payment AS p 
        ON p.invoice_id = i.invoice_id
        AND p.pay_ref_no = i.inv_ref_no
        AND p.invoice_date = i.invoice_date

    WHERE i.inv_ref_no = :inv_ref_no 
      AND i.invoice_fees != 0

    GROUP BY 
        i.invoice_id, r.room_no, i.invoice, i.invoice_fees, 
        i.inv_desc, i.invoice_date, i.inv_room_id, 
        i.inv_date, i.done_by, i.paid

    ORDER BY i.invoice_id desc
    LIMIT 10
");
$invoicesStmt->execute([':inv_ref_no' => $tenantRef]);
$lastInvoices = $invoicesStmt->fetchAll(PDO::FETCH_ASSOC);



// Fetch last 2 payments
$paymentsStmt = $db->prepare("
    SELECT pay_id, pay_amount, pay_due_date, pay_description 
    FROM tbl_payment 
    WHERE pay_ref_no = :tenantRef 
    ORDER BY rent_date DESC 
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
               
                 <h5 class="card-title mb-0" data-toggle="modal" data-target="#addexampleModalCenter" onClick="makeDeposit(<?php echo $tenantData['ten_id'];  ?>,'<?php  echo htmlspecialchars($tenantData['te_names']); ?>')"> Balance : <span class="text-success"><span id="balance-area"><?php echo number_format($tenantData['rent_balance'],2)." Frw"; ?></span></span></h5>
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
    <div class="d-grid" align="left">
        <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#addexampleModalCenter" onClick="makeDeposit(<?php echo $tenantData['ten_id'];  ?>,'<?php  echo htmlspecialchars($tenantData['te_names']); ?>')">
    Next and Deposit <i class="fe fe-arrow-right ms-1"></i>
</button>

    </div>
       
    </div>

    <!-- RIGHT COLUMN: Activities -->
    <div class="col-md-8 col-xl-6">
        <div class="card shadow-sm">
            <div class="card-header " onClick=selecttbl_admission2(<?= $tenantRef ?>)>
                <h5 class="card-title mb-0">Recent Invoice Generated    <i class="fe fe-arrow-right"></i> </h5>
            </div>
        <div class="card-body">

    <!-- Last 5 Invoices -->
    <h6 class="mb-3">Last 10 Invoices</h6>
    <?php if(!empty($lastInvoices)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Shops</th>
                        <th class="text-end">Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach($lastInvoices as $inv): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars(date("M Y", strtotime($inv['invoice_date']))); ?></td>
                            <td><?php echo "#".htmlspecialchars($inv['room_no']); ?></td>
                            <td class="text-end">
                                <?php echo number_format($inv['invoice_fees'], 2); ?>
                            </td>
                              <td>
                              <?php if($inv['remaining_balance'] == 0) { ?>  
                              <span class="fe fe-check text-success" title="Approved and paid"></span></td>
                              <?php  } else {?>
                                <?=  number_format($inv['remaining_balance'], 2); ?>
                                <?php } ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <p class="text-muted">No invoices found.</p>
    <?php endif; ?>

  
</div>

        </div>
    </div>
</div>
  
<div class="row">
 <div class="col-md-8 col-xl-12">
      
           <div id="recent-deposit"></div>
                    
                    </div>
    </div>




<!-- Modal (structure unchanged; only content updated) -->
<div class="modal fade" id="addexampleModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addexampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <!-- Header (styled background) -->
      <div class="modal-header " >
        <h3 class="modal-title" id="addexampleModalCenterTitle"><span class="fe fe-24 fe-user-check"></span> <?php echo htmlspecialchars($tenantData['te_names']); ?> |  <span id="balance-area2"></span></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     
      <div id="deposit-form" align="center"></div>

    </div>
  </div>
</div>



                                    
<script>
$(document).ready(function () {
var ten_id= <?php   echo $tenantData['ten_id']; ?>;
recent_deposit(ten_id);
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

function recent_deposit(val) {
    $("#recent-deposit").html('<div class="text-center"><span>Loading...</span></div>').fadeIn();

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

function cancelDeposit(deposit_id, ten_id, amount) {
  // SweetAlert confirmation
  Swal.fire({
    title: "Are you sure?",
    text: "This will remove the deposit and refund the amount  to the tenant's balance.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, remove it!",
    cancelButtonText: "Cancel"
  }).then((result) => {
    if (result.isConfirmed) {
      // show loader inside table or element
      $("#deposit_id" + deposit_id).html(
        '<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />'
      ).fadeIn();

      $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: {
          action: "remove-deposit",
          ten_id: ten_id,
          deposit_id: deposit_id,
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

          $("#deposit_id" + deposit_id).fadeOut();

          // Refresh deposit and balance views
          balance(ten_id);
          recent_deposit(ten_id);

          if (res.status === 1) {
            Swal.fire({
              icon: "success",
              title: "Deposit Removed",
              text: res.message || "Deposit removed and balance refunded.",
              showConfirmButton: false,
              timer: 3000
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed",
              text: res.message || "Could not remove deposit.",
            });
          }
        },
        error: function (xhr, status, error) {
          $("#deposit_id" + deposit_id).html('<span class="text-danger">Failed</span>');
          Swal.fire("Error", "Something went wrong while processing your request.", "error");
          console.error(error);
        }
      });
    }
  });
}



</script>


