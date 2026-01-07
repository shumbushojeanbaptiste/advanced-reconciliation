<!--update Invoice-->
     <?php
     
   
  
     ?>   

<?php
// Ensure DB and company id ($cpny_ID) are available from your app bootstrap
// require_once('../include/initialize.php');

$companyId = isset($cpny_ID) ? $cpny_ID : (isset($_SESSION['cpny_ID']) ? $_SESSION['cpny_ID'] : 0);
?>
<div class="span9">
  <div class="content">

        
 <div class="module">
<div class="col">
  <h2 class="h5 page-title">Advance Payments</h2>
</div>

 
</div><!--/.module-->

</div><!--/.content-->
</div><!--/.span9-->
<div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
        <?php if (!empty($msg)): ?>
          <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><strong>Well done!</strong> <?= htmlspecialchars($msg) ?></div>
        <?php elseif (!empty($msge)): ?>
          <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>Oh snap!</strong> <?= htmlspecialchars($msge) ?></div>
        <?php endif; ?>

        <?php include 'send_to_all.php'; ?>

        <div class="table-responsive mt-4">
          <form method="post" id="sendbysome">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Ref No</th>
                  <th>Names</th>
                 
                  <th>Phone</th>
                  <th>TIN / ID</th>
                 
                 
                  <th>Rent Status</th>
                  <th>Rent Balance</th>
                </tr>
              </thead>
              <tbody>
<?php
try {
    $sql = "SELECT ten_id, ten_ref_no, te_names, tenant_type, tin_no, leader_name, ten_phone, id_number, ten_email, ten_address, ten_date, rent_status, ten_staff, currency_id, leave_stts, rc, rent_balance
            FROM tbl_tenants
            WHERE rent_balance >= 0
              AND ten_staff = :cpny
              AND ten_id NOT IN (SELECT tenant_id FROM tbl_tenant_leave)
            ORDER BY te_names ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute([':cpny' => $companyId]);

    $i = 0;
    $totalBalance = 0.0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $i++;
        $rentBal = (float)$row['rent_balance'];
        $totalBalance += $rentBal;
        ?>
                <tr>
                  <td><?php echo $i; ?></td>
                  <td><?php echo htmlspecialchars($row['ten_ref_no']); ?></td>
                  <td><?php echo htmlspecialchars($row['te_names']); ?></td>
                 
                  <td><?php echo htmlspecialchars($row['ten_phone']); ?></td>
                  <td><?php echo htmlspecialchars($row['tin_no'] ?: $row['id_number']); ?></td>
                
                 
                  <td><?php echo htmlspecialchars($row['rent_status']); ?></td>
                  <td class="text-right"><?php echo number_format($rentBal, 2); ?></td>
                </tr>
        <?php
    }

    if ($i === 0) {
        echo '<tr><td colspan="7" class="text-muted text-center">No tenants with positive rent balance.</td></tr>';
    } else {
        echo '<tr ><td colspan="6" class="text-right"><strong>Total Outstanding</strong></td><td class="text-center"><strong>' . number_format($totalBalance, 2) . '</strong></td></tr>';
    }
} catch (PDOException $e) {
    echo '<tr><td colspan="7" class="text-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
}
?>
              </tbody>
            </table>
          </form>
        </div> <!-- /.table-responsive -->

      </div> <!-- /.card-body -->
    </div> <!-- /.card -->
  </div> <!-- /.col -->
</div> <!-- /.row -->

<!--modal for invoice update-->

