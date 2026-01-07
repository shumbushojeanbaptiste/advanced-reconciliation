<?php
 require_once ("../include/initialize.php");
 

    $options =$_REQUEST['options'];


        ?>
   
    <div class="table-responsive">
   <div class="module-body table">
	<?php







$counter = 0;
if ($options==1){
    
$roomAndClientInfo = $db->prepare("
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

        a.assgn_id,
        a.rent_date,
        a.amount,
        a.discount,
        a.description,
        a.recorded_date,
        a.assgn_status
    FROM tbl_assign a
    INNER JOIN tbl_rooms r ON a.room_id = r.room_id
    INNER JOIN tbl_tenants t ON a.tent_id = t.ten_id
    WHERE r.company_id = ? and r.side = 1 and a.assgn_status = 1 order by r.room_no asc
");
$roomAndClientInfo->execute([$company]);
$dataList = $roomAndClientInfo->fetchAll(PDO::FETCH_ASSOC);

$stats = $db->prepare("
    SELECT 
   
        (SELECT COUNT(*) FROM tbl_rooms r WHERE r.company_id = ? and side = 1 and availability = 4) AS total_rooms,

        (SELECT SUM(r.room_area) FROM tbl_rooms r WHERE r.company_id = ?) AS total_area,

        (SELECT COUNT(DISTINCT a.tent_id)  FROM tbl_assign a WHERE a.assgn_status = 1 and a.company_id =?) AS total_clients,


        (SELECT MAX(r.price) FROM tbl_rooms r WHERE r.company_id = ?) AS highest_room_price,

        (SELECT te_names 
         FROM tbl_tenants t 
         ORDER BY t.ten_date DESC 
         LIMIT 1) AS last_joiner
");
$stats->execute([$company, $company, $company, $company]);
$summary = $stats->fetch(PDO::FETCH_ASSOC);



?>
  <div align="center">
        <a href="activeClientExcel" target="_blank"> 
            <label class="btn btn-success btn-sm text-white"><i class="fe fe-file"></i> Export-excel</label>
        </a>
        </div>
<table class="table table-hover" width="100%" style="font-size:11px">
    <thead>
        <tr>
            <th colspan="6"><?php echo $cmp_full; ?> | Rent Information
        </tr>
    
        <tr>
            
            <th colspan="2"><b>Total Room : <?= $summary['total_rooms'] ?></b></th>
             <!--<th><b>Tot Area: <?= $summary['total_area'] ?></b></th>-->
            <th><b>Total Client: <?= $summary['total_clients'] ?> </b></th>
            
            <th ><b>Highest Room price: <?= number_format($summary['highest_room_price']) ?></b></th>
            <th colspan="2"><b> Last Joiner: <?= $summary['last_joiner'] ?></b></th>
        
        </tr>
        <tr>
            <th>#</th>
            <th><b>Shop</b></th>
             <th><b>Area (m <sup>2</sup>)</b></th>
            <th><b>Client-name(s) </b></th>
            
            <th><b>Phone and TIN</b></th>
            <th><b>Joined Date</b></th>
        
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dataList as $row): 
            $counter++;
            
        ?>
        <tr class="odd gradeX">
            <td><?php echo $counter; ?></td>
            <td><b><?php echo htmlspecialchars($row['room_no']); ?></b></td>
             <td><?php echo htmlspecialchars($row['room_area']); ?></td>
            <td><?php echo htmlspecialchars($row['te_names']); ?></td>
            <td><?php echo htmlspecialchars($row['ten_phone'])."<b> | ".htmlspecialchars($row['tin_no'])." </b>"; ?></td>
            <td><?php echo date('d/m/Y', strtotime($row['recorded_date'])); ?></td>
          
        </tr>
        <?php endforeach; ?>
        <tr><td colspan="7"><hr style="width:100%; background-color:#4e8aff; height: 1px; border: 0;"></td></tr>
        
    </tbody>
</table>
  <div align="right">
        <a href="activeClientExcel" target="_blank"> 
            <label class="btn btn-success btn-sm text-white"><i class="fe fe-file"></i> Export-excel</label>
        </a>
        </div>
    </div>
 </div>
 <?php
}
if ($options==2){
       
$roomAndClientInfo = $db->prepare("
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

        a.assgn_id,
        a.rent_date,
        a.amount,
        a.discount,
        a.description,
        a.recorded_date,
        a.assgn_status
    FROM tbl_assign a
    INNER JOIN tbl_rooms r ON a.room_id = r.room_id
    INNER JOIN tbl_tenants t ON a.tent_id = t.ten_id
    WHERE r.company_id = ?  and a.assgn_status = 1 order by r.room_no asc
");
$roomAndClientInfo->execute([$company]);
$dataList = $roomAndClientInfo->fetchAll(PDO::FETCH_ASSOC);

$stats = $db->prepare("
    SELECT 
   
       (SELECT COUNT(DISTINCT c.room_id) FROM tbl_consume c WHERE c.co_id = ?) AS total_meter,


        (SELECT SUM(r.room_area) FROM tbl_rooms r WHERE r.company_id = ?) AS total_area,

        (SELECT COUNT(DISTINCT a.tent_id)  FROM tbl_assign a WHERE a.assgn_status = 1 and a.company_id =?) AS total_clients,


        (SELECT MAX(c.qty) FROM tbl_consume c WHERE c.co_id = ?) AS highest_room_price,

        (SELECT te_names 
         FROM tbl_tenants t 
         ORDER BY t.ten_date DESC 
         LIMIT 1) AS last_joiner
");
$stats->execute([$company, $company, $company, $company]);
$summary = $stats->fetch(PDO::FETCH_ASSOC);



?>

<table class="table table-hover" width="100%" style="font-size:11px">
    <thead>
        <tr>
            <th colspan="6"><?php echo $cmp_full; ?> | Electricity Information
        </tr>
    
         <tr>
            
            <th colspan="2"><b>Total meter : <?= $summary['total_meter'] ?></b></th>
             <!--<th><b>Tot Area: <?= $summary['total_area'] ?></b></th>-->
            <th><b>Total Client: <?= $summary['total_clients'] ?> </b></th>
            
            <th ><b>Highest monthly consumed: <?= number_format($summary['highest_room_price']) ?></b></th>
            <th colspan="2"><b> Last Joiner: <?= $summary['last_joiner'] ?></b></th>
        
        </tr>
        <tr>
            <th>#</th>
            <th><b>Shop</b></th>
             <th><b>Area (m <sup>2</sup>)</b></th>
            <th><b>Client-name(s) </b></th>
            
            <th><b>Phone and TIN</b></th>
            <th><b>Joined Date</b></th>
        
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dataList as $row): 
            $counter++;
            
        ?>
        <tr class="odd gradeX">
            <td><?php echo $counter; ?></td>
            <td><b><?php echo htmlspecialchars($row['room_no']); ?></b></td>
             <td><?php echo htmlspecialchars($row['room_area']); ?></td>
            <td><?php echo htmlspecialchars($row['te_names']); ?></td>
            <td><?php echo htmlspecialchars($row['ten_phone'])."<b> | ".htmlspecialchars($row['tin_no'])." </b>"; ?></td>
            <td><?php echo date('d/m/Y', strtotime($row['recorded_date'])); ?></td>
          
        </tr>
        <?php endforeach; ?>
        <tr><td colspan="7"><hr style="width:100%; background-color:#4e8aff; height: 1px; border: 0;"></td></tr>
        
    </tbody>
</table>

    </div>
 </div>
 <?php
    
}

            
    
   
    ?>