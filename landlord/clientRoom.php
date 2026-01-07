<div class="modal fade" id="allocate<?php echo $rm_id; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addexampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header"> 
         <h3 class="modal-title">Allocate Room On Client </h3>  
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
            
        </div>  
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="add_room" method="post" action="">
            <input type="hidden" id="cpid" name="cpid" value="<?php echo $cpny_ID;?>">
            <input type="hidden" id="roo_id" name="room_id" value="<?php echo $rm_id;?>">
             <div class="row">
                  <div class="col-md-12">
                        <div class="form-group mb-2">
                            </div>
                      <div class="form-group mb-10">
                        <label for="simpleinput">Client name <span class="help-inline " style="color:red;" id="loader">*</span></label>
                        <select  class="form-control select2" name="client_id" data-placeholder="Choose Client..." style="width:100%" required >
                             <option></option>
                                 <?php
                                     $stmt = $db->query('SELECT * FROM tbl_tenants where ten_staff="'.$cpny_ID.'"  ORDER BY ten_id desc');
        
                                            try
                                            {
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $row['ten_id']; ?>"><?php echo $row['te_names']; ?></option>
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
                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            </div>
                      <div class="form-group mb-10">
                        <label for="simpleinput">Date<span class="help-inline " style="color:red;" id="loader">*</span></label>
                        <input type="date" name="duedate" id="price" placeholder="0.00" class="form-control" required>
                      </div>
            </div>
            
                
            </div>
	
		
					
			
			
		
		
			
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          
        <button type="submit" name="clientRoom" id="clientRoom" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

     