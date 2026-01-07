<div class="modal fade modal-right modal-slide" id="viewRoom<?php echo $romm700['ten_id']?>" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addexampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" modal="large" role="document">
    <div class="modal-content" >  
        <div class="modal-header"> 
         <h3 class="modal-title"><i class="fe fe-user"></i> <?php  echo $fetch_ten['te_names'] ?></h3>  
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
            
        </div> 
         <form class="form-horizontal row-fluid" id="add_room" method="post" action="">
      <div class="modal-body">
       
            <input type="hidden" id="cpid" name="cpid" value="<?php echo $cpny_ID;?>">
             <div class="row">
                  <div class="col-md-12">
                        <div class="form-group mb-2">
                            </div>
                    
            </div>
            
                <div class="col-md-12">
                    <div style='padding: 20px;border-radius: 8px;border: 2px solid #cb760d;width:100%;margin-top:1%;margin-bottom:1%;'>
                        
                           <div class="row">
                        <div class="col-md-2">
                        <label class="control-label" for="basicinput">#</label>
             	         
             	        </div>
                        <div class="col-md-8">
                   
                        <div class="form-group mb-10">
                        Room code 
                        </div>
                        </div>
                        
                        <div class="col-md-2"></div>
                        </div>
                     
                       
                      
                           <?php  
                           
                           
                           $ii=0;
                           while($fetchsqlstm=$sqlstm->fetch()){
                               $ii++;
                        
                        $r_id=$fetchsqlstm['room_id'];
                        $assgn_id=$fetchsqlstm['assgn_id'];
                        
                           $sqlstm1=$conn->prepare("SELECT * FROM tbl_rooms WHERE room_id='".$r_id."' ");
                    $sqlstm1->execute();
                    $fetchsqlstm1=$sqlstm1->fetch();
                    $counter=$sqlstm1->rowCount();
                   
                        
                        $r_no=$fetchsqlstm1['room_no'];
                        ?>
                       
                        <div class="row">
                        <div class="col-md-2">
                        <label class="control-label" for="basicinput"> <?php echo $ii;  ?></label>
             	         
             	        </div>
                        <div class="col-md-8">
                   
                        <div class="form-group mb-10">
                        <?php  echo "<b>".$r_no."</b>"; ?>
                        </div>
                        </div>
                        <div class="col-md-2">
                            <!--<a href="?file=tenmgnt&remove_room=<?php echo $fetchsqlstm1['room_id'];?>&assign_id=<?php echo $assgn_id ?>" onclick="if(!confirm('Do you really want to Remove This Room?'))return false;else return true;" title="Remove Room on Client"><span style="color: #2e2e2e;"><i class="fe fe-24 fe-delete"></i> </span></a>-->
                        
                        </div>
                        </div>
                        
                        <?php
                        
                       
                          }
                        ?>
                      
                     
            </div>
            
              
            </div>
              
            
                
            </div>
	
			<input type="hidden" id="CPSN" name="CPSN" value="<?php echo $cmp_sn;?>">
					
			
			
		
			<div name='display_roomcode' id='display_roomcode'></div>
			
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
        <!--<input type="hidden" name="rm_id" id="rm_id" />  -->
        <!--<button type="submit" name="expenseUp" id="expenseUp" class="btn btn-primary">Save</button>-->
        
      </div>
      </form>
    </div>
  </div>
</div>

