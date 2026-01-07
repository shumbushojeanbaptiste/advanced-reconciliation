
<style>
    
</style>
<?php 
require_once("../include/initialize.php");

$month= $_REQUEST['month'] ?? '';

// Calculate total invoice amount and count for a given month (YYYY-MM)
$totalInvoiceStmt = $db->prepare("
    SELECT 
        COALESCE(SUM(invoice_fees), 0) AS total_invoice,
        COUNT(*) AS total_data,
        COUNT(distinct(inv_ref_no) ) AS total_tenant,
        COUNT(distinct(inv_room_id) ) AS total_room,
        COUNT(distinct(done_by) ) AS total_user,
         COUNT(distinct(invoice) ) AS links
        
    FROM tbl_invoice
    WHERE DATE_FORMAT(invoice_date, '%Y-%m') = :invoice_date
");
$totalInvoiceStmt->execute([':invoice_date' => $month]);
$totalInvoice = $totalInvoiceStmt->fetch(PDO::FETCH_ASSOC);

$total_invoice = $totalInvoice['total_invoice'];
$total_data = $totalInvoice['total_data'];
$total_tenant =  $totalInvoice['total_tenant'];
$total_room = $totalInvoice['total_room'];
$total_user = $totalInvoice['total_user'];
$links = $totalInvoice['links'];


$totalDepositStmt = $db->prepare("
    SELECT 
        COALESCE(SUM(dep_amount), 0) AS total_deposit,
        COUNT(*) AS total_data,
        COUNT(DISTINCT(ten_id)) AS total_tenant,
        COUNT(DISTINCT(bank_id)) AS total_bank,
        COUNT(DISTINCT(recorded_by)) AS total_user
       
    FROM tbl_deposit
    WHERE DATE_FORMAT(due_to, '%Y-%m') = :deposit_date
");
$totalDepositStmt->execute([':deposit_date' => $month]);
$totalDeposit = $totalDepositStmt->fetch(PDO::FETCH_ASSOC);

$total_deposit = $totalDeposit['total_deposit'];
$total_dataDep = $totalDeposit['total_data'];
$total_tenantDep = $totalDeposit['total_tenant'];
$total_bank = $totalDeposit['total_bank'];
$total_userDep = $totalDeposit['total_user'];

$totalPaymentStmt = $db->prepare("
    SELECT 
        COALESCE(SUM(pay_amount), 0) AS total_payment,
        COUNT(*) AS total_data,
        COUNT(DISTINCT(pay_ref_no)) AS total_ref,
        COUNT(DISTINCT(rec_by)) AS total_user,
        COUNT(DISTINCT(pay_room_id)) AS total_room,
        COUNT(DISTINCT(currency)) AS total_currency,
        COUNT(DISTINCT(invoice_id)) AS total_invoice
      
    FROM tbl_payment
    WHERE DATE_FORMAT(invoice_date, '%Y-%m') = :payment_date
");
$totalPaymentStmt->execute([':payment_date' => $month]);
$totalPayment = $totalPaymentStmt->fetch(PDO::FETCH_ASSOC);

$total_payment = $totalPayment['total_payment'];
$total_dataPay = $totalPayment['total_data'];
$total_refPay = $totalPayment['total_ref'];
$total_userPay = $totalPayment['total_user'];
$total_roomPay = $totalPayment['total_room'];
$total_currencyPay = $totalPayment['total_currency'];
$total_invoicePay = $totalPayment['total_invoice'];




$totalBalanceStmt = $db->prepare("
    SELECT 
        
        COUNT(DISTINCT sub.invoice_id) AS total_invoices,
        COUNT(DISTINCT sub.inv_room_id) AS total_rooms,
        COUNT(DISTINCT sub.inv_ref_no ) AS total_tenants
    FROM (
        SELECT 
            i.invoice_id,
            i.inv_room_id,
            i.inv_ref_no
           
            
            
        FROM tbl_invoice AS i
        LEFT JOIN tbl_payment AS p 
            ON i.invoice_id = p.invoice_id
        WHERE DATE_FORMAT(i.invoice_date, '%Y-%m') = :month
        GROUP BY 
            i.invoice_id, 
            i.inv_room_id,
            i.invoice_fees
        HAVING (i.invoice_fees - COALESCE(SUM(p.pay_amount), 0)) > 0
    ) AS sub
");

$totalBalanceStmt->execute([':month' => $month]);
$totalBalance = $totalBalanceStmt->fetch(PDO::FETCH_ASSOC);

// Access values
$total_tenantsB   = $totalBalance['total_tenants'];


$total_invoicesB  = $totalBalance['total_invoices'];
$total_roomsB     = $totalBalance['total_rooms'];









?>

<div class="row">
  

<div class="container py-4">
    
  <div class="row g-4"> <!-- g-4 adds nice spacing -->
    
    <!-- Free Plan -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card text-center h-100 shadow-sm border-0">
        <div class="card-body d-flex flex-column">
          <div class="mb-4">
            <h5 class="fw-bold text-success">Total Deposit</h5>
            <span class="display-6 fw-bold"><?= number_format($total_deposit) ?> </span>
            <span class="text-muted">/ <?= date("M Y", strtotime($month)) ?></span>
          </div>
          <h6 class="text-muted">Includes:</h6>
          <ul class="list-unstyled mb-4">
             <li><b><?= $total_dataDep ?></b>  Records</li>
             <li><b><?= $total_tenantDep ?></b>  Clients</li>
             <li><b><?= $total_userDep ?></b>  Cashier</li>
             <li><b><?= $total_bank ?></b>  Bank</li>
           
          </ul>
          <div class="mt-auto">
            <a href="javascript:void(0)" class="btn btn-outline-primary w-100"  onClick="deposit_list('<?= $month ?>')"> <span id="spinner-deposit"></span>View More <i class="fa fa-long-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Standard Plan -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card text-center h-100 shadow-sm border-0">
        <div class="card-body d-flex flex-column">
          <div class="mb-4">
            <h5 class="fw-bold text-warning">Total Invoices</h5>
            <span class="display-6 fw-bold"><?= number_format($total_invoice) ?> </span>
            <span class="text-muted">/ <?= date("M Y", strtotime($month)) ?></span>
          </div>
          <h6 class="text-muted">Includes:</h6>

          <ul class="list-unstyled mb-4">
            <li><b><?= $total_data ?></b>  Invoices</li>
             <li><b><?= $total_tenant ?></b>  Clients</li>
             <li><b><?= $total_room ?></b>  Rooms</li>
             <li><b><?= $total_userPay ?></b>  Users</li>
            <li><b><?= $links ?></b>  Links sent. </li>
          </ul>
          <div class="mt-auto">
            <a href="javascript:void(0)" class="btn btn-outline-primary w-100" onClick="monthlyInvoice('<?= $month ?>')"><span id="spinner-invoice"></span> View More <i class="fa fa-long-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Plus Plan -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card text-center h-100 shadow-sm border-0">
        <div class="card-body d-flex flex-column">
          <div class="mb-4">
            <h5 class="fw-bold text-success">Invoice Paid</h5>
            <span class="display-6 fw-bold"><?= number_format($total_payment) ?> </span>
           <span class="text-muted">/ <?= date("M Y", strtotime($month)) ?></span>
          </div>
          <h6 class="text-muted">Includes:</h6>
          <ul class="list-unstyled mb-4">
            <li><b><?= $total_invoicePay ?></b> Paid Records</li>
           <li><b><?= $total_dataPay ?></b>  Invoices Paid</li>
             <li><b><?= $total_refPay ?></b>  Clients</li>
             <li><b><?= $total_roomPay ?></b>  Rooms</li>
             <li><b><?= $total_userPay ?></b>  Users</li>
          
          </ul>
          <div class="mt-auto">
            <a href="javascript:void(0)" class="btn btn-outline-primary w-100" onClick="invoicePaid('<?= $month ?>')"><span id="spinner-paid"></span> View More  <i class="fa fa-long-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Enterprise Plan -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card text-center h-100 shadow-sm border-0">
        <div class="card-body d-flex flex-column">
          <div class="mb-4">
            <h5 class="fw-bold text-danger">Unpaid Balance</h5>
            <span class="display-6 fw-bold"><?= number_format($total_invoice-$total_payment) ?> </span>
            <span class="text-muted">/ <?= date("M Y", strtotime($month)) ?></span>
          </div>
          <h6 class="text-muted">Includes:</h6>
          <ul class="list-unstyled mb-4">
           <li><b><?= $total_invoicesB ?></b> Invoices</li>
            <li><b><?= $total_tenantsB ?></b>  Clients</li>
            <li><b><?= $total_roomsB ?></b>  Rooms</li>
           
          </ul>
          <div class="mt-auto">
            <a href="javascript:void(0)" class="btn btn-outline-primary w-100"> View More <i class="fa fa-long-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

 
</div>
<div class="row">
 <div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
  
     
        <span id="result"></span>
       
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
     
      <div id="invoice_pay-form text-align="></div>

    </div>
  </div>
</div>



                                    
<script>
$(document).ready(function () {


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

function monthlyInvoice(month) {

      $("#spinner-invoice").html('<img src="../images/ajax_loader.gif" alt="loading...." width="15" height="15" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: { month: month, action: "invoice-list" }, // action as string
        success: function(data) {
            $("#result").html(data).fadeIn();
            $("#spinner-invoice").fadeOut();
        },
        error: function(xhr, status, error) {
            $("#result").html('<span class="text-danger">Error loading recent deposits</span>');
            console.error(error);
        }
    });
}

function deposit_list(month) {
 
    $("#spinner-deposit").html('<img src="../images/ajax_loader.gif" alt="loading...." width="15" height="15" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: { month: month, action: "deposit-list" }, // action as string
        success: function(data) {
            $("#result").html(data).fadeIn();
            $("#spinner-deposit").fadeOut();
        },
        error: function(xhr, status, error) {
            $("#result").html('<span class="text-danger">Error loading recent deposits</span>');
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

function invoicePaid(month){
     $("#spinner-paid").html('<img src="../images/ajax_loader.gif" alt="loading...." width="15" height="15" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: { month: month, action: "paid-invoice-list" }, // action as string
        success: function(data) {
            $("#result").html(data).fadeIn();
            $("#spinner-paid").fadeOut();
        },
        error: function(xhr, status, error) {
            $("#result").html('<span class="text-danger">Error loading recent deposits</span>');
            console.error(error);
        }
    });
}



</script>


