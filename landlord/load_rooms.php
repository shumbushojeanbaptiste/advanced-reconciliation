<?php
 require_once ("../include/initialize.php");
 $tenant_ref=$_REQUEST['tenant_ref'];
$sql = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_ref_no = $tenant_ref ");
$sql->execute();
$fetch = $sql->fetch();
$tenid=$fetch['ten_id'];
?>
<div class="form-row">
          
        <div class="form-group col-md-2">
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationCustom3">Tenant Room</label>
           <select id="roomid1" name="roomid1" class="form-control select2"  required>
                      <?php 
                      $sqlassign = $conn->prepare("SELECT distinct(room_id) as room_id FROM `tbl_assign`
                      WHERE tent_id = $tenid and assgn_status in (0,1) and room_id in (select inv_room_id from tbl_invoice group by inv_room_id)");
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
                         
                          
                 
                 </select>
                
        </div> 
        <!-- form-group -->
        <!--<div class="col-md-3 mb-3">-->
        <!--  <label for="multi-select2">Invoice Month</label>-->
        <!--  <input type="month" class="form-control" name="invdate" id="invdate" value="" max="<?php echo date('Y-m')?>" required />-->
        <!--</div> <!-- form-group -->-->
        <div class="col-md-3 mb-3">
          <label for="simple-select2">Slip No</label>
           <input type="text" name="slip_no" id="slip_no" class="form-control" placeholder="Slip Number" />
         </div> <!-- form-group -->
      </div> <!-- form-row -->
      
   <!-- jQuery Core -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
 <script type="text/javascript">
    $(document).ready(function () {
        $("#roomid1").change(function () {
            var tenant_reff = $('#tenant_ref').val();  
            var roomid1 = $('#roomid1').val();  
            $.get('load_balanceRoom.php?tenant_ref=' + tenant_reff+'&roomid1='+roomid1 , function (data) {
             $("#balanceamount").html(data);
            });
        });

    });
</script>
   
