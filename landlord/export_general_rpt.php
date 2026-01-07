<?php
// Excel headers
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=" . (isset($_REQUEST['cpny']) ? preg_replace('/[^\w\-\. ]/', '_', $_REQUEST['cpny']) : 'general') . "_general_report.xls");

require_once ("../include/initialize.php");

// safe date parsing
function safe_date($d, $fallback) {
    $dt = DateTime::createFromFormat('Y-m-d', $d);
    return $dt ? $dt->format('Y-m-d') : $fallback;
}

$date_from = isset($_REQUEST['date_from']) ? safe_date($_REQUEST['date_from'], date('Y-m-01')) : date('Y-m-01');
$date_to   = isset($_REQUEST['date_to'])   ? safe_date($_REQUEST['date_to'],   date('Y-m-t'))  : date('Y-m-t');

// company / label fallbacks
$cmp_full = isset($_REQUEST['cpny']) ? $_REQUEST['cpny'] : (isset($cmp_full) ? $cmp_full : '');
$company_param = isset($company) ? $company : (isset($_REQUEST['company']) ? $_REQUEST['company'] : '');

// Prepared optimized query (same logic as load_quick_report)
$sql = "
SELECT t.ten_id, t.ten_ref_no, t.te_names, t.ten_phone,
  (SELECT IFNULL(SUM(r.price - a.discount),0)
     FROM tbl_assign a
     JOIN tbl_rooms r ON r.room_id = a.room_id
    WHERE a.assgn_status = 1
      AND a.tent_id = t.ten_id
      AND r.availability = 4
  ) AS pay_by_month,
  (SELECT IFNULL(SUM(inv.invoice_fees),0)
     FROM tbl_invoice inv
    WHERE inv.inv_ref_no = t.ten_ref_no
      AND inv.auto = 'yes'
      AND inv.invoice_date BETWEEN :date_from AND :date_to
  ) AS monthly_invoice,
  (SELECT IFNULL(SUM(inv.invoice_fees),0)
     FROM tbl_invoice inv
    WHERE inv.inv_ref_no = t.ten_ref_no
      AND inv.auto = 'no'
      AND inv.invoice_date BETWEEN :date_from AND :date_to
  ) AS special_invoice,
  (SELECT IFNULL(SUM(pay.pay_amount),0)
     FROM tbl_payment pay
    WHERE pay.pay_ref_no = t.ten_ref_no
      AND pay.pay_due_date BETWEEN :date_from AND :date_to
  ) AS payment_in_range,
  (SELECT IFNULL(SUM(inv.invoice_fees),0)
     FROM tbl_invoice inv
    WHERE inv.inv_ref_no = t.ten_ref_no
  ) AS total_invoice,
  (SELECT IFNULL(SUM(pay.pay_amount),0)
     FROM tbl_payment pay
    WHERE pay.pay_ref_no = t.ten_ref_no
  ) AS total_payment,
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
    ':company'   => $company_param
]);

// Totals
$aa = 0;
$tot_paymon = $tot_inv_yes = $tot_inv_no = $tot_pay = $gen_total = $diff_total = 0.0;

?>
<div class="table-responsive">
  <div class="module-body table">
    <table border="1" width="100%" style="font-size:13px; font-family:Bookman Old Style;">
      <thead>
        <tr>
          <th colspan="11"><?php echo htmlspecialchars($cmp_full); ?> General Report<br>
            Date From: <?php echo htmlspecialchars($date_from); ?> &nbsp; To: <?php echo htmlspecialchars($date_to); ?>
          </th>
        </tr>
        <tr>
          <th>#</th>
          <th>Tenant</th>
          <th>Room</th>
          <th>Tenant phone</th>
          <th>Pay By Month</th>
          <th>Monthly Invoice</th>
          <th>Special Invoice</th>
          <th>Payment (range)</th>
          <th>Total Invoice (range)</th>
          <th>Balance (range)</th>
          <th>G.Balance</th>
        </tr>
      </thead>
      <tbody>
<?php
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $aa++;
    $pay_by_month = (float)$row['pay_by_month'];
    $monthly_invoice = (float)$row['monthly_invoice'];
    $special_invoice = (float)$row['special_invoice'];
    $payment_in_range = (float)$row['payment_in_range'];
    $total_invoice = (float)$row['total_invoice'];
    $total_payment = (float)$row['total_payment'];

    $range_total_inv = $monthly_invoice + $special_invoice;
    $range_balance = $range_total_inv - $payment_in_range;
    $gbalance = $total_invoice - $total_payment;

    // accumulate totals
    $tot_paymon += $pay_by_month;
    $tot_inv_yes += $monthly_invoice;
    $tot_inv_no += $special_invoice;
    $tot_pay += $payment_in_range;
    $gen_total += $range_total_inv;
    $diff_total += $range_balance;
    ?>
    <tr>
      <td><?php echo $aa; ?></td>
      <td><?php echo htmlspecialchars($row['te_names']); ?></td>
      <td><?php echo htmlspecialchars($row['rooms']); ?></td>
      <td><?php echo htmlspecialchars($row['ten_phone']); ?></td>
      <td><?php echo number_format($pay_by_month, 2); ?></td>
      <td><?php echo number_format($monthly_invoice, 2); ?></td>
      <td><?php echo number_format($special_invoice, 2); ?></td>
      <td><?php echo number_format($payment_in_range, 2); ?></td>
      <td><?php echo number_format($range_total_inv, 2); ?></td>
      <td><?php echo number_format($range_balance, 2); ?></td>
      <td><?php echo number_format($gbalance, 2); ?></td>
    </tr>
<?php
}
?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4"><strong>Total</strong></td>
          <td><?php echo number_format($tot_paymon, 2); ?></td>
          <td><?php echo number_format($tot_inv_yes, 2); ?></td>
          <td><?php echo number_format($tot_inv_no, 2); ?></td>
          <td><?php echo number_format($tot_pay, 2); ?></td>
          <td><?php echo number_format($gen_total, 2); ?></td>
          <td><?php echo number_format($diff_total, 2); ?></td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>