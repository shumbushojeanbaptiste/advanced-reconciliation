 <?php   require_once ("../include/initialize.php"); ?>
 <?php if($_REQUEST['t_type']==1)
 {  ?>
 
 <div class="row">
                       <div class="col-md-3">
             	        <label class="control-label" for="basicinput">Company Names <span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                  
					<input type="text" name="tename" id="tename" placeholder="Names" class="form-control" required>
                     </div>
                </div>
                </div>
                 <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">TIN No </label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="tin_no" id="tin_no" placeholder="TIN Number..." class="form-control" >
                     </div>
                </div>
                </div>
                 <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">REPERSENTED BY 	<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="Reprsented_name" id="Reprsented_name" placeholder="Reprsented name..." class="form-control" required>
                     </div>
                </div>
                </div>
		
		        <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Phone 	<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="phone" id="phone" placeholder="Phone Number..." class="form-control" required>
                     </div>
                </div>
                </div>
                 <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Currency Used	<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                  <select id="currency_id" class="form-control select2" name="currency_id" data-placeholder="Choose one..." style="width:100%" required >
                             <option></option>
                                 <?php
                                     $stmt1 = $db->query('SELECT * FROM tbl_money');
        
                                            try
                                            {
                                                while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $row1['money_id']; ?>"><?php echo $row1['m_name']; ?></option>
                                                    <?php
                                                     }
                                                }
                                                catch (PDOException $ex) {
                                                //Something went wrong rollback!
                                                echo $ex->getMessage();
                                        }
                                  ?>	
                            </select>
                     </div>
                </div>
                </div>
                 <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">ID/PASSPORT </label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="passport" id="passport" placeholder="ID/Passport Number..." class="form-control" >
                     </div>
                </div>
                </div>
                
                 <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Email</label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="email" name="email" id="email" placeholder="email..." class="form-control">
                     </div>
                </div>
                </div>
		
		           <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput" >Address</label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   	<textarea class="form-control" name="address" id="address" placeholder="address..." class="span8" rows="2"></textarea>
                     </div>
                </div>
                </div>
                
                <?php } 
                else{ ?>
                 <div class="row">
                       <div class="col-md-3">
             	        <label class="control-label" for="basicinput">Names <span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="hidden" name="tnt_id" id="tnt_id" value="<?php echo $tnts_id; ?>" class="span8" required>
					<input type="hidden" name="added_by" id="added_by" value="<?php echo $cpny_ID; ?>" class="span8" required>
					<input type="text" name="tename" id="tename" placeholder="Names" class="form-control" required>
                     </div>
                </div>
                </div>
                 <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">TIN No </label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="tin_no" id="tin_no" placeholder="TIN Number..." class="form-control" >
                     </div>
                </div>
                </div>
		
		        <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Phone 	<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="phone" id="phone" placeholder="Phone Number..." class="form-control" required>
                     </div>
                </div>
                </div>
                     <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Currency Used	<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                  <select id="currency_id" class="form-control select2" name="currency_id" data-placeholder="Choose one..." style="width:100%" required >
                             <option></option>
                                 <?php
                                     $stmt1 = $db->query('SELECT * FROM tbl_money');
        
                                            try
                                            {
                                                while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $row1['money_id']; ?>"><?php echo $row1['m_name']; ?></option>
                                                    <?php
                                                     }
                                                }
                                                catch (PDOException $ex) {
                                                //Something went wrong rollback!
                                                echo $ex->getMessage();
                                        }
                                  ?>	
                            </select>
                     </div>
                </div>
                </div>
                    <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">ID/PASSPORT 	</label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="passport" id="passport" placeholder="ID/Passport Number..." class="form-control" >
                     </div>
                </div>
                </div>
                
                 <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Email</label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="email" name="email" id="email" placeholder="email..." class="form-control">
                     </div>
                </div>
                </div>
		
		           <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput" >Address</label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   	<textarea class="form-control" name="address" id="address" placeholder="address..." class="span8" rows="2"></textarea>
                     </div>
                </div> 
                </div>
                
                <?php }
                ?>