<?php 
//require_once("../../include/initialize.php");
?>
<!-- Add Account Modal -->
<div class="modal fade" id="newInitModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Start New Reconciliation</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <form method="POST" id="save_reco_init">
        <div class="modal-body">

          <input type="hidden" name="action" value="save_init">
            <h6>Starting From </h6>
          <div class="form-group">
            <input type="date"
                   name="date_from"
                   placeholder="Write account number..."
                   class="form-control"
                   max="<?= date("Y-m-d"); ?>"
                   value="<?= date("Y-m-d"); ?>"
                   required>
          </div>

          <h6>Ending to</h6>
          <div class="form-group">
            <input type="date"
                   name="to_date"
                   placeholder="Write account number..."
                   class="form-control"
                   max="<?= date("Y-m-d"); ?>"
                   value="<?= date("Y-m-d"); ?>"
                   required>
          </div>

          <h6>Wallet Type</h6>
          <div class="form-group">
            <select name="wallet_type" class="form-control" required>
             
              <?php
              // Fetch wallet methods from database
              $methods = $db->query("
              SELECT account_id, account_name, account_reference, account_method_type, balance, created_at,method_name FROM tbl_accounts
        LEFT JOIN payment_methods ON payment_methods.method_id= tbl_accounts.account_method_type
              ");
              while ($method = $methods->fetch()) {
                  echo "<option value='" . htmlspecialchars($method['account_id']) . "'>" .htmlspecialchars($method['account_name']) ." | ".htmlspecialchars($method['method_name']) . "</option>";
              }
              ?>
          
            </select>
          </div>

            <h6> Narrative/ comments</h6>
          <div class="form-group">
          <textarea
    rows="4"
    class="form-control"
    name="narrative"
    placeholder="Write your message here..."
    required></textarea>

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



