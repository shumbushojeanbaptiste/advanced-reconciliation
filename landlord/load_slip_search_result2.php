<?php require_once ("../include/initialize.php");?>
     <style>
    
    #search-result-list {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }

    #search-result-list li a {
      border: none;
      margin-top: 1px;
      padding: 6px 12px 6px 12px;
      text-decoration: none;
      font-size: 10px;
      font-family:arial;
      color: black;
      display: block;
  
    }  
    #search-result-list li a:hover {
  background-color: #D3D3D3;
  
}
    .highlighted{
      background-color:none;
    }
    </style>
<?php
//echo " helllllloooo";


if(!empty($_POST["keyword1"])) {
    $kk = $_POST["keyword1"];
    // $company=;
    
    //  $res = $db->prepare("SELECT * FROM tbl_tenants
    // INNER JOIN tbl_assign ON tbl_assign.tent_id=tbl_tenants.ten_id
    // INNER JOIN  tbl_rooms ON  tbl_rooms.room_id=tbl_assign.room_id
    // WHERE (tbl_tenants.ten_phone LIKE '%".$kk."%' OR  tbl_tenants.te_names LIKE '%".$kk."%' OR tbl_rooms.room_no LIKE '%".$kk."%' ) AND ten_staff = $company
    // and tbl_tenants.ten_ref_no not in (select tenant_id from tbl_tenant_leave group by tenant_id) and tbl_assign.assgn_status=1 group by tbl_tenants.ten_phone ");
    // $res->execute();

  $sql = "
SELECT *
FROM tbl_tenants t
INNER JOIN tbl_assign a ON a.tent_id = t.ten_id
INNER JOIN tbl_rooms r ON r.room_id = a.room_id
LEFT JOIN tbl_tenant_leave l ON l.tenant_id = t.ten_id
WHERE (t.ten_phone LIKE :kk OR t.te_names LIKE :kk OR r.room_no LIKE :kk)
  AND t.ten_staff = :company
  AND l.tenant_id IS NULL
 
GROUP BY t.ten_id
";
//  AND a.assgn_status = 1
$res = $db->prepare($sql);
$search = "%".$kk."%";
$res->bindParam(':kk', $search, PDO::PARAM_STR);
$res->bindParam(':company', $company, PDO::PARAM_INT);
$res->execute();

    $data=$res->rowCount();
    if($data>0){
    if(!empty($res)) {
    ?>
    <ul id="search-result-list">
    <?php
    foreach($res as $fff) {
    ?>
    <li onClick="selecttbl_admission2('<?php echo $fff["ten_ref_no"]; ?>');"> <a href="javascript:void(0)"><i class="fe fe-user"></i> <?php echo "<b>".$fff["room_no"]."</b> | ".$fff["ten_phone"]." "." ".$fff["te_names"]; ?></a></li>
    <?php 
    } // End For each
        
    }}
    
    else{ ?>
            <h6 class="mb-1">No Data Found</h6>
    <?php
    }
    
} 
    
    ?>
  </ul>