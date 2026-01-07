	<form class="form-horizontal row-fluid" id="add_tenant" method="post" action="" enctype="multipart/form-data">
    <div class="modal fade" id="update<?php echo $fetch_ten['ten_id']?>" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
    <div class="modal-header">  
         <button type="button" class="close" data-dismiss="modal">&times;</button>  
         <h3 class="modal-title">Update Tenant Details</h3>  
    </div>  
    <div class="modal-body">
    
        <input type="hidden" name="tid" value="<?php echo $fetch_ten['ten_id']?>"/>
    	<div class="control-group">
    		<label class="control-label" for="basicinput">Tenant Names</label>
    		<div class="controls">
    			<input type="text" name="tename" id="tename" value="<?php echo $fetch_ten['te_names']; ?>" class="" style="width:80%;" required>
    			<span class="help-inline">*</span>
    		</div>
    	</div>
    
    	<div class="control-group">
    		<label class="control-label" for="basicinput">Phone</label>
    		<div class="controls">
    			<input type="text" name="phone" id="phone" value="<?php echo $fetch_ten['ten_phone']; ?>" class="" style="width:80%;" required>
    			<span class="help-inline">*</span>
    		</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label" for="basicinput">Email</label>
    		<div class="controls col-md-12">
    			<input type="email" name="email" id="email" value="<?php echo $fetch_ten['ten_email']; ?>" class="" style="width:80%;">
    		</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label" for="basicinput">Address</label>
    		<div class="controls col-md-12">
    			<textarea class="form-control" name="address" id="address" value="" class="" rows="2" style="width:80%;"> <?php echo $fetch_ten['ten_address']; ?></textarea>
    		</div>
    	</div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" name="update_tenant" id="update_tenant" class="btn btn-primary">Submit</button>
    
    </div>
    </div>
    
   
    
    </div>
    </div>
</form>