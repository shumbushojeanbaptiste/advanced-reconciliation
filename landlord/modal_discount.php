<!-- Modal -->
<div class="modal fade modaldiscount" tabindex="-1" role="dialog" aria-labelledby="modaldiscount" aria-hidden="true" id="modaldiscount">
    
   <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header"> 
        <h3 class="modal-title">Room Discount </h3> 
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
              
        </div>  
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="mngedisc" method="POST" action="">
            
             <div class="row">
                       <div class="col-md-3">
             	         <label class="control-label" for="basicinput">Room</label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   	<select id="floor_code" class="form-control select2" name="ridNo" data-placeholder="Choose one..." style="width: 100%" required >
                         <option></option>
                             <?php
                                 $stmt = $db->query('SELECT * FROM tbl_rooms INNER JOIN tbl_admins ON tbl_rooms.company_id = tbl_admins.company_id WHERE tbl_rooms.company_id = "'.$company.'"');
    
                                        try
                                        {
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                    <option value="<?php echo $row['room_id']; ?>"><?php echo $row['room_no']; ?></option>
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
             	         <label class="control-label" for="basicinput">Amount</label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="number" id="amount" name="amount" class="form-control" required>
                     </div>
                </div>
                </div>
			
		  <div class="row">
                       <div class="col-md-3">
             	         <label class="control-label" for="basicinput">Description</label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <textarea type="text" id="descption" name="descption" class="form-control" required></textarea>
                     </div>
                </div>
                </div>
			
		
			
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="saveDiscount" id="saveDiscount" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
  
</div>