<!-- Update modal -->
<div class="modal fade" id="update<?php echo $pay_id;?>" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header">  
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
             <h3 class="modal-title">Update Room Info. </h3>  
        </div>  
        
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="add_room" method="post" action="">
            <input type="hidden" name="rid" value="<?php $roomID = $rm_id; echo $roomID;?>"/>
              <?php
        		$sql = $conn->prepare("SELECT * FROM `tbl_rooms` WHERE room_id = '$roomID' ");
        		$sql->execute();
                $fetch1 = $sql->fetch();
             	?>
             	<div class="form-group">
        		<label class="control-label" for="basicinput">Room No</label>
        		<div class="controls col-md-4">
        			<input type="text" name="room_no" value="<?php echo $fetch1['room_no']; ?>" class="" required>
        			<span class="help-inline" style="color:red;">*</span>
        		</div>
        	</div>
           	<div class="form-group">
        		<label class="control-label" for="basicinput">Price</label>
        		<div class="controls col-md-4">
        			<input type="number" name="price" value="<?php echo $fetch1['price']; ?>" class="form-control" required>
        			<span class="help-inline" style="color:red;">*</span>
        		</div>
        	</div>
        
        	<div class="form-group">
        		<label class="control-label" for="basicinput">Descriptions</label>
        		<div class="controls col-md-4">
        			<textarea class="form-control" name="descr" class="form-control" rows="2"><?php echo $fetch1['descr']; ?></textarea>
        		</div>
        	</div>
        	
        		<div class="form-group">
        		<label class="control-label" for="basicinput">Area [m<sup>2</sup>]</label>
        		<div class="controls col-md-12">
        			<input type="text" name="area" value="<?php echo $fetch1['room_area']; ?>" class="form-control">
        			<span class="help-inline" style="color:red;">*</span>
        		</div>
        	</div>
			
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!--<input type="hidden" name="rm_id" id="rm_id" />  -->
        <button type="submit" name="update" id="update" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /Update modal -->