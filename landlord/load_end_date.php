                
                <?php  
                 $contract_period=$_REQUEST['contract_period'];
                 $c_from_date=$_REQUEST['from_date'];
                $daysss=30*$contract_period;
                $days=$daysss."days";
                $c_end_date = date('d/m/Y', strtotime($c_from_date. " + $days"));
                ?>
                
                <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Contract End Date<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-4">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="contract_period2" id="contract_period2"  value="<?php echo $c_end_date     ?>"  class="form-control" readonly>
                     </div>
                </div>
                </div>