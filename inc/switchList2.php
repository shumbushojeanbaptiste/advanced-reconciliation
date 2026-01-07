<div class="modal fade modal-right modal-slide" id="switchToOther" 
data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addexampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" modal="large" role="document">
    <div class="modal-content" >  
        <div class="modal-header"> 
         <h3 class="modal-title"><i class="fe fe-home"></i> Switch to</h3>  
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
                        Building 
                        </div>
                        </div>
                        
                            <div class="col-md-12">
                        <div class="form-group mb-2">
                            </div>
                      <div class="form-group mb-10">
                        <!--<label for="simpleinput">Floor <span class="help-inline " style="color:red;" id="loader">*</span></label>-->
                        <select id="switchToother" class="form-control select2" name="switchToother" data-placeholder="Choose Building..." style="width:100%" required >
                             <option></option>
                                 <?php
                                     $stmt = $db->query("SELECT * FROM tbl_company  where cpny_ID IN(14,10) and  cpny_ID !=$company_id");
        
                                            try
                                            {
                                                $a=0;
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $a++;
                                                    ?>
                                                    <option value="<?php echo $row['cpny_ID']; ?>"><?php echo $row['short_name']; ?></option>
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
                     
                       
                      
                      
                     
            </div>
            
              
            </div>
              
            
                
            </div>
	
			
					
			
			
		
		
			
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

      <script type="text/javascript">
    $(document).ready(function () {

        $("#switchToother").change(function () {
            var c_id=<?php echo $company ?>;
          
           var user_id=<?php echo $USER_ID ?>;
            // alert(user_id);
            
            var switchToother=$('#switchToother').val(); 
			$(this).after('<div id="loader114"><img src="../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('../include/switchTo.php?switchToother=' + $(this).val()  + '&user_id=' + user_id +'&c_id=' + c_id, function (data) {
             $("#display_roomcode").html(data);
                $('#loader114').slideUp(910, function () {
                    $(this).remove();
                });
                
                location.reload();
            });
        });

    });
</script>