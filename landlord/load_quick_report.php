<?php
require_once ("../include/initialize.php");

// sanitize dates (expect YYYY-MM-DD). fallback to today if invalid.
$date_from_raw = isset($_REQUEST['date_from']) ? $_REQUEST['date_from'] : date('Y-m-01');
$date_to_raw   = isset($_REQUEST['date_to'])   ? $_REQUEST['date_to']   : date('Y-m-t');

function safe_date($d, $fallback) {
    $dt = DateTime::createFromFormat('Y-m-d', $d);
    return $dt ? $dt->format('Y-m-d') : $fallback;
}
$date_from = safe_date($date_from_raw, date('Y-m-01'));
$date_to   = safe_date($date_to_raw, date('Y-m-t'));

// company label variables (ensure they exist)
$company = isset($company) ? $company : (isset($cmp_full) ? $cmp_full : '');
$cmp_full = isset($cmp_full) ? $cmp_full : $company;

// Export button
?>
<a href="export_general_rpt.php?date_from=<?php echo urlencode($date_from); ?>&date_to=<?php echo urlencode($date_to); ?>&cpny=<?php echo urlencode($cmp_full); ?>">
    <button class="btn btn-success pull-left" style="margin:7px;">Print Excel</button>
</a>

<div class="table-responsive">
  <div class="module-body table">
    <table class="table table-hover" width="100%">
      <thead>
        <tr>
          <th colspan="11"><?php echo htmlspecialchars($cmp_full); ?> General Report<br>
            Date From: <?php echo htmlspecialchars($date_from); ?><br>
            Date To: <?php echo htmlspecialchars($date_to); ?>
          </th>
        </tr>
        <tr>
          <th>#</th>
          <th>Tenant</th>
          <th>Tenant phone</th>
          <th>Pay By Month</th>
          <th>Monthly Invoice</th>
          <th>Special Invoice</th>
          <th>Payment (range)</th>
          <th>Total Invoice</th>
          <th>Balance</th>
          <th>G.Balance</th>
          <th>Room</th>
        </tr>
      </thead>
      <tbody>
<?php
// Optimized single-query per-tenant with subqueries to avoid per-row queries
$sql = "
SELECT t.ten_id, t.ten_ref_no, t.te_names, t.ten_phone,
  -- pay by month (sum of room prices - discount) for assigned rooms with availability = 4 and active assignment
  (SELECT IFNULL(SUM(r.price - a.discount),0)
     FROM tbl_assign a
     JOIN tbl_rooms r ON r.room_id = a.room_id
    WHERE a.assgn_status = 1
      AND a.tent_id = t.ten_id
      AND r.availability = 4
  ) AS pay_by_month,

  -- monthly invoices within date range (auto = 'yes')
  (SELECT IFNULL(SUM(inv.invoice_fees),0)
     FROM tbl_invoice inv
    WHERE inv.inv_ref_no = t.ten_ref_no
      AND inv.auto = 'yes'
      AND inv.invoice_date BETWEEN :date_from AND :date_to
  ) AS monthly_invoice,

  -- special invoices within date range (auto = 'no')
  (SELECT IFNULL(SUM(inv.invoice_fees),0)
     FROM tbl_invoice inv
    WHERE inv.inv_ref_no = t.ten_ref_no
      AND inv.auto = 'no'
      AND inv.invoice_date BETWEEN :date_from AND :date_to
  ) AS special_invoice,

  -- payments within date range
  (SELECT IFNULL(SUM(pay.pay_amount),0)
     FROM tbl_payment pay
    WHERE pay.pay_ref_no = t.ten_ref_no
      AND pay.pay_due_date BETWEEN :date_from AND :date_to
  ) AS payment_in_range,

  -- total invoices (all time)
  (SELECT IFNULL(SUM(inv.invoice_fees),0)
     FROM tbl_invoice inv
    WHERE inv.inv_ref_no = t.ten_ref_no
  ) AS total_invoice,

  -- total payments (all time)
  (SELECT IFNULL(SUM(pay.pay_amount),0)
     FROM tbl_payment pay
    WHERE pay.pay_ref_no = t.ten_ref_no
  ) AS total_payment,

  -- rooms assigned (comma-separated)
  (SELECT IFNULL(GROUP_CONCAT(r.room_no SEPARATOR ', '),'')
     FROM tbl_assign a
     JOIN tbl_rooms r ON r.room_id = a.room_id
    WHERE a.tent_id = t.ten_id
      AND a.assgn_status = 1
  ) AS rooms
