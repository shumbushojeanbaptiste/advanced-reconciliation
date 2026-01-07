<!-- Update modal -->
<div class="modal fade" id="update<?php echo $rm_id;?>" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header">
            <h3 class="modal-title">Update Room Info. </h3>    
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
             
        </div>  
        
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="add_room" method="post" action="">
            <input type="hidden" name="rid" value="<?php $roomID = $rm_id; echo $roomID;?>"/>
              <?php
        		$sql = $conn->prepare("SELECT * FROM `tbl_rooms` WHERE room_id = '$roomID' ");
        		$sql->execute();
                $fetch1 = $sql->fetch();
             	?>
             	<div class="row">
             	    <div class="col-md-3">
             	         <label for="simpleinput">Room No<span class="help-inline " style="color:red;" id="loader">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-8">
                   
                    <input type="text" name="room_no" value="<?php echo $fetch1['room_no']; ?>" class="form-control" required >
                     </div>
                </div>
                </div>
                <div class="row">
                       <div class="col-md-3">
             	         <label for="simpleinput">Price<span class="help-inline " style="color:red;" id="loader">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                    <input type="text" name="price" value="<?php echo $fetch1['price']; ?>" class="form-control" required>
                     </div>
                </div>
                </div>
             	 <div class="row">
             	        <div class="col-md-3">
             	        <label for="simpleinput">Descriptions<span class="help-inline " style="color:red;" id="loader">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                    <textarea class="form-control" name="descr" class="form-control" rows="2" style="width:100%;"><?php echo $fetch1['descr']; ?></textarea>
                     </div>
                </div>
                </div>
           	
            <div class="row">
                  <div class="col-md-3">
             	        <label for="simpleinput">Area [m<sup>2</sup>]<span class="help-inline " style="color:red;" id="loader">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                   
                    	<input type="text" name="area" value="<?php echo $fetch1['room_area']; ?>" class="form-control">
                     </div>
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