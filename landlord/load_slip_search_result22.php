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
    
    
      $res = $db->prepare("SELECT * FROM tbl_tenants
    INNER JOIN tbl_assign ON tbl_assign.tent_id=tbl_tenants.ten_id
    INNER JOIN  tbl_rooms ON  tbl_rooms.room_id=tbl_assign.room_id
    WHERE (tbl_tenants.ten_phone LIKE '%".$kk."%' OR  tbl_tenants.te_names LIKE '%".$kk."%' OR tbl_rooms.room_no LIKE '%".$kk."%' ) AND ten_staff = $company and assgn_status=1 and tbl_tenants.ten_ref_no not in (select tenant_id from tbl_tenant_leave group by tenant_id) group by tbl_tenants.ten_phone ");
    $res->execute();
    

      $stmt1 = $db->prepare("SELECT SUM(amount) AS amount FROM  tbl_assign where tent_id='".$fff["tent_id"]."' ");
                $stmt1->execute();
                
                $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                $sumtot1 = $row1['amount'];
                
    
    
    $data=$res->rowCount();
    if($data>0){
    if(!empty($res)) {
    ?>
    <ul id="search-result-list">
    <?php
    foreach($res as $fff) {
         $stmt1 = $db->prepare("SELECT SUM(amount) AS amount FROM  tbl_assign where tent_id='".$fff["tent_id"]."' ");
                $stmt1->execute();
                
                $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                $sumtot1 = $row1['amount'];
    ?>
    <li onClick="selecttbl_admission2('<?php echo $fff["ten_ref_no"]; ?>');"><a href="javascript:void(0)"><?php echo "<b>".$fff["ten_phone"]."</b>"." ".$fff["te_names"]." ".number_format($sumtot1); ?></a></li>
    <?php 
    } // End For each
        
    }}
    
    else{ ?>
            <h6 class="mb-1">No Data Found</h6>
    <?php
    }} 
    
    ?>
  </ul>