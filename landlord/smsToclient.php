<?php
// Ensure $rows and $namesEq exist before using them
$liDate = isset($rows['li_date']) ? $rows['li_date'] : null;
$newDate = $liDate ? date("d F Y", strtotime($liDate)) : '';
$newDate2 = $liDate ? date("F Y", strtotime($liDate)) : '';
$linkText = isset($rows['link_text']) ? $rows['link_text'] : '';
$receivedPhone = isset($rows['receiced_phone']) ? $rows['receiced_phone'] : '';
$linkId = isset($rows['link_id']) ? (int)$rows['link_id'] : 0;
$tenantNameSafe = isset($namesEq) ? htmlspecialchars($namesEq) : 'Tenant';
?>

<div class="modal fade modal-full" id="viewsms<?= $linkId ?>" tabindex="-1" role="dialog" aria-labelledby="viewsmsLabel<?= $linkId ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewsmsLabel<?= $linkId ?>">Send Bill Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body text-center">
        <p>
          Sending bill link to <strong><?= $tenantNameSafe ?></strong>
          <?= $newDate2 ? " for <strong>$newDate2</strong>." : '.' ?>
        </p>

        <form class="form-inline justify-content-center" method="POST" action="">
          <input type="hidden" name="period" value="<?= htmlspecialchars($newDate2) ?>">
          <input type="hidden" name="linktext" value="<?= htmlspecialchars($linkText) ?>">

          <div class="form-group mb-2 mr-2">
            <input
              class="form-control form-control-lg bg-transparent"
              name="phone"
              type="tel"
              value="<?= htmlspecialchars($receivedPhone) ?>"
              placeholder="Phone number"
              aria-label="Phone">
          </div>

          <button class="btn btn-primary btn-lg mb-2" type="submit" name="sendBill">
            Send
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
