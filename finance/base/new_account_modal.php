<?php 
//require_once("../../include/initialize.php");
?>
<!-- Add Account Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Create New Account</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <form method="POST" id="save_department">
        <div class="modal-body">

          <input type="hidden" name="action" value="save_account">
            <h6>Account name</h6>
          <div class="form-group">
            <input type="text"
                   name="account_name"
                   placeholder="Write account number..."
                   class="form-control"
                   required>
          </div>

          <h6>Account Number</h6>
          <div class="form-group">
            <input type="text"
                   name="account_no"
                   placeholder="Write account number..."
                   class="form-control"
                   required>
          </div>

          <h6>Wallet Type</h6>
          <div class="form-group">
            <select name="wallet_type" class="form-control" required>
             
              <?php
              // Fetch wallet methods from database
              $methods = $db->query("SELECT method_id, method_name FROM payment_methods");
              while ($method = $methods->fetch()) {
                  echo "<option value='" . htmlspecialchars($method['method_id']) . "'>" . htmlspecialchars($method['method_name']) . "</option>";
              }
              ?>
          
            </select>
          </div>
          <div id="result" class="small mt-2"></div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancel
          </button>

          <button type="submit"
                  class="btn btn-primary"
                  id="savebtn">
            <span id="indicator-save"></span>
            Save Account
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
$('#save_department').on('submit', function (e) {
    e.preventDefault();

    $('#indicator-save').html("<img src='../../img/ajax_loader.gif' width='15'>");

    $.ajax({
        url: '../finance/controller/accountController.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            $('#indicator-save').html('');
            $('#result').html(response);

            // Reload account list
            accountList();

            // Close modal after success
            setTimeout(() => {
                $('#addAccountModal').modal('hide');
                $('#save_department')[0].reset();
                 
            }, 1000);
        },
        error: function () {
            $('#indicator-save').html('');
            $('#result').html("<span class='text-danger'>Error saving data</span>");
        }
    });
});
</script>

