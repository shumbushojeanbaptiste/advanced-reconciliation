
<div class="modal fade modal-full<?php echo $ten_id; ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
<button aria-label="" type="button" class="close px-2" data-dismiss="modal" aria-hidden="true">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
    <div class="modal-body text-center">
      <p> Messaging To <?php echo $fetch['te_names']; ?>. </p>
      <form method="post" action="" >
          <div class="form-group col-md-12">
              <input type="hidden" name="tenant" value="<?php echo $ten_id; ?>">
        <textarea class="form-control form-control-lg mr-sm-4" name="tenSMSx" type="search" readonly placeholder="<?php echo $msg1; ?>" aria-label="" rows="4"></textarea>
        <input type="hidden" name="tenSMS" value="<?php  echo $msg1; ?>">
        <input type="hidden" name="phone" value="<?php echo $call?>">
        </div>
        <button type="submit" class="btn mb-2 btn-outline-warning btn-sm" name="send" >Send</button>
      </form>
    </div>
  </div>
</div>
</div> <!-- small modal -->

                      