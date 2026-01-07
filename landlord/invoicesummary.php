<?php 
  $sqlten = $db->prepare("SELECT DISTINCT(tent_id) AS tent_id FROM tbl_assign WHERE assgn_status = 1 AND company_id = 9");
  $sqlten->execute();
?>

<div class="col">
  <h2 class="h5 page-title text-left">Pre-Invoice Overview</h2>
</div>

<div class="row my-4">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-body">
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active text-left" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Invoice Overview</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"> 
            <div class="module-body table-responsive">
                <a href="exportinvoDetails.php" class="btn btn-primary btn-sm" style="display:none;">Export Excel</a>

              <table id="tenantTable" class="table table-striped table-bordered" style="font-size:11px; text-align:left;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Names</th>
                    <th>Phone</th>
                    <th>Room No</th>
                    <th>Monthly</th>
                    <th>Previous Balance</th>
                    <th>Total Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $i = 0;
                    $totalAmount = 0; // Initialize total amount
                    $totalAmountinvo=0;
                    $totalAmountinvoBAL=0;
                    while ($rowten = $sqlten->fetch(PDO::FETCH_ASSOC)) {
                        $i++;
                        $tenid = $rowten['tent_id'];
                        
                        // Tenant info
                        $sqlteninfo1 = $db->prepare("SELECT * FROM tbl_tenants WHERE ten_id = :ten_id");
                        $sqlteninfo1->execute([':ten_id' => $tenid]);
                        $rowteninfo1 = $sqlteninfo1->fetch();
                        $tenreff = $rowteninfo1['ten_ref_no'];
                        
                        // Invoice
                        $sqlinvo = $db->prepare("SELECT SUM(invoice_fees) AS invoice_fees FROM tbl_invoice WHERE inv_ref_no = :tenreff AND serve_id = 4");
                        $sqlinvo->execute([':tenreff' => $tenreff]);
                        $rowinvo = $sqlinvo->fetch();
                        
                        // Payment
                        $sqlpyt = $db->prepare("SELECT SUM(pay_amount) AS pay_amount FROM tbl_payment WHERE pay_ref_no = :tenreff AND pay_user = 9");
                        $sqlpyt->execute([':tenreff' => $tenreff]);
                        $rowpyt = $sqlpyt->fetch();
                        
                        $amount = $rowinvo['invoice_fees'] - $rowpyt['pay_amount'];
                        if($amount>0){
                            $amount=$amount;
                        }
                        else{
                            $amount=0;
                        }
                        
                        // Get assigned rooms
                        $sqlassign = $db->prepare("SELECT * FROM tbl_assign WHERE tent_id = :tent_id ORDER BY amount DESC");
                        $sqlassign->execute([':tent_id' => $tenid]);
                        $countassgn = $sqlassign->rowCount();
                        
                        $rowsRendered = 0;
                        $amountbal = 0;
                        while ($rowassgn = $sqlassign->fetch(PDO::FETCH_ASSOC)) {
                            // Room details
                            $sqlroom = $db->prepare("SELECT * FROM tbl_rooms WHERE room_id = :room_id");
                            $sqlroom->execute([':room_id' => $rowassgn['room_id']]);
                            $rowroom = $sqlroom->fetch();
                            $amountbal += ($rowassgn['amount'] - $rowassgn['discount']);
                            $totalAmountinvoBAL+=$amount;
                  ?>
                  <tr>
                    <?php if ($rowsRendered == 0): ?>
                      <td rowspan="<?php echo $countassgn; ?>"><?php echo $i; ?></td>
                      <td rowspan="<?php echo $countassgn; ?>"><?php echo htmlspecialchars($rowteninfo1['te_names']); ?></td>
                      <td rowspan="<?php echo $countassgn; ?>"><?php echo htmlspecialchars($rowteninfo1['ten_phone']); ?></td>
                    <?php endif; ?>
                    <td><?php echo htmlspecialchars($rowroom['room_no']); ?></td>
                    <td>
                      <?php echo ($rowassgn['amount'] > 0) ? number_format(($rowassgn['amount'] - $rowassgn['discount']), 0) : "-"; ?>
                    </td>
                    <?php if ($rowsRendered == 0): ?>
                      <td><?php echo number_format($amount, 0);
                    
                      ?></td>
                      <td rowspan="<?php echo $countassgn; ?>">
                        <?php 
                          $tenantTotal = $amountbal + $amount;
                          echo number_format($tenantTotal, 0); 
                          $totalAmount += $tenantTotal; // Add to total
                          $totalAmountinvo+=($rowassgn['amount'] - $rowassgn['discount']);
                        
                        ?>
                      </td>
                    <?php endif; ?>
                  </tr>
                  <?php 
                        $rowsRendered++; 
                        } 
                    } 
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5" class="text-right"><strong><?php echo number_format($totalAmountinvo,0); ?></strong></td>
                     <td><strong><?php //echo number_format($totalAmountinvoBAL, 0); ?></strong></td>
                    <td><strong><?php echo number_format($totalAmount, 0); ?></strong></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#tenantTable').DataTable({
      "searching": true,
      "paging": true,
      "ordering": true,
      "info": true
    });
  });
</script>
