<div class="modal fade" id="addexampleModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addexampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header"> 
         <h3 class="modal-title">Room Details </h3>  
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
            
        </div>  
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="add_room" method="post" action="">
            <input type="hidden" id="cpid" name="cpid" value="<?php echo $cpnyIDD;?>">
             <div class="row">
                  <div class="col-md-12">
                        <div class="form-group mb-2">
                            </div>
                      <div class="form-group mb-10">
                        <label for="simpleinput">Floor <span class="help-inline " style="color:red;" id="loader">*</span></label>
                        <select id="floor_code" class="form-control select2" name="floor" data-placeholder="Choose Floor..." style="width:100%" required >
                             <option></option>
                                 <?php
                                     $stmt = $db->query('SELECT Block_code, block_name FROM tbl_block where company_id="'.$cpny_ID.'" AND status=1 ORDER BY Block_code ASC');
        
                                            try
                                            {
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $row['Block_code']; ?>"><?php echo $row['block_name']; ?></option>
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
                        <label for="simpleinput">Auto Code <span class="help-inline " style="color:red;" id="loader">*</span></label>
                        <select id="auto_code" class="form-control select2" name="auto_code" data-placeholder="Choose one..." style="width:100%" required >
                             <option></option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                      </div>
            </div>
            
                
            </div>
	
			<input type="hidden" id="CPSN" name="CPSN" value="<?php echo $cmp_sn;?>">
					
			
			
		
			<div name='display_roomcode' id='display_roomcode'></div>
			
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="hidden" name="rm_id" id="rm_id" />  
        <button type="submit" name="insert" id="insert" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

      <script type="text/javascript">
    $(document).ready(function () {

        $("#auto_code").change(function () {
            var c_id=<?php echo $company ?>;
            var CPSN = $('#CPSN').val();
            var auto_code=$('#auto_code').val();
            var last_CODE = $('#last_CODE').val();
            var floor_code=$('#floor_code').val(); 
			$(this).after('<div id="loader11sss"><img src="../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('load_floor_code.php?auto_code=' + $(this).val() + '&CPSN=' + CPSN  + '&last_CODE=' + last_CODE  + '&floor_code=' + floor_code +'&c_id=' + c_id, function (data) {
             $("#display_roomcode").html(data);
                $('#loader11sss').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>