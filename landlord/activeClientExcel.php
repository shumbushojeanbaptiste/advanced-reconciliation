<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=".date("Y-m-d")." CHIC_Active Clients.xls");
?>
<?php
 require_once ("../include/initialize.php");
?>
<style type="text/css">
table{
    padding: 20px;
    width:100%;
 }

td
{
	padding-left:20px;
	padding-right:20px;
	padding-top:3px;
	padding-bottom:3px;
	border:1px solid #666;
	font-size:19px;
	/*text-align:center;*/
	min-width:120px;
	}
.header
	{
		padding-top:15px;
		padding-bottom:15px;
		color:#fff;
		background-color:#68A9BD;
		
	}
.title
	{
		border:0px;
		text-align:center;
		font-weight:600;
	}
.color_title
	{
		color:#68A9BD;
	}
</style>
<?php


?>
 <div class="table-responsive">
   <div class="module-body table">
	<?php








    
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

<table class="table table-hover" width="100%" style="font-size:11px" border="1">
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
        
        
    </tbody>
</table>

    </div>
 </div>