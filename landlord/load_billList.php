<?php 
require_once ("../include/initialize.php");

$monthly = $_REQUEST['monthly'] ?? '';
$company = $company ?? 0; // ensure company is defined

// Optimized single query to get all data
$sql = "
SELECT 
    il.link_id,
    il.ten_ref,
    il.receiced_phone,
    il.invo_type,
    il.link_text,
    il.li_date,
    t.te_names,
    t.ten_id,
    t.currency_id,
    r.room_no,
    m.intoR AS converted
FROM tbl_invo_link AS il
INNER JOIN tbl_tenants AS t ON il.ten_ref = t.ten_ref_no
LEFT JOIN tbl_assign AS a ON a.tent_id = t.ten_id
LEFT JOIN tbl_rooms AS r ON r.room_id = a.room_id
LEFT JOIN tbl_invoice AS inv ON inv.inv_ref_no = il.ten_ref 
    AND DATE_FORMAT(inv.invoice_date,'%Y-%m') = :monthly
LEFT JOIN tbl_money AS m ON m.money_id = t.currency_id
WHERE il.co_id = :company
  AND inv.inv_ref_no IS NOT NULL
GROUP BY il.link_id
ORDER BY il.li_date DESC limit 5
";

$stmt = $db->prepare($sql);
$stmt->execute([
    ':monthly' => $monthly,
    ':company' => $company
]);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row_count = count($rows);
?>

<div class="row">
    <?php if(!empty($msg)) { ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Well done!</strong> <?php echo $msg; ?> 
        </div>
    <?php } else if(!empty($msge)) { ?>
        <div class="alert alert-danger" role="alert">
            <span class="fe fe-minus-circle fe-16 mr-2"></span> <?php echo $msge; ?> 
        </div>
    <?php } ?>

    <div class="col-md-12"><br><br>
      <div class="card shadow mb-4">
        <div class="card-body"><br><br>
          <div id="resultedOK">
            <?php if($row_count > 0) { ?>
                <div class="card-header">
                  <strong class="card-title"><?php echo $rows[0]['te_names'] ?? ''; ?></strong>
                  <a class="float-right small text-muted" href="#!"></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-borderless" style="font-size:11px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Names</th>
                                <th>SHOP</th>
                                <th>Receiver Phone</th>
                                <th>Invoice Type</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $i = 0;
                        foreach($rows as $row):
                            $i++;
                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row['te_names']; ?></td>
                                <td><?php echo $row['room_no']; ?></td>
                                <td><?php echo $row['receiced_phone']; ?></td>
                                <td><?php echo ($row['invo_type']==1) ? "Monthly" : "Special"; ?></td>
                                <td>
                                    <a href="<?php echo $row['link_text']; ?>" target="_blank">
                                      <span class="fe fe-download fe-16"></span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <br />
                <center>
                <div class="alert alert-success" role="alert" style="width:50%;">
                    <strong>No Data.</strong> <?php echo $monthly; ?>
                </div>
                </center><br />
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
</div>
