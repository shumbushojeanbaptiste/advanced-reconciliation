<?php
 require_once ("../include/initialize.php");
 $tenant_ref=$_REQUEST['tenant_ref'];
$sql = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_ref_no = $tenant_ref ");
$sql->execute();
$fetch = $sql->fetch();
$tenid=$fetch['ten_id'];
?> 
     <?php 
                      $sqlassign = $conn->prepare("SELECT distinct(room_id) as room_id FROM `tbl_assign` WHERE tent_id = $tenant_ref and assgn_status in (1)");
                      $sqlassign->execute();
                      while($fetchassign = $sqlassign->fetch()){
                          //room
                          $roomid=$fetchassign['room_id'];
                          $sqlroom = $conn->prepare("SELECT * FROM `tbl_rooms` WHERE room_id = $roomid ");
                          $sqlroom->execute();
                          $fetchroom = $sqlroom->fetch();
                          $room= $fetchroom['room_no'];
                          ?>
                           <option value="<?php echo $roomid; ?>"><?php echo $room; ?></option> 
                          <?php
                      }
                          ?>