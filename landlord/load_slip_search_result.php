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

    $res = $db->prepare("SELECT * FROM tbl_tenants
     INNER JOIN tbl_assign ON tbl_assign.tent_id=tbl_tenants.ten_id
    INNER JOIN  tbl_rooms ON  tbl_rooms.room_id=tbl_assign.room_id
    WHERE (tbl_tenants.ten_phone LIKE '%".$kk."%' OR  tbl_tenants.te_names LIKE '%".$kk."%'  OR tbl_rooms.room_no LIKE '%".$kk."%' ) AND ten_staff = $company ");
    $res->execute();
    $data=$res->rowCount();
    if($data>0){
    if(!empty($res)) {
    ?>
    <ul id="search-result-list">
    <?php
    foreach($res as $fff) {
    ?>
    <li onClick="selecttbl_admission('<?php echo $fff["ten_ref_no"]; ?>');"><a href="javascript:void(0)"><?php echo "<b>".$fff["room_no"]."</b>"." ".$fff["te_names"]; ?></a></li>
    <?php 
    } // End For each
        
    }}
    
    else{ ?>
            <h6 class="mb-1">No Data Found</h6>
    <?php
    }} 
    
    ?>
  </ul>