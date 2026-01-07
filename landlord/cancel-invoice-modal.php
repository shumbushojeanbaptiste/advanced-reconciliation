<div class="modal fade modal-full<?php echo $rows1['invoice_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel<?php echo $rows1['invoice_id']; ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white">
        <h6 class="modal-title" id="cancelModalLabel<?php echo $rows1['invoice_id']; ?>">Cancel Invoice</h6>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>

      <form id="form_validation" method="POST" action="">
        <div class="modal-body text-center">
          <p>
            Canceling Invoice  
            <strong><?php echo isset($rows1['invoice']) ? htmlspecialchars($rows1['invoice']) : 'N/A'; ?></strong> 
            for 
            <strong><?php echo isset($tenant['te_names']) ? htmlspecialchars($tenant['te_names']) : 'Unknown Tenant'; ?></strong>.
          </p>

          <input type="hidden" name="inv_id" value="<?php echo $rows1['invoice_id']; ?>">
          <input type="hidden" name="invo_no" value="<?php echo $rows1['invoice']; ?>">
          <input type="hidden" name="keyword" value="<?php echo $_REQUEST['keyword1']; ?>">

          <div class="form-group">
            <textarea class="form-control" name="cancel1" placeholder="Reason for cancellation" rows="3"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger btn-sm" name="cancel">Cancel Invoice</button>
        </div>
      </form>
    </div>
  </div>
</div>
