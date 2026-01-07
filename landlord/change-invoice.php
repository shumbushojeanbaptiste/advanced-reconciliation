<style>
.icon-rtl {
  border-radius: 20px;
  border: 1px solid #2d9fd9;
  color: #a0d18c;
 
  height: 35px;
  padding-left: 10px;
  background: url("../images/search.png") no-repeat right 8px center;
  background-size: 25px;
  transition: 0.3s;
}
.icon-rtl:focus {
  outline: none;
  border-color: #a0d18c;
  color: #2d9fd9;
}
</style>

<?php
if (isset($_POST['update_invoice'])) {
    try {
        $invoice_id = $_POST['invoice_id'];
        $amount = $_POST['invoice_amount'];
        $invoice_date = $_POST['invoice_date'];
        $invoice_comment = $_POST['invoice_comment'];
        $keyword = $_POST['keyword'];

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ✅ Get current invoice info
        $stms = $conn->prepare("SELECT invoice_fees FROM tbl_invoice WHERE invoice_id = ?");
        $stms->execute([$invoice_id]);
        $satarow = $stms->fetch(PDO::FETCH_ASSOC);

        if ($satarow) {
            $amountchanged = $satarow['invoice_fees'];
            $date1 = date("Y-m-d");
            $des = "changed";

            // ✅ Log change
            $sql12 = $conn->prepare("
                INSERT INTO tbl_changed_invo (amount, done_by, due, cmt, inv_id)
                VALUES (?, ?, ?, ?, ?)
            ");
            $sql12->execute([$amountchanged, $_SESSION['USER_ID'], $date1, $des, $invoice_id]);

            // ✅ Update invoice
            $sql = $conn->prepare("
                UPDATE tbl_invoice 
                SET invoice_fees = ?, invoice_date = ?, inv_desc = ?
                WHERE invoice_id = ?
            ");
            $sql->execute([$amount, $invoice_date, $invoice_comment, $invoice_id]);

            $msg = "Invoice updated successfully.";
            echo '<meta http-equiv="refresh" content="2;URL=index?file=change-invoice&keyword1=' . $keyword . '">';
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

if (isset($_POST['cancel'])) {
    try {
        $cancel = $_POST['cancel1'];
        $inv_id = $_POST['inv_id'];
        $slip = $_POST['invo_no'];
        $keyword = $_POST['keyword'];
        $date1 = date("Y-m-d");
        $des = "removed";

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ✅ Log old data before delete
        $stms = $conn->prepare("SELECT invoice_fees FROM tbl_invoice WHERE invoice_id = ?");
        $stms->execute([$inv_id]);
        $satarow = $stms->fetch(PDO::FETCH_ASSOC);

        if ($satarow) {
            $sql12 = $conn->prepare("
                INSERT INTO tbl_changed_invo (amount, done_by, due, cmt)
                VALUES (?, ?, ?, ?)
            ");
            $sql12->execute([$satarow['invoice_fees'], $_SESSION['USER_ID'], $date1, $des]);

            // ✅ Delete invoice
            $sqld = $conn->prepare("DELETE FROM tbl_invoice WHERE invoice_id = ?");
            $sqld->execute([$inv_id]);

            $msge = "$slip Invoice successfully cancelled.";
            echo '<meta http-equiv="refresh" content="2;URL=index?file=change-invoice&keyword1=' . $keyword . '">';
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="row">
<?php if (!empty($msg)): ?>
  <div class="alert alert-success">
    <strong>✔️ Success:</strong> <?= htmlspecialchars($msg) ?>
  </div>
<?php elseif (!empty($msge)): ?>
  <div class="alert alert-danger">
    <strong>⚠️ Error:</strong> <?= htmlspecialchars($msge) ?>
  </div>
<?php endif; ?>

<div class="col-md-12 mt-4">
  <div class="card shadow mb-4">
    <div class="card-body">
      <!-- 🔍 Search box full width -->
      <div class="form-row">
        <div class="col-12">
          <input type="text" 
                 class="form-control icon-rtl" 
                 id="search-box" 
                 placeholder="🔍 Search by Tenant Name or Phone">
        </div>
      </div>

      <!-- 📦 Suggestion box full width -->
      <div class="form-row mt-2">
        <div class="col-12" id="suggesstion-box"></div>
      </div>
    </div>



      <div id="resultedOK">
      <?php
      if (!empty($_REQUEST['keyword1'])) {
          $keyword1 = $_REQUEST['keyword1'];

          $stms = $db->prepare("SELECT * FROM tbl_invoice WHERE inv_ref_no = ? GROUP BY invoice_date");
          $stms->execute([$keyword1]);
          $row_count = $stms->rowCount();

          $stms2 = $db->prepare("SELECT * FROM tbl_tenants WHERE ten_ref_no = ?");
          $stms2->execute([$keyword1]);
          $tenant = $stms2->fetch(PDO::FETCH_ASSOC);

          if ($tenant) {
              $currency_id = $tenant['currency_id'];
              $moneyStmt = $db->prepare("SELECT intoR FROM tbl_money WHERE money_id = ?");
              $moneyStmt->execute([$currency_id]);
              $money = $moneyStmt->fetch(PDO::FETCH_ASSOC);
              $converted = $money['intoR'] ?? 1;

              $i = 0;
              $tot = 0;
      ?>
    

      <div class="card-header">
        <strong class="card-title"><?= htmlspecialchars($tenant['te_names']) ?></strong>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless" style="font-size:11px;">
          <thead>
            <tr>
              <th>No.</th>
              <th>Date</th>
              <th>Shop</th>
              <th>Description</th>
              <th>Invoice Type</th>
              <th>Invoice AMT</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
          while ($rows = $stms->fetch(PDO::FETCH_ASSOC)):
              $i++;
              $pay_due_date = $rows['invoice_date'];

              $stms1 = $db->prepare("
                  SELECT i.*, r.room_no
                  FROM tbl_invoice i
                  JOIN tbl_rooms r ON i.inv_room_id = r.room_id
                  WHERE i.invoice_date = ? AND i.inv_ref_no = ?
              ");
              $stms1->execute([$pay_due_date, $keyword1]);
              $row_count1 = $stms1->rowCount();

              while ($rows1 = $stms1->fetch(PDO::FETCH_ASSOC)):
                  $tot += ($rows1['invoice_fees'] / $rows1['unit_value']);
          ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= htmlspecialchars($rows1['invoice_date']) ?></td>
            <td><?= htmlspecialchars($rows1['room_no']) ?></td>
            <td><?= htmlspecialchars($rows1['inv_desc']) ?></td>
            <td><?= $rows1['auto'] === 'yes' ? 'Monthly' : 'Special' ?></td>
            <td><?= number_format($rows1['invoice_fees'] / $rows1['unit_value'], 2) ?></td>
            <td>
              <div class="dropdown">
                <button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                  Action
                </button>
                <div class="dropdown-menu">
                  <a href="#" data-toggle="modal" data-target="#modal-edit<?= $rows1['invoice_id'] ?>" class="dropdown-item text-primary"><i class="fe fe-edit" title="edit"></i></a>
                  <a href="#" data-toggle="modal" data-target=".modal-full<?= $rows1['invoice_id'] ?>" class="dropdown-item text-danger"><i class="fe fe-trash" title="cancel"></i></a>
                </div>
              </div>
            </td>
          </tr>
           <?php  include 'invoice_edit.php'; ?>
            <?php  include 'cancel-invoice-modal.php';?>
          <?php endwhile; endwhile; ?>
          <tr>
            <td><b>Total:</b></td><td colspan="4"></td>
            <td><b><?= $currency_id == 2 ? $tot . " USD" : number_format($tot) . " RWF" ?></b></td>
            <td></td>
          </tr>
          </tbody>
        </table>
      </div>
      <?php } else { ?>
        <div class="alert alert-info text-center mt-3">No Data Found</div>
      <?php } } ?>
      </div>
    </div>
  </div>
</div>
</div>

<script>
$(document).ready(function(){
  $("#search-box").keyup(function(){
    $.ajax({
      type: "POST",
      url: "load_slip_search_result2",
      data: { keyword1: $(this).val() },
      success: function(data){
        $("#suggesstion-box").show().html(data);
        $("#resultedOK").hide();
      }
    });
  });
});

function selecttbl_admission2(val) {
  $("#search-box").val(val);
  window.location.href = "index?file=change-invoice&keyword1=" + val;
  $("#suggesstion-box").hide();
}
</script>
