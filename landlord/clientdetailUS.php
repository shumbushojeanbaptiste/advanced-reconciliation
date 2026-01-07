<?php
require_once("../include/initialize.php");

//  Sanitize input
$ten = isset($_REQUEST['ten']) ? trim($_REQUEST['ten']) : '';
if (empty($ten)) {
    die("<div class='alert alert-danger'>Missing tenant reference number.</div>");
}

$date_from = "2018-01-01";
$date_to = date("Y-m-d");

// Use parameterized queries
$stmt = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_ref_no = :ten_ref_no");
$stmt->execute([':ten_ref_no' => $ten]);
$tenant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tenant) {
    die("<div class='alert alert-warning'>Tenant not found.</div>");
}
?>

<style>
#clientpaiddetails {
    border: 1px solid #999;
    font-size: 11px;
}
</style>

<div class="module-body table">
    <div class="table-responsive">
        <h4><?php echo htmlspecialchars($tenant['te_names']); ?></h4>
        <table class="table table-hover" id="clientpaiddetails" width="100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice</th>
                    <th>Payment</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Combine invoice + payment dates (chronological)
                $query_dates = "
                    SELECT invoice_date AS date FROM tbl_invoice 
                    WHERE inv_ref_no = :ten AND invoice_date BETWEEN :from AND :to
                    UNION
                    SELECT pay_due_date AS date FROM tbl_payment 
                    WHERE pay_ref_no = :ten AND pay_due_date BETWEEN :from AND :to
                    ORDER BY date ASC
                ";

                $stmt_dates = $conn->prepare($query_dates);
                $stmt_dates->execute([':ten' => $ten, ':from' => $date_from, ':to' => $date_to]);
                $dates = $stmt_dates->fetchAll(PDO::FETCH_ASSOC);

                $tot_invoice = 0;
                $tot_payment = 0;

                foreach ($dates as $d) {
                    $date = $d['date'];

                    // 🔹 Get total invoice for that date
                    $stmt_inv = $conn->prepare("
                        SELECT SUM(invoice_fees / unit_value) AS total_invoice
                        FROM tbl_invoice 
                        WHERE inv_ref_no = :ten AND invoice_date = :date
                    ");
                    $stmt_inv->execute([':ten' => $ten, ':date' => $date]);
                    $invoiceAMT = $stmt_inv->fetchColumn() ?: 0;

                    // 🔹 Get total payment for that date
                    $stmt_pay = $conn->prepare("
                        SELECT SUM(pay_amount / c_value) AS total_payment
                        FROM tbl_payment 
                        WHERE pay_ref_no = :ten AND pay_due_date = :date
                    ");
                    $stmt_pay->execute([':ten' => $ten, ':date' => $date]);
                    $paymentAMT = $stmt_pay->fetchColumn() ?: 0;

                    $tot_invoice += $invoiceAMT;
                    $tot_payment += $paymentAMT;
                    $balance = $tot_invoice - $tot_payment;
                    ?>
                    <tr>
                        <td><?php echo date("d F, Y", strtotime($date)); ?></td>
                        <td><?php echo $invoiceAMT > 0 ? number_format($invoiceAMT, 2) : "-"; ?></td>
                        <td><?php echo $paymentAMT > 0 ? number_format($paymentAMT, 2) : "-"; ?></td>
                        <td><?php echo number_format($balance, 2); ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td><b>TOTAL:</b></td>
                    <td><b><?php echo number_format($tot_invoice, 2); ?></b></td>
                    <td><b><?php echo number_format($tot_payment, 2); ?></b></td>
                    <td>
                        <?php 
                        $final_balance = $tot_invoice - $tot_payment;
                        if ($final_balance >= 0) {
                            echo "<b>" . number_format($final_balance, 2) . "</b>";
                        } else {
                            echo "<b class='text-danger'>" . number_format(abs($final_balance), 2) . "</b>";
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
