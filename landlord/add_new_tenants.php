 <div class="modal fade" id="tenantsModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="tenantsModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header">  
        <h3 class="modal-title">Add Tenant Details</h3>
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
               
        </div>  
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="add_tenant" method="post" action="" enctype="multipart/form-data">
			<div class="control-group">
				<label class="control-label" for="basicinput">Tenant Names</label>
				<div class="controls">
					<input type="hidden" name="tnt_id" id="tnt_id" value="<?php echo $tnts_id; ?>" class="span8" required>
					<input type="hidden" name="added_by" id="added_by" value="<?php echo $cpny_ID; ?>" class="span8" required>
					<input type="text" name="tename" id="tename" placeholder="Names" class="span8" required>
					<span class="help-inline">*</span>
				</div>
			</div>
		
			<div class="control-group">
				<label class="control-label" for="basicinput">Phone</label>
				<div class="controls">
					<input type="text" name="phone" id="phone" placeholder="Phone Number..." class="span8" required>
					<span class="help-inline">*</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="basicinput">Email</label>
				<div class="controls col-md-12">
					<input type="email" name="email" id="email" placeholder="email..." class="span8">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="basicinput">Address</label>
				<div class="controls col-md-12">
					<textarea class="form-control" name="address" id="address" placeholder="address..." class="span8" rows="2"></textarea>
				</div>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="insert" id="insert" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
 </div>