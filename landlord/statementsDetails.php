<?php
 require_once ("../include/initialize.php");
 
 ?>
 
 <?php
$monthly = $_REQUEST['month'];
$type = $_REQUEST['type'];

$monthStart = $monthly . '-01';
$monthEnd = date('Y-m-d', strtotime($monthly . ' +1 month'));

?>


<style rel="stylesheet" type="text/css">
#clientpaiddetails {
  /*background-color: #efefef;*/
  /*width: 350px;*/
  border-style: solid;
  border-width: 1px;
  border-color: #999999;
  /*font-family: arial, verdana, sans-serif;*/
  font-size:11px;
}

</style>
<?php  
if($type=='invoice'){
$roomAndInvoice = $db->prepare("
    SELECT 
        r.room_id,
        r.room_no,
        r.auto_code,
        r.price,
        r.descr,
        r.room_area,
        r.company_id,
        r.r_floor,
        r.rm_status,
        r.availability,
        r.side,
        r.done_on,

        t.ten_id,
        t.ten_ref_no,
        t.te_names,
        t.tenant_type,
        t.tin_no,
        t.leader_name,
        t.ten_phone,
        t.id_number,
        t.ten_email,
        t.ten_address,
        t.ten_date,
        t.rent_status,
        t.ten_staff,
        t.currency_id,
        t.leave_stts,
        t.rc,

        i.invoice_id,
        i.invoice,
        SUM(i.invoice_fees) AS invoice_fees,
        i.unit_value,
        i.inv_desc,
        i.invoice_date,
        i.inv_ref_no,
        i.inv_room_id,
        i.cpny_ID,
        i.auto,
        i.inv_date,
        i.inv_message,
        i.serve_id,
        i.done_by,
        i.adv_invo,
        i.paid
    FROM tbl_invoice i
    INNER JOIN tbl_rooms r 
        ON i.inv_room_id = r.room_id
    INNER JOIN tbl_tenants t 
        ON i.inv_ref_no = t.ten_ref_no
    WHERE r.company_id = ? 
      AND DATE_FORMAT(i.invoice_date, '%Y-%m') = ?
    GROUP BY i.invoice_id
    HAVING SUM(i.invoice_fees) > 0
    ORDER BY r.room_no ASC
");
$currentMonth = $monthly;  // can be dynamic: date('Y-m')
$roomAndInvoice->execute([$company, $currentMonth]);
$invoiceList = $roomAndInvoice->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="module-body table">

    <div class="table-responsive">
	<table class="table table-hover" width="100%" id="clientpaiddetails">
        	<thead>

       
       
						<tr>
							<th># <?php echo $monthly  ?></th>
							<th>Room</th>
							<th>Client(s)</th>
							<td>Invoice (<?php echo "<b>".date('M Y', strtotime($monthly))."</b>" ?>)</td>
							<th>Upaid (From previous Months)</th>
							<th>G.total (Balance)</th>
							
							<!--<th>Tot. invoice</th>-->
						</tr>
						<?php 
						
						?>
					</thead>
					<tbody>
            <?php foreach ($invoiceList as $row): 
            $counter++;
            $tot2=$tot2+$row['invoice_fees'];
            
  
 // Invoice amount (before current month)

$sqlinvo = $conn->prepare("
    SELECT COALESCE(SUM(invoice_fees), 0) AS invoice_fees
    FROM tbl_invoice 
    WHERE inv_room_id = :room_id
      AND invoice_date < :monthEnd
      AND cpny_ID = :company
");
$sqlinvo->execute([
    ':room_id' => $row['room_id'],
    ':monthEnd' => $monthStart,
    ':company' => $company
]);

$fetchinvo = $sqlinvo->fetch();
$invoiceTotal = $fetchinvo['invoice_fees'] ?? 0;


// Paid amount for those invoices
$sqlinvopyt = $conn->prepare("
    SELECT COALESCE(SUM(pay_amount), 0) AS pay_amount
    FROM tbl_payment 
    WHERE pay_room_id = :room_id
      AND status = 1
      AND invoice_date < :monthEnd
      AND pay_user = :company
");
$sqlinvopyt->execute([
    ':room_id' => $row['room_id'],
    ':monthEnd' => $monthStart,
    ':company' => $company
]);

$fetchinvopyt = $sqlinvopyt->fetch();
$paidTotal = $fetchinvopyt['pay_amount'] ?? 0;


// Balance
$invoBalance = $invoiceTotal - $paidTotal;

       ?>
                    <tr>
                   	<th><?php echo $counter  ?></th>
							<td><b><?php echo htmlspecialchars($row['room_no']); ?></b></td>
							<td><i class="fe fe-user"></i> <b><?php echo htmlspecialchars($row['te_names'])." | ".htmlspecialchars($row['ten_phone']) ; ?>  </b></td>
							<td><b><?php echo number_format($row['invoice_fees']); ?></b></td>
							<td><b><?php echo number_format($invoBalance); ?></b></td>
							<td><b><?php echo number_format($invoBalance+$row['invoice_fees']); ?></b></td>
                    </tr>
                   
                   <?php endforeach; ?>
                    <tr><td colspan="3"><b>TOTAL :</b></td>
                    
                    <td><b><?php  echo number_format($tot2)." Frw"; ?></b></td>
                    
                    
                    </tr>
                   
                   </tbody>
                    </table>
    </div>
 </div>
 <?php } ?>
 
 <?php  
if($type=='payment'){
$roomAndPayment = $db->prepare("
    SELECT 
        r.room_id,
        r.room_no,
        r.auto_code,
        r.price,
        r.descr,
        r.room_area,
        r.company_id,
        r.r_floor,
        r.rm_status,
        r.availability,
        r.side,
        r.done_on,

        t.ten_id,
        t.ten_ref_no,
        t.te_names,
        t.tenant_type,
        t.tin_no,
        t.leader_name,
        t.ten_phone,
        t.id_number,
        t.ten_email,
        t.ten_address,
        t.ten_date,
        t.rent_status,
        t.ten_staff,
        t.currency_id,
        t.leave_stts,
        t.rc,

        -- Totals instead of per-payment values
        SUM(CASE 
              WHEN DATE_FORMAT(p.pay_due_date, '%Y-%m') = :currentMonth 
              THEN p.pay_amount ELSE 0 END
        ) AS total_paid_current_month,

        SUM(CASE 
              WHEN DATE_FORMAT(p.invoice_date, '%Y-%m') = :currentMonth 
              THEN p.pay_amount ELSE 0 END
        ) AS total_paid_for_current_month_invoices

    FROM tbl_payment p
    INNER JOIN tbl_rooms r 
        ON p.pay_room_id = r.room_id
    INNER JOIN tbl_tenants t 
        ON p.pay_ref_no = t.ten_ref_no
    WHERE r.company_id = :company and  DATE_FORMAT(p.pay_due_date, '%Y-%m') = :paymonth
    GROUP BY p.pay_room_id, t.ten_id
    ORDER BY r.room_no ASC
");

$currentMonth = $monthly; // or date('Y-m')
$roomAndPayment->execute([
    ':company' => $company,
    ':currentMonth' => $currentMonth,
    ':paymonth' => $currentMonth
]);
$paymentList = $roomAndPayment->fetchAll(PDO::FETCH_ASSOC);


?>
<div class="module-body table">

    <div class="table-responsive">
	<table class="table table-hover" width="100%" id="clientpaiddetails">
        	<thead>

       
       
						<tr>
							<th># <?php echo $monthly  ?></th>
							<th>Room</th>
							<th>Client(s)</th>
							<td> Total paid (<?php echo "<b>".date('M Y', strtotime($monthly))."</b>" ?>)</td>
							
							<td>Tot paid Depend invoiced (<?php echo"<b>".date('M Y', strtotime($monthly))."</b>" ?>)</td>
						</tr>
						<?php 
						
						?>
					</thead>
					<tbody>
            <?php 
            $tot2=0;
            $tot3=0;
            foreach ($paymentList as $row): 
            $counter++;
            $tot2=$tot2+$row['total_paid_current_month'];
            $tot3=$tot3+$row['total_paid_for_current_month_invoices'];
            
  
       ?>
                    <tr>
                   	<th><?php echo $counter  ?></th>
							<td><b><?php echo htmlspecialchars($row['room_no']); ?></b></td>
							<td><i class="fe fe-user"></i> <b><?php echo htmlspecialchars($row['te_names'])." | ".htmlspecialchars($row['ten_phone']) ; ?>  </b></td>
							<td><b><?php echo number_format($row['total_paid_current_month']); ?></b></td>
						    <td><b><?php echo number_format($row['total_paid_for_current_month_invoices']); ?></b></td>
                    </tr>
                   
                   <?php endforeach; ?>
                    <tr><td colspan="3"><b>TOTAL :</b></td>
                    
                    <td><b><?php  echo number_format($tot2)." Frw"; ?></b></td>
                    <td><b><?php  echo number_format($tot3)." Frw"; ?></b></td>
                    
                    
                    </tr>
                   
                   </tbody>
                    </table>
    </div>
 </div>
 <?php } ?>
  <?php  
if($type=='balance'){
$roomAndBalance = $db->prepare("
    SELECT 
        r.room_id,
        r.room_no,
        t.ten_id,
        t.ten_ref_no,
        t.te_names,
        t.ten_phone,
        COALESCE(inv.total_invoiced, 0) AS total_invoiced,
        COALESCE(pay.total_paid, 0) AS total_paid,
        (COALESCE(inv.total_invoiced, 0) - COALESCE(pay.total_paid, 0)) AS remaining_balance
    FROM tbl_rooms r
    INNER JOIN tbl_tenants t
        ON t.ten_ref_no = (
            SELECT pay_ref_no 
            FROM tbl_payment p 
            WHERE p.pay_room_id = r.room_id 
            ORDER BY pay_id DESC LIMIT 1
        )
    LEFT JOIN (
        SELECT inv_room_id, SUM(invoice_fees) AS total_invoiced
        FROM tbl_invoice
        WHERE invoice_date >= :monthStart AND invoice_date < :monthEnd
          AND cpny_ID = :company
        GROUP BY inv_room_id
    ) inv ON inv.inv_room_id = r.room_id
    LEFT JOIN (
        SELECT pay_room_id, SUM(pay_amount) AS total_paid
        FROM tbl_payment
        WHERE invoice_date >= :monthStart AND invoice_date < :monthEnd
          AND status = 1
          AND pay_user = :company
        GROUP BY pay_room_id
    ) pay ON pay.pay_room_id = r.room_id
    WHERE r.company_id = :company
      AND (COALESCE(inv.total_invoiced, 0) - COALESCE(pay.total_paid, 0)) > 0
    ORDER BY r.room_no ASC
  
");



$roomAndBalance->execute([
    ':company' => $company,
    ':monthStart' => $monthStart,
    ':monthEnd' => $monthEnd
]);
$BalanceList = $roomAndBalance->fetchAll(PDO::FETCH_ASSOC);








?>
<div class="module-body table">

    <div class="table-responsive">
	<table class="table table-hover" width="100%" id="clientpaiddetails">
        	<thead>

       
       
						<tr>
							<th># <?php echo $monthly  ?></th>
							<th>Room</th>
							<th>Client(s)</th>
							<td> Invoice (<?php echo "<b>".date('M Y', strtotime($monthly))."</b>" ?>)</td>
							
							<td>Payment (<?php echo "<b>".date('M Y', strtotime($monthly))."</b>" ?>)</td>
							<td>Balance (<?php echo "<b>".date('M Y', strtotime($monthly))."</b>" ?>)</td>
							
							<th>Balance + Unpaid from Prev</th>
							
							
						</tr>
						<?php 
						
						?>
					</thead>
					<tbody>
            <?php 
            $tot2=0;
            $tot3=0;
            $tot4=0;
            $tot5=0;
            foreach ($BalanceList as $row): 
            $counter++;
            
            
            
             // Invoice amount (before current month)

$sqlinvo = $conn->prepare("
    SELECT COALESCE(SUM(invoice_fees), 0) AS invoice_fees
    FROM tbl_invoice 
    WHERE inv_room_id = :room_id
      AND invoice_date < :monthEnd
      AND cpny_ID = :company
");
$sqlinvo->execute([
    ':room_id' => $row['room_id'],
    ':monthEnd' => $monthEnd,
    ':company' => $company
]);

$fetchinvo = $sqlinvo->fetch();
$invoiceTotal = $fetchinvo['invoice_fees'] ?? 0;


// Paid amount for those invoices
$sqlinvopyt = $conn->prepare("
    SELECT COALESCE(SUM(pay_amount), 0) AS pay_amount
    FROM tbl_payment 
    WHERE pay_room_id = :room_id
      AND status = 1
      AND invoice_date < :monthEnd
      AND pay_user = :company
");
$sqlinvopyt->execute([
    ':room_id' => $row['room_id'],
    ':monthEnd' => $monthEnd,
    ':company' => $company
]);

$fetchinvopyt = $sqlinvopyt->fetch();
$paidTotal = $fetchinvopyt['pay_amount'] ?? 0;


// Balance
$invoBalance = $invoiceTotal - $paidTotal;

$tot2=$tot2+$row['total_invoiced'];
$tot3=$tot3+$row['total_paid'];
$tot4=$tot4+$row['remaining_balance'];
$tot5=$tot5+$invoBalance;
            
  
       ?>
                    <tr>
                   	<th><?php echo $counter  ?></th>
							<td><b><?php echo htmlspecialchars($row['room_no']); ?></b></td>
							<td><i class="fe fe-user"></i> <b><?php echo htmlspecialchars($row['te_names'])." | ".htmlspecialchars($row['ten_phone']) ; ?>  </b></td>
							<td><b><?php echo number_format($row['total_invoiced']); ?></b></td>
						    <td><b><?php echo number_format($row['total_paid']); ?></b></td>
						    <td><b><?php echo number_format($row['remaining_balance']); ?></b></td>
						    <td><b><?php echo number_format($invoBalance); ?></b></td>
                    </tr>
                   
                   <?php endforeach; ?>
                    <tr><td colspan="3"><b>TOTAL :</b></td>
                    
                    <td><b><?php  echo number_format($tot2)." Frw"; ?></b></td>
                    <td><b><?php  echo number_format($tot3)." Frw"; ?></b></td>
                    <td><b><?php  echo number_format($tot4)." Frw"; ?></b></td>
                    <td><b><?php  echo number_format($tot5)." Frw"; ?></b></td>
                    
                    
                    </tr>
                   
                   </tbody>
                    </table>
    </div>
 </div>
 <?php } ?>
 