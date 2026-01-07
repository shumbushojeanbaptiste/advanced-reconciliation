<?php 
require_once("../include/initialize.php");
$invo_id=$_POST['invo_id'];
$ten_id=$_POST['ten_id'];
$remain=$_POST['remain'];
$limit=$_POST['limit'];
$invo_date=$_POST['invo_date'];
$ten_ref=$_POST['ten_ref'];
$room_id=$_POST['room_id'];


?>
<form class="form-horizontal row-fluid" id="add_deposit" method="post" action="">
  <div class="modal-body">

    <!-- Hidden Fields -->
    <input type="hidden" name="ten_id" id="ten_id" value="<?= htmlspecialchars($ten_id) ?>">
    <input type="hidden" name="invo_id" value="<?= htmlspecialchars($invo_id) ?>">
    <input type="hidden" name="recorded_by" value="<?= htmlspecialchars($USER_ID) ?>">
    <input type="hidden" name="invo_date" value="<?= $invo_date ?>">
    <input type="hidden" name="ten_ref" value="<?= $ten_ref ?>">
    <input type="hidden" name="room_id" value="<?= $room_id ?>">


    <!-- Header -->
    <div class="text-center mb-4">
      <h4 class="fw-bold text-primary">
        <i class="fe fe-credit-card text-primary"></i> <?= htmlspecialchars(date('F Y', strtotime($invo_date))) ?> Payment 
      </h4>
      <p class="text-muted small">Please review your details carefully before submitting.</p>
    </div>

    <div class="row">

      

          <!-- Pay Amount -->
          <div class="col-md-12">
            <label class="form-label fw-semibold">Pay Amount <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">RWF</span>
              <input type="number" 
                     step="0.01" 
                     min="0" 
                     max="<?= htmlspecialchars($remain) ?>" 
                     id="pay_amount"
                     name="pay_amount" 
                     class="form-control" 
                     value="<?= htmlspecialchars($remain) ?>" 
                     placeholder="Enter amount" 
                     required>
            </div>
          </div>

      

          <!-- Description -->
    <div class="col-md-12">
        <div class="form-group mb-3">
          <label for="deposit_desc">Description / Notes</label>
          <textarea id="deposit_desc" name="pay_desc" class="form-control" rows="3" placeholder="Optional notes about this payment"></textarea>
        </div>
      </div>

    
    </div>

    <!-- Terms and Conditions -->
    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" id="termsCheck">
      <label class="form-check-label" for="termsCheck">
        I agree to the <a href="../deposit_terms" target="_blank" class="text-primary fw-semibold">Terms and Conditions</a>
      </label>
    </div>

    <div id="result_alert" class="text-center small"></div>

  </div>

  <div class="modal-footer d-flex justify-content-center">
    <button type="submit" 
            name="insert_deposit" 
            id="submitBtn" 
            class="btn btn-primary px-5 rounded-pill shadow-sm" 
            disabled>
      <span id="spinner"></span> Save Payment
    </button>
  </div>
</form>


<!-- ✅ Working JavaScript -->
<script>
$(document).ready(function() {
  // Ensure button starts disabled
  $('#submitBtn').prop('disabled', true);

  // Toggle button based on checkbox
  $('#termsCheck').on('change', function() {
    if ($(this).is(':checked')) {
      $('#submitBtn').prop('disabled', false);
    } else {
      $('#submitBtn').prop('disabled', true);
    }
  });

 $('#add_deposit').on('submit', function(e) {
    e.preventDefault(); // stop normal form submission
   var pay_amt= $("#pay_amount").val();
    var invo_id = <?php  echo $invo_id; ?>;
     var ten_id = <?php  echo $ten_id; ?>;
      var remain = <?php  echo (float)$remain; ?> - pay_amt;
       var invo_date = <?php  echo $invo_date; ?>;
        var limit = <?php echo (float)$limit; ?> ;
         var ten_ref = <?php  echo $ten_ref; ?>;
         var room_id = <?php  echo $room_id; ?>;
    // show spinner
    $("#spinner").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    // collect form data
    var formData = $(this).serialize(); // gets all form inputs

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: formData + "&action=save-payment", // append action
        success: function(response) {
        
            $("#spinner").fadeOut();

            try {
                // parse JSON response
                var res = JSON.parse(response.trim());

                if (res.status == "1") {
                    Swal.fire({
                        icon: 'success',
                        title: res.transaction  || 'Deposit saved successfully!',
                        text: res.message || 'Well Done',
                        showConfirmButton: false,
                        timer: 2000
                    });
                     $('#add_deposit')[0].reset();
                   
                     balance(ten_id);
                     payInvo(invo_id,ten_id,remain,invo_date,limit,ten_ref,room_id);
                     recent_invoice(ten_id);
                    // optional: reset form
                   
                   
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to save Payment',
                        text: res.message || 'Something went wrong.',
                    });
                }
            } catch (err) {
                console.error("Invalid JSON response:", err);
                Swal.fire({
                    icon: 'error',
                    title: 'Unexpected Error',
                    text: 'Could not process server response.',
                });
            }
        },
        error: function(xhr, status, error) {
            $("#spinner").fadeOut();
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'Something went wrong. Please try again.',
            });
            console.error(error);
        }
    });
});

});


</script>
