	<form class="form-horizontal row-fluid" method="post" action="" enctype="multipart/form-data">
            <div class="modal fade" id="assign<?php echo $fetch_ten['ten_id']?>" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">  
            <div class="modal-header"> 
            <h3 class="modal-title">Assign Tenant To Room</h3>
                 <button type="button" class="close" data-dismiss="modal">&times;</button>  
                   
            </div>  
            <div class="modal-body">
            
                <input type="hidden" name="tid" value="<?php echo $fetch_ten['ten_id']?>"/>
                <div class="control-group text-center" style="display: inline-flex;">
                    <div class="controls" style="width: 100%;margin-right: 10px;">
            		<label class="control-label" for="basicinput">Payment Date</label>
            		</div>
            		<div class="controls">
            			<input type="date" name="date" id="date" placeholder="Date of Payment" class="" required>
            		</div>
            	</div>
            	<div class="control-group" style="display: inline-flex;">
            	    <div class="controls" style="width: 100%;margin-right: 20px;">
            		<label class="control-label" for="basicinput">Select Room</label>
            		</div>
            		<div class="controls">
            			<select id="floor_code" class="form-control select2" name="rooms" data-placeholder="Choose one..." required >
                                     <option></option>
                                         <?php
                                             $stmt = $db->query('SELECT * FROM `tbl_rooms` WHERE rm_status = 1 AND availability = 3 AND company_id = "'.$company.'" ;');
                
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
            
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="assign" id="update_tenant" class="btn btn-primary">Save</button>
          
            </div>
            </div>
            </div>
            </div>
             </form>