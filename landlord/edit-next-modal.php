 <form  action="" id="" method="post" enctype="multipart/form-data">
<div class="modal fade modal-edit<?php echo $rows['pnext_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
<button aria-label="" type="button" class="close px-2" data-dismiss="modal" id="btnClick" aria-hidden="true">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
      
    <div class="modal-body text-center">
      <p> Edit payment date  For <?php   echo $tnames1; ?>. </p>
      
      <div class="row">
                      <!-- <div class="col-md-3">-->
             	        <!--<label class="control-label" for="basicinput">Company Names </label>-->
             	        <!--</div>-->
             	      
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                  <input type="hidden" name="tenant_id" id="tenant_id" placeholder="Names" value="<?php echo $ten_id; ?>">
                  <input type="hidden" name="pnext_id" id="pnext_id" value="<?php echo $rows['pnext_id']; ?>">
				<input type="hidden" name="tename" id="tename" placeholder="Names" value="<?php echo $row_count2['te_names']; ?>" class="form-control" required readonly>
                     </div>
                </div>
                </div>
                  
                 
                
               
                
              
               
		       
               
                    <div class="form-group row">
                    <label class="col-md-4" for="basicinput">Payment Date </label>
             	     <div class="col-md-7">
                    <input type="date" name="date2" id="date2" class="form-control" required value="<?php echo $rows['next_date'] ?>">
                    </div>
                    </div>
                
             
          <div class="form-group col-md-12">
              <input type="hidden" name="pnext_id" id="pay_id<?php echo $rows['pnext_id']; ?>"  value="<?php echo $rows['pnext_id']; ?>">
              <input type="hidden" name="slip" value="<?php echo $fetch['slip_no']; ?>">
        </div>
        <!--<button type="submit" class="btn btn-success btn-sm mb-2 my-2 my-sm-0" name="update-contract" >save change</button>-->
     
    </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary" name="edit-next" id="edit-contract" >save change</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!--<button type="submit" name="saveDiscount" id="saveDiscount" class="btn btn-primary">save change</button>-->
        
      </div>
    </div>
  
  </div> 
  
</div>
</div> <!-- small modal -->
 </form>
                      