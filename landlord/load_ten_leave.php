    <?php
     require_once ("../include/initialize.php");
    $ten = $_REQUEST['ten'];
    ?>

   <label class="control-label" for="basicinput">Tenant Rooms</label>
   <div class="controls">
     <select name="room" class="form-control select2" data-selecter-options='{"cover":"true"}' style="width: 50%;">
         <option>Choose one</option>
             <?php
                 $sql = $conn->prepare("SELECT * FROM `tbl_assign` INNER JOIN `tbl_rooms` ON tbl_assign.room_id=tbl_rooms.room_id WHERE tent_id = $ten");
                 $sql->execute();
                 while($fetch = $sql->fetch()){
             ?>
     
         <option value="<?php echo $fetch['room_id']?>"><?php echo $fetch['room_no']?></option>
     
     <?php
         }
     ?>      
     </select>
     <span class="help-inline">*</span>
 </div>