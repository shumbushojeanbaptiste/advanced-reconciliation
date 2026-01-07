<?php
require_once("../include/initialize.php");

// Input: expected 'tenantly' as YYYY-MM (like 2025-11) or a full date; fallback to current year-month
$tenantly_raw = isset($_REQUEST['tenantly']) && $_REQUEST['tenantly'] !== '' ? trim($_REQUEST['tenantly']) : date('Y-m');
if (preg_match('/^\d{4}-\d{2}$/', $tenantly_raw)) {
    $month_start = $tenantly_raw . '-01';
    $dt = DateTime::createFromFormat('Y-m-d', $month_start);
} else {
    // try parse as full date then set to its month
    $dt = DateTime::createFromFormat('Y-m-d', $tenantly_raw) ?: new DateTime();
    $month_start = $dt->format('Y-m-01');
}
$monthEndDt = (clone $dt)->modify('last day of this month');
$month_end = $monthEndDt->format('Y-m-d');

// date labels for header/export
$date_from = $month_start;
$date_to = $month_end;

// company label (fallbacks if not set)
$company = isset($company) ? $company : (isset($_REQUEST['company']) ? $_REQUEST['company'] : '');
$cmp_full = isset($cmp_full) ? $cmp_full : (isset($_REQUEST['cpny']) ? $_REQUEST['cpny'] : $company);

// Export link uses computed month range
$export_url = "export_general_rpt.php?date_from=" . urlencode($date_from) . "&date_to=" . urlencode($date_to) . "&cpny=" . urlencode($cmp_full);
?>
<a href="<?php echo $export_url; ?>"><!-- Export link (hidden if you prefer) --></a>

<div class="table-responsive">
 <div class="module-body table">
    <table class="table table-hover" width="100%">
      <thead>
         <tr>
            <th colspan="6"><?php echo htmlspecialchars($cmp_full); ?> General Report<br>
            Date From: <?php echo htmlspecialchars($date_from); ?><br>
            Date To: <?php echo htmlspecialchars($date_to); ?>
            </th>
         </tr>
         <tr>
           <th>#</th>
           <th>Tenant Names</th>
           <th>Monthly Invoice</th>
           <th>Payment</th>
           <th>Balance (month)</th>
           <th>Rooms</th>
         </tr>
      </thead>
      <tbody>
<?php
try {
    // Optimized query: single pass per tenant with subqueries for sums and rooms
    $sql = "
    SELECT t.ten_id, t.ten_ref_no, t.te_names,
      (SELECT IFNULL(SUM(inv.invoice_fees),0) FROM tbl_invoice inv
         WHERE inv.inv_ref_no = t.ten_ref_no AND inv.invoice_date BETWEEN :m_start AND :m_end
      ) AS monthly_invoice,
      (SELECT IFNULL(SUM(pay.pay_amount),0) FROM tbl_payment pay
         WHERE pay.pay_ref_no = t.ten_ref_no AND pay.pay_due_date BETWEEN :m_start AND :m_end AND (pay.status IS NULL OR pay.status != 0)
      ) AS monthly_payment,
      (SELECT IFNULL(SUM(inv.invoice_fees),0) FROM tbl_invoice inv WHERE inv.inv_ref_no = t.ten_ref_no) AS total_invoice,
      (SELECT IFNULL(SUM(pay.pay_amount),0) FROM tbl_payment pay WHERE pay.pay_ref_no = t.ten_ref_no AND (pay.status IS NULL OR pay.status != 0)) AS total_payment,
      (SELECT IFNULL(GROUP_CONCAT(r.room_no SEPARATOR ', '),'') FROM tbl_assign a JOIN tbl_rooms r ON r.room_id = a.room_id
         WHERE a.tent_id = t.ten_id AND a.assgn_status = 1
      ) AS rooms
    FROM tbl_tenants t
    WHERE t.ten_staff = :company
      AND t.ten_id NOT IN (SELECT tenant_id FROM tbl_tenant_leave)
    ORDER BY t.te_names ASC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':m_start' => $month_start,
        ':m_end'   => $month_end,
        ':company' => $company
    ]);

    $i = 0;
    $totalInvoice = 0.0;
    $totalPayment = 0.0;
    $totalBalance = 0.0;

    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $i++;
        $monthly_invoice = (float)$r['monthly_invoice'];
        $monthly_payment = (float)$r['monthly_payment'];
        $balance_month = $monthly_invoice - $monthly_payment;

        $totalInvoice += $monthly_invoice;
        $totalPayment += $monthly_payment;
        $totalBalance += $balance_month;
        ?>
        <tr class="odd gradeX">
          <td><?php echo $i; ?></td>
          <td><?php echo htmlspecialchars($r['te_names']); ?></td>
          <td><?php echo number_format($monthly_invoice, 2); ?></td>
          <td><?php echo number_format($monthly_payment, 2); ?></td>
          <td><?php echo number_format($balance_month, 2); ?></td>
          <td><?php echo htmlspecialchars($r['rooms']); ?></td>
        </tr>
        <?php
    }

} catch (PDOException $ex) {
    echo '<tr><td colspan="6" class="text-danger">Query error: ' . htmlspecialchars($ex->getMessage()) . '</td></tr>';
}
?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><strong>Totals</strong></td>
          <td><strong><?php echo number_format($totalInvoice, 2); ?></strong></td>
          <td><strong><?php echo number_format($totalPayment, 2); ?></strong></td>
          <td><strong><?php echo number_format($totalBalance, 2); ?></strong></td>
          <td></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