FROM tbl_tenants t
WHERE t.ten_staff = :company
  AND t.ten_id NOT IN (SELECT tenant_id FROM tbl_tenant_leave)
  AND t.ten_id IN (SELECT tent_id FROM tbl_assign)
ORDER BY t.te_names ASC
";

$stmt = $db->prepare($sql);
$stmt->execute([
    ':date_from' => $date_from,
    ':date_to'   => $date_to,
    ':company'   => $company
]);

// Totals for footer
$tot_paymon = 0.0;
$tot_inv_yes = 0.0;
$tot_inv_no = 0.0;
$tot_pay = 0.0;
$gen_total = 0.0;
$diff_total = 0.0;
$aa = 0;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $aa++;
    $ten_id = $row['ten_id'];
    $ten_ref = $row['ten_ref_no'];

    $pay_by_month = (float)$row['pay_by_month'];
    $monthly_invoice = (float)$row['monthly_invoice'];
    $special_invoice = (float)$row['special_invoice'];
    $payment_in_range = (float)$row['payment_in_range'];
    $total_invoice = (float)$row['total_invoice'];
    $total_payment = (float)$row['total_payment'];

    // totals accumulation
    $tot_paymon += $pay_by_month;
    $tot_inv_yes += $monthly_invoice;
    $tot_inv_no += $special_invoice;
    $tot_pay += $payment_in_range;
    $gen_total += ($monthly_invoice + $special_invoice);
    $diff = ($monthly_invoice + $special_invoice) - $payment_in_range;
    $diff_total += $diff;

    // global balance (all-time invoices - all-time payments)
    $gbalance = $total_invoice - $total_payment;

    // rooms list
    $rooms_html = htmlspecialchars($row['rooms']);

    ?>
    <tr class="odd gradeX" data-toggle="modal" data-target="#modal-paydetails" onclick="selectclient_details('<?php echo htmlspecialchars($ten_ref); ?>')" style="cursor: pointer;">
      <td><?php echo $aa; ?></td>
      <td><?php echo htmlspecialchars($row['te_names']); ?></td>
      <td><?php echo htmlspecialchars($row['ten_phone']); ?></td>
      <td><?php echo number_format($pay_by_month, 2); ?></td>
      <td><?php echo number_format($monthly_invoice, 2); ?></td>
      <td><?php echo number_format($special_invoice, 2); ?></td>
      <td><?php echo number_format($payment_in_range, 2); ?></td>
      <td><?php echo number_format($monthly_invoice + $special_invoice, 2); ?></td>
      <td><?php echo number_format($diff, 2); ?></td>
      <td><?php echo number_format($gbalance, 2); ?></td>
      <td><?php echo $rooms_html; ?></td>
    </tr>
    <?php
}
?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3">Total</td>
          <td align="left"><?php echo number_format($tot_paymon, 2); ?></td>
          <td align="left"><?php echo number_format($tot_inv_yes, 2); ?></td>
          <td align="left"><?php echo number_format($tot_inv_no, 2); ?></td>
          <td align="left"><?php echo number_format($tot_pay, 2); ?></td>
          <td align="left"><?php echo number_format($gen_total, 2); ?></td>
          <td align="left"><?php echo number_format($diff_total, 2); ?></td>
          <td align="left"></td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<script>
function selectclient_details(val1) {
    $('#fetching').html("<img src='../images/ajax_loader.gif' width='15'>").fadeIn('fast');
    $.get('clientdetailUS.php?ten=' + encodeURIComponent(val1), function (data22) {
        $('#datacomes').html(data22);
        $('#fetching').html("#" + val1).fadeIn('fast');
    });
}
</script>