 <form  action="" id="" method="post" enctype="multipart/form-data">
<div class="modal fade modal-change<?php echo $row_count2CC['contr_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
<button aria-label="" type="button" class="close px-2" data-dismiss="modal" id="btnClick" aria-hidden="true">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
      
    <div class="modal-body text-center">
      <p> contract addendum For <?php   echo $tnames1; ?>. </p>
      
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
             	        	<label class="control-label" for="basicinput">Starting Date <span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-7">
                   
                   
                    
                   <input type="date" name="from_date" id="from_date" value="<?php echo $row_count2CC['c_start_date'] ?>"  class="form-control" required  >
                  
                </div>
                </div>
                 <div class="form-group row">
                       <div class="col-md-4">
             	        	<label class="control-label" for="basicinput">Added Month 	<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-7">
                <input type="number" name="contract_period" id="contract_period22"  placeholder="Write number of month... ex: 12" class="form-control" required>
                </div>
                </div>
                <div id="display_end_date2"></div>  
		       
                    <div class="form-group row">
                       <div class="col-md-4">
             	        	<label class="control-label" for="basicinput">Payment Interval </label>
             	        </div>
                <div class="col-md-7">
                   
                    
                    
                   <!--<input type="text" name="interval" id="phone" placeholder="Write interval Month..." class="form-control" >-->
                    <select id="interval" class="form-control select2" name="interval" data-placeholder="Choose interval Month..." style="width:100%" required >
                        <?php   $stms2RoomPER1 = $db->prepare("SELECT * FROM  tbl_pay_period where Mnumber='".$row_count2CC['m_interval']."' ");
    			                $stms2RoomPER1->execute();
    			
    			                $per1=$stms2RoomPER1->fetch();
    			?>
                             <option value="<?php echo $per1['Mnumber'] ?>"><?php echo $per1['period_desc'] ?></option>
                            <?php 
                              $stms2RoomPER = $db->prepare("SELECT * FROM  tbl_pay_period  where   Mnumber!='".$row_count2CC['m_interval']."'");
    			$stms2RoomPER->execute();
    			
    			while($per=$stms2RoomPER->fetch()){
    			
    			
    			?>
                             <option value="<?php echo $per['Mnumber'] ?>"><?php echo $per['period_desc'] ?></option>
                             
                             
                             <?php  } ?>
                              	
                            </select>
                    
                </div>
                </div>
                    <div class="form-group row">
                    <label class="col-md-4" for="basicinput">Attach Document </label>
             	     <div class="col-md-7">
                    <input type="file" name="files100[]" id="files100" placeholder="attach document file..." class="form-control" >
                    </div>
                    </div>
                
             
          <div class="form-group col-md-12">
              <input type="hidden" name="contract_id" id="pay_id<?php echo $row_count2CC['contr_id']; ?>" value="<?php echo $row_count2CC['contr_id']; ?>">
              <input type="hidden" name="slip" value="<?php echo $fetch['slip_no']; ?>">
        <textarea class="form-control form-control-lg mr-sm-2" name="description"   type="search" placeholder="Why" aria-label="" ></textarea>
        </div>
        <button type="submit" class="btn btn-success btn-sm mb-2 my-2 my-sm-0" name="update-contract" >save change</button>
     
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!--<button type="submit" name="saveDiscount" id="saveDiscount" class="btn btn-primary">save change</button>-->
        
      </div>
    </div>
  
  </div> 
  
</div>
</div> <!-- small modal -->
 </form>
                      