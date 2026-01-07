<?php
require_once("../include/initialize.php");

// Get dates from form
$date_from = $_REQUEST['date_from'] ?? '';
$date_to = $_REQUEST['date_to'] ?? '';
$date_to1 = $date_to . '-31';

// Ensure `$cmp_full` is defined
$cmp_full = $cmp_full ?? 'Company Name';
?>
<a href="export_recoveylist.php?date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>&cpny=<?php echo $cmp_full; ?>">
    <button class="btn btn-success pull-left" style="margin:7px;">Print Excel</button>
</a>
<div class="table-responsive">
    <div class="module-body table">
        <table class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th colspan="9"><?php echo htmlspecialchars($cmp_full); ?> unpaid Invoice<br>Month: <?php echo htmlspecialchars($date_to); ?></th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Room</th>
                    <!--<th>Invoice</th>-->
                    <!--<th>Payment</th>-->
                    <th>Unpaid</th>
                    <th>Payment</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
               <?php
                $tinvoice = 0;
                $tpaid = 0;
                $tinvoicefee = 0;
                $tinvoicefeepaid = 0;
                
                // Query for distinct invoice references
                $sqlinvo = $db->prepare("SELECT DISTINCT(inv_ref_no) FROM tbl_invoice WHERE DATE_FORMAT(invoice_date, '%Y-%m') < :date_to AND serve_id = 4 and cpny_ID = 9");
                $sqlinvo->execute(['date_to' => $date_to]);
                
                $i = 0;
                while ($rowinvo = $sqlinvo->fetch()) {
                    $i++;
                    
                    // Query for invoice details by reference number
                    $sqlinvodet = $db->prepare("SELECT * FROM tbl_invoice WHERE DATE_FORMAT(invoice_date, '%Y-%m') < :date_to AND serve_id = 4 AND inv_ref_no = :inv_ref_no"); 
                    $sqlinvodet->execute(['date_to' => $date_to, 'inv_ref_no' => $rowinvo['inv_ref_no']]);

                    $countassgn = $sqlinvodet->rowCount();
                    $firstRow = true;

                    // Loop through invoice details
                    while ($rowinvodet = $sqlinvodet->fetch()) {
                        $roominvo = $rowinvodet['inv_room_id'];

                        // Get tenant details
                        $sqlten = $db->prepare("SELECT * FROM tbl_tenants WHERE ten_ref_no = :ten_ref_no");
                        $sqlten->execute(['ten_ref_no' => $rowinvodet['inv_ref_no']]);
                        $rowten = $sqlten->fetch();
                        
                        // Get room details
                        $sqlroom = $db->prepare("SELECT * FROM tbl_rooms WHERE room_id = :room_id");
                        $sqlroom->execute(['room_id' => $roominvo]);
                        $rowroom = $sqlroom->fetch(PDO::FETCH_ASSOC);
                        
                        // Payment query fix
                        $sqlinvopyt = $db->prepare(
                            "SELECT SUM(pay_amount) AS total_paid FROM tbl_payment WHERE status IN (1) AND pay_ref_no = :tenid AND pay_room_id = :pay_room_id AND DATE_FORMAT(invoice_date, '%Y-%m') < :date_to"
                        );
                        $sqlinvopyt->execute(['tenid' => $rowinvodet['inv_ref_no'], 'pay_room_id' => $rowroom['room_id'], 'date_to' => $date_to]);
                        $rowinvopyt = $sqlinvopyt->fetch(PDO::FETCH_ASSOC);

                        $amountPaid = $rowinvopyt['total_paid'] ?? 0;
                        $balance = $rowinvodet['invoice_fees'] - $amountPaid;
                        if ($balance > 0) {
                    ?>
                    <tr>
                         <?php if ($firstRow): ?>
                            <td rowspan="<?php echo $countassgn; ?>"><?php echo $i; ?></td>
                            <td rowspan="<?php echo $countassgn; ?>"><?php echo htmlspecialchars($rowten['te_names'] ?? 'Unknown'); ?></td>
                         <?php endif; ?>
                         <td><?php echo htmlspecialchars($rowroom['room_no'] ?? 'N/A'); ?></td>
                        <!--<td><?php echo number_format($rowinvodet['invoice_fees'], 0); ?></td>-->
                        <!--<td><?php echo number_format($amountPaid, 0); ?></td>-->
                        <td><?php echo number_format($balance, 0); ?></td>
                        <?php if ($firstRow): ?>
    <?php
        $sqlinvopyt1 = $db->prepare(
            "SELECT SUM(pay_amount) AS total_paid FROM tbl_payment 
            WHERE status IN (1) AND pay_ref_no = :tenid 
            AND DATE_FORMAT(pay_due_date, '%Y-%m') = :date_to"
        );
        $sqlinvopyt1->execute(['tenid' => $rowinvodet['inv_ref_no'], 'date_to' => $date_to]);

        $rowinvopyt1 = $sqlinvopyt1->fetch(PDO::FETCH_ASSOC);
        $totalPaid = $rowinvopyt1['total_paid'] ?? 0; // Ensure it's defined
    ?>
    <td rowspan="<?php echo (int) $firstRow; ?>"><?php echo htmlspecialchars($totalPaid); ?></td>
<?php endif; ?>
<td><?php echo number_format($balance+$totalPaid, 0); ?></td>

                        
                    </tr>
                    <?php
                    $firstRow = false;  // Ensure that the first row is displayed only once
                }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>