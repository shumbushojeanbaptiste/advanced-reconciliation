<?php 
require_once("../include/initialize.php");
$names=$_POST['names'];
$ten_id=$_POST['ten_id'];
?>
<form class="form-horizontal row-fluid" id="add_deposit" method="post" action="">
  <div class="modal-body">
    <!-- hidden fields -->
    <input type="hidden" id="deposit_ten_id" name="ten_id" value="<?php echo $ten_id; ?>">
    <input type="hidden" id="deposited_from" name="deposited_from" value="<?php echo htmlspecialchars($ten_id); ?>">
     <input type="hidden" id="recorded_by" name="recorded_by" value="<?php echo htmlspecialchars($USER_ID); ?>">
    

    <div class="row">
      <!-- Bank -->
      <div class="col-md-6">
        <div class="form-group mb-3">
          <label for="bank">Bank | Account Deposited <?php echo $names ?> <span class="text-danger">*</span></label>
          <select id="bank" name="bank_id" class="form-control select2" required>
            <?php
            $sql = $conn->prepare("SELECT * FROM `tbl_bank`");
            $sql->execute();
            while($fetch = $sql->fetch()){
            ?>
              <option value="<?php echo $fetch['bank_id']?>"><?php echo $fetch['bank_name']?></option>
            <?php } ?>      
          </select>
        </div>
      </div>

      <!-- Deposit amount -->
      <div class="col-md-6">
        <div class="form-group mb-3">
          <label for="deposit_amount">Deposit Amount <span class="text-danger">*</span></label>
          <input type="number" step="0.01" min="0" id="deposit_amount" name="deposit_amount" class="form-control" placeholder="Enter amount" required>
        </div>
      </div>

      <!-- Bank slip -->
      <div class="col-md-6">
        <div class="form-group mb-3">
          <label for="deposited_from">Bank Slip <span class="text-danger">*</span></label>
          <input type="text" id="deposited_from" name="bank_slip" class="form-control" placeholder="Bank reference (e.g. <?php echo date('YmdHis'); ?>)" required>
        </div>
      </div>

      <!-- Deposit date -->
      <div class="col-md-6">
        <div class="form-group mb-3">
          <label for="deposit_date">Deposited on <span class="text-danger">*</span></label>
          <input type="date" id="deposit_date" name="deposit_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
      </div>

      <!-- Description -->
      <div class="col-md-12">
        <div class="form-group mb-3">
          <label for="deposit_desc">Description / Notes</label>
          <textarea id="deposit_desc" name="deposit_desc" class="form-control" rows="3" placeholder="Optional notes about this deposit"></textarea>
        </div>
      </div>
    </div> <!-- .row -->

    <div id="display_roomcode"></div>

    <!-- Terms and Conditions -->
    <div class="form-group mt-3">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="termsCheck">
        <label class="form-check-label" for="termsCheck">
          I agree to the <a href="../deposit_terms" target="_blank">terms and conditions</a>.
        </label>
      </div>
    </div>
    <span id="result_alert"></span>
  </div> <!-- .modal-body -->

  <div class="modal-footer">
    <button type="submit" name="insert_deposit" id="submitBtn" class="btn btn-primary btn-block" disabled>
      <span id="spinner"></span>Save Deposit
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
    var ten_id= $("#deposit_ten_id").val();
    // show spinner
    $("#spinner").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    // collect form data
    var formData = $(this).serialize(); // gets all form inputs

    $.ajax({
        type: "POST",
        url: "../deposit/recentTransaction.php",
        data: formData + "&action=save-deposit", // append action
        success: function(response) {
            $("#spinner").fadeOut();

            try {
                // parse JSON response
                var res = JSON.parse(response.trim());

                if (res.status == "1") {
                    Swal.fire({
                        icon: 'success',
                        title: res.amount + ' Deposited' || 'Deposit saved successfully!',
                        text: res.ref_code || 'Something went wrong.',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    
                     balance(ten_id);
                     recent_deposit(ten_id);
                    // optional: reset form
                    $('#add_deposit')[0].reset();
                   
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to save deposit',
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
