 <form  action="" id="" method="post" enctype="multipart/form-data">
<div class="modal fade modal-edit<?php echo $row_count2CC['contr_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
<button aria-label="" type="button" class="close px-2" data-dismiss="modal" id="btnClick" aria-hidden="true">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
      
    <div class="modal-body text-center">
      <p> Edit contract  For <?php   echo $tnames1; ?>. </p>
      
      <div class="row">
                      <!-- <div class="col-md-3">-->
             	        <!--<label class="control-label" for="basicinput">Company Names </label>-->
             	        <!--</div>-->
             	      
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                  <input type="hidden" name="tenant_id" id="tenant_id" placeholder="Names" value="<?php echo $ten_id; ?>">
                  <input type="hidden" name="contract_id" id="contract_id" value="<?php echo $row_count2CC['contr_id']; ?>">
					<input type="hidden" name="tename" id="tename" placeholder="Names" value="<?php echo $row_count2['te_names']; ?>" class="form-control" required readonly>
                     </div>
                </div>
                </div>
                  
                 <div class="form-group row">
                       <div class="col-md-4">
             	        	<label class="control-label" for="basicinput">Contract rent AMT <span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-7">
                   
                   
                    
                   <input type="text" name="c_amount" id="c_amount" value="<?php echo $row_count2CC['c_rent_AMT'] ?>"  class="form-control" required >
                  
                </div>
                </div>
                
               
                
                 <div class="form-group row">
                       <div class="col-md-4">
             	        	<label class="control-label" for="basicinput">Contract AMT<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-7">
                <input type="text" name="tot_c_amount" id="tot_c_amount"  class="form-control" required value="<?php echo $row_count2CC['c_amaunt'] ?>">
                </div>
                </div>
                <div id="display_end_date2"></div>  
		       
                    <div class="form-group row">
                       <div class="col-md-4">
             	        	<label class="control-label" for="basicinput">Coution </label>
             	        </div>
                <div class="col-md-7">
                   
                    
                    
                   <!--<input type="text" name="interval" id="phone" placeholder="Write interval Month..." class="form-control" >-->
                    <input type="number" name="coution" id="coution"  class="form-control" required value="<?php echo $row_count2CC['coution_amaunt'] ?>">
                </div>
                </div>
                    <div class="form-group row">
                    <label class="col-md-4" for="basicinput">Contract End Date </label>
             	     <div class="col-md-7">
                    <input type="date" name="date2" id="date2" class="form-control" required value="<?php echo $row_count2CC['c_end_date'] ?>">
                    </div>
                    </div>
                
             
          <div class="form-group col-md-12">
              <input type="hidden" name="contract_id" id="pay_id<?php echo $row_count2CC['contr_id']; ?>" value="<?php echo $row_count2CC['contr_id']; ?>">
              <input type="hidden" name="slip" value="<?php echo $fetch['slip_no']; ?>">
        </div>
        <!--<button type="submit" class="btn btn-success btn-sm mb-2 my-2 my-sm-0" name="update-contract" >save change</button>-->
     
    </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary" name="edit-contract" id="edit-contract" >save change</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!--<button type="submit" name="saveDiscount" id="saveDiscount" class="btn btn-primary">save change</button>-->
        
      </div>
    </div>
  
  </div> 
  
</div>
</div> <!-- small modal -->
 </form>
                      