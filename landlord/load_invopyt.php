<?php
require_once("../include/initialize.php");

// Get dates from form safely
$date_from = $_REQUEST['date_from'] ?? '';
$date_to = $_REQUEST['date_to'] ?? '';
$date_to1 = $date_to . '-31';

// Ensure `$cmp_full` is defined
$cmp_full = $cmp_full ?? 'Company Name';

?>
<a href="export_recoveylist.php?date_from=<?php echo urlencode($date_from); ?>&date_to=<?php echo urlencode($date_to); ?>&cpny=<?php echo urlencode($cmp_full); ?>">
    <button class="btn btn-success pull-left" style="margin:7px;">Print Excel</button>
</a>

<div class="table-responsive">
    <div class="module-body table">
        <table class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th colspan="9"><?php echo htmlspecialchars($cmp_full); ?> Monthly Invoice<br>Month: <?php echo htmlspecialchars($date_to); ?></th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Room</th>
                    <th>Invoice</th>
                    <th>Payment</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;

                // Get distinct invoice references
                $sqlinvo = $db->prepare("SELECT DISTINCT inv_ref_no FROM tbl_invoice WHERE invoice_date LIKE :date_to AND serve_id = 4 AND cpny_ID = 9");
                $sqlinvo->execute(['date_to' => $date_to . '%']);

                while ($rowinvo = $sqlinvo->fetch(PDO::FETCH_ASSOC)) {
                    $i++;
                    $inv_ref_no = $rowinvo['inv_ref_no'];

                    $sqlinvodet = $db->prepare("
                        SELECT SUM(invoice_fees) AS invoice_fees, inv_room_id, inv_ref_no
                        FROM tbl_invoice
                        WHERE invoice_date LIKE :date_to AND serve_id = 4 AND inv_ref_no = :inv_ref_no
                        GROUP BY inv_room_id
                    ");
                    $sqlinvodet->execute(['date_to' => $date_to . '%', 'inv_ref_no' => $inv_ref_no]);
                    $countassgn = $sqlinvodet->rowCount();
                    $firstRow = true;

                    while ($rowinvodet = $sqlinvodet->fetch(PDO::FETCH_ASSOC)) {
                        $room_id = $rowinvodet['inv_room_id'];

                        // Tenant info
                        $sqlten = $db->prepare("SELECT * FROM tbl_tenants WHERE ten_ref_no = :ten_ref_no");
                        $sqlten->execute(['ten_ref_no' => $inv_ref_no]);
                        $rowten = $sqlten->fetch(PDO::FETCH_ASSOC) ?? ['te_names' => 'Unknown'];

                        // Room info
                        $sqlroom = $db->prepare("SELECT * FROM tbl_rooms WHERE room_id = :room_id");
                        $sqlroom->execute(['room_id' => $room_id]);
                        $rowroom = $sqlroom->fetch(PDO::FETCH_ASSOC) ?? ['room_id' => 0, 'room_no' => 'N/A'];

                        // Payment info
                        $sqlinvopyt = $db->prepare("
                            SELECT SUM(pay_amount) AS total_paid
                            FROM tbl_payment
                            WHERE status = 1 AND pay_ref_no = :tenid AND pay_room_id = :pay_room_id AND invoice_date LIKE :date_like
                        ");
                        $sqlinvopyt->execute([
                            'tenid' => $inv_ref_no,
                            'pay_room_id' => $rowroom['room_id'],
                            'date_like' => $date_to . '%'
                        ]);
                        $rowinvopyt = $sqlinvopyt->fetch(PDO::FETCH_ASSOC);
                        $amountPaid = $rowinvopyt['total_paid'] ?? 0;

                        ?>
                        <tr>
                            <?php if ($firstRow): ?>
                                <td rowspan="<?php echo $countassgn; ?>"><?php echo $i; ?></td>
                                <td rowspan="<?php echo $countassgn; ?>"><?php echo htmlspecialchars($rowten['te_names']); ?></td>
                            <?php endif; ?>
                            <td><?php echo htmlspecialchars($rowroom['room_no']); ?></td>
                            <td><?php echo number_format($rowinvodet['invoice_fees'], 0); ?></td>
                            <td><?php echo number_format($amountPaid, 2); ?></td>
                            <td><?php echo number_format(($rowinvodet['invoice_fees'] - $amountPaid), 0); ?></td>
                        </tr>
                        <?php
                        $firstRow = false;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
