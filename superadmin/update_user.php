<!-- Update modal -->
<div class="modal fade" id="update<?php echo $adm_id;?>" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header">
            <h3 class="modal-title">Update User Info. </h3>    
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
             
        </div>  
        
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="mngedisc" method="POST" action="">
            <input type="hidden" id="basicinput" name="admin_id" value="<?php echo $adm_id ?>" class="form-control" required>
             <div class="row">
                 
                       <div class="col-md-4">
             	        <label class="control-label" for="basicinput">Company Name <span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-8">
                   
                    <div class="form-group mb-10">
                    
                   	 <select class="form-control select2" id="basicinput" name="company" data-placeholder="Select company name..." class="span8" style="width: 100%" required>
                             <option value="<?php echo $company_id ?>"><?php  echo $Cname ?></option>
                                 <?php
                                     $stmt = $db->query('SELECT cpny_ID, full_name FROM tbl_company where cpny_ID !="'.$company_id.'" ORDER BY cpny_ID DESC');
    
                                        try
                                        {
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                    <option value="<?php echo $row['cpny_ID']; ?>"><?php echo $row['full_name']." ".($row['short_name']); ?></option>
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
                            
                       <div class="col-md-4">
             	         <label class="control-label" for="basicinput">First Name <span class="help-inline" style="color:red">*</span></label>
             	        </div>
                        <div class="col-md-8">
                   
                        <div class="form-group mb-10">
                        <input type="text" id="basicinput"  value="<?php echo $lastName ?>" name="firstname"  class="form-control" required>
                        
                        </div>
                        </div>
                        </div>
			
		  <div class="row">
		             
                       <div class="col-md-4">
             	         <label class="control-label" for="basicinput">Family Name <span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-8">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" id="basicinput"  value="<?php echo $f_name ?>" name="lastname"  class="form-control" required>
                     </div>
                </div>
                </div>
                
                 <div class="row">
                     
                       <div class="col-md-4">
             	        <label class="control-label" for="basicinput">Mobile No. <span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-8">
                   
                    <div class="form-group mb-10">
                    <input type="text" id="basicinput"  value="<?php echo $phone ?>" name="mobile"  class="form-control" required>
                   
                     </div>
                </div>
                </div>
                
                 <div class="row">
                     
                       <div class="col-md-4">
             	       <label class="control-label" for="basicinput">Email</label>
             	        </div>
                <div class="col-md-8">
                   
                    <div class="form-group mb-10">
                        <input type="email" id="basicinput"  value="<?php echo $email ?>" name="email"  class="form-control">
                    
                   
                     </div>
                </div>
                </div>
                
                 <div class="row">
                   
                       <div class="col-md-4">
             	       <label class="control-label" for="basicinput">Address</label>
             	        </div>
                <div class="col-md-8">
                   
                    <div class="form-group mb-10">
                        <textarea  name="address"  class="form-control" ><?php echo $address ?></textarea>
                      </div>
                </div>
                </div>
			
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!--<input type="hidden" name="rm_id" id="rm_id" />  -->
        <button type="submit" name="update_user" id="update" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /Update modal -->