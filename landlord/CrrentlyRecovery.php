<?php
// Ensure $db and $company are available from your initialize include
// require_once('../include/initialize.php'); // already included earlier in your app

// Simple CSS - do not hide Bootstrap modals globally
?>
<style>
    .modal-title { color: #111; }
    td { font-size: 12px; }
    .title { margin:10px; font-size:15px; }
    .form-group { display: inline-flex; }
    .lblClaa { font-size:10px; color: rgb(100,100,100); }
</style>

<div class="span9">
  <div class="content">
   <div class="module">
    <div class="col">
      <h2 class="h5 page-title"><?= htmlspecialchars($title ?? 'Currently Recovery') ?></h2>
    </div>
    <br>
   </div><!--/.module-->
  </div><!--/.content-->
</div><!--/.span9-->

<div class="row">
 <div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
      <br><br>
      <div class="form-row">
        <div class="form-group col-md-1"></div>
        <div class="form-group col-md-3"></div>
      </div> <!-- form-row -->

      <div class="table-responsive">
       <div class="module-body table">

        <table class="table table-hover" width="100%">
         <thead>
           <tr>
             <th colspan="4"><?php echo htmlspecialchars($cmp_full ?? $company ?? ''); ?> All Currently Recovery
               <th>
                 <a href="currentNowAMT.php?pny=<?php echo urlencode($cmp_full ?? $company ?? ''); ?>">
                   <button class="btn btn-success pull-left" style="margin:7px;">Print Excel</button>
                 </a>
               </th>
             </th>
           </tr>
           <tr>
             <th>#</th>
             <th>Tenant Names</th>
             <th>Room No</th>
             <th>Price</th>
             <th>Discount</th>
             <th>Net Amount</th>
           </tr>
         </thead>
         <tbody>
<?php
// Optimized: fetch every assigned room for tenants of this company (active assignments)
$sql = "
SELECT
  t.ten_id,
  t.ten_ref_no,
  t.te_names,
  r.room_no,
  r.price,
  IFNULL(a.discount,0) AS discount,
  (r.price - IFNULL(a.discount,0)) AS net_amount
FROM tbl_assign a
JOIN tbl_tenants t ON a.tent_id = t.ten_id
JOIN tbl_rooms r ON r.room_id = a.room_id
WHERE a.assgn_status = 1
  AND t.ten_staff = :company
ORDER BY t.te_names ASC, r.room_no ASC
";

$stmt = $db->prepare($sql);
$stmt->execute([':company' => $company]);

$idx = 0;
$grandTotal = 0.0;
$currentTenant = null;
$tenantTotal = 0.0;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $idx++;
    $tenantName = $row['te_names'];
    $roomNo = $row['room_no'];
    $price = (float)$row['price'];
    $discount = (float)$row['discount'];
    $net = (float)$row['net_amount'];

    // New tenant grouping: if tenant changed, print tenant total row for previous
    if ($currentTenant !== null && $currentTenant !== $tenantName) {
        // print tenant subtotal
        echo '<tr ><td></td><td></td><td></td><td></td><td></td><td><strong>'.number_format($tenantTotal,2).'</strong></td></tr>';
        $tenantTotal = 0.0;
    }

    // print row: show tenant name only for first room row per tenant to keep orientation clean
    $displayTenant = ($currentTenant === $tenantName) ? '' : htmlspecialchars($tenantName);

    echo '<tr>';
    echo '<td>'. $idx .'</td>';
    echo '<td><strong>'. $displayTenant .'</strong></td>';
    echo '<td>'. htmlspecialchars($roomNo) .'</td>';
    echo '<td>'. number_format($price,2) .'</td>';
    echo '<td>'. number_format($discount,2) .'</td>';
    echo '<td>'. number_format($net,2) .'</td>';
    echo '</tr>';

    $currentTenant = $tenantName;
    $tenantTotal += $net;
    $grandTotal += $net;
}

// After loop, print last tenant subtotal
if ($currentTenant !== null) {
    echo '<tr "><td>-</td><td></td><td></td><td></td><td></td><td><strong>'.number_format($tenantTotal,2).'</strong></td></tr>';
}

// Grand total row
echo '<tfoot>';
echo '<tr><td colspan="5"><strong>Grand Total</strong></td><td><strong>'.number_format($grandTotal,2).'</strong></td></tr>';
echo '</tfoot>';
?>
         </tbody>
        </table>

       </div>
      </div>

    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
 </div> <!-- /.col -->
</div> <!-- /.row -->



