<?php
if(ISSET($_POST['btn-submit'])){
    $ten_id = $_POST['ten'];
    $room_id = $_POST['room'];
    $key = $_POST['key'];
    $electricity = $_POST['electricity'];
    $bulb = $_POST['bulb'];
    $paint = $_POST['paint'];
    $window = $_POST['window'];
    $door = $_POST['door'];
    $comments = $_POST['comments'];
    $todaysdate = date("Y-m-d H:i:s");
       
                
                $stmt = "UPDATE `tbl_rooms` SET availability = 3 WHERE room_id = $room_id";
                $conn->exec($stmt);
                
              
                // update into tbl_tenant
                
                $stmt = "UPDATE `tbl_tenants` SET ten_staff = 0 WHERE ten_id = $ten_id";
                $conn->exec($stmt);
                
                 //  update into tbl_assign
                
                $stmt = "UPDATE `tbl_assign` SET assgn_status= 0 WHERE tent_id = $ten_id AND room_id = $room_id ";
                $conn->exec($stmt);
                
                // insert into tbl_tenant_leave
                $sql = "INSERT INTO `tbl_tenant_leave` (`key_holder`, `electricity`,`bulbs`,`paint`,`windows`,`door`,`leave_invoice`,`leave_payment`,`tenant_id`,`room_id`,`comp_id`,`comment`,`leave_date`)
                                                VALUES ('$key','$electricity','$bulb','$paint','$window','$door','$inv_amount','$pay_amount','$ten_id','$room_id','$company','$comments','$todaysdate')";
                                                $conn->exec($sql);
                 $msg = " Successfully Tenant Leave!";
                 
                 echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=tenant_leave">';
}
?>

<div class="span9">
  <div class="content">

        <?php
            if(!empty($msg)) {?>
    	<div class="alert alert-success">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Well done!</strong> <?php echo $msg; ?> 
    	</div>
        <?php
          }
           else if(!empty($msge)) {
        ?>
    	<div class="alert alert-error">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Oh snap!</strong>  <?php echo $msge; ?> 
    	</div>
        <?php
           }
        ?>
        					<div class="module">
 <ul class="nav nav-tabs">
    <li><a href="#" data-toggle="tab">Tenant Leave Form</a></li>
</ul>
 <form class="form-horizontal row-fluid"  role="form" id="add_form" action="" method="POST">
<div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
        <br><br>
      <div class="form-row">
        <div class="form-group col-md-2">
        </div>
        <div class="form-group col-md-4">
          <label for="simple-select2">Tenant name</label>
           <select name="ten" id="ten" class="form-control select2"  required>
                           <option></option>
                 <?php
                     $sql = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_staff = $company and ten_id not in (select tenant_id from tbl_tenant_leave group by tenant_id) ORDER BY ten_id");
                     $sql->execute();
                     while($fetch = $sql->fetch()){
                 ?>
         
             <option value="<?php echo $fetch['ten_id']?>"><?php echo $fetch['te_names']?></option>
         
         <?php
             }
         ?>      
        </select>
        </div> <!-- form-group -->
        <div class="form-group col-md-4">
          <label for="multi-select2">Key Holder</label>
          <select name="key" id="key" class="form-control select2" required>
               <option></option>
                         <?php
                             $sql = $conn->prepare("SELECT * FROM `tbl_conditions`");
                             $sql->execute();
                             while($fetch = $sql->fetch()){
                         ?>
                 
                     <option value="<?php echo $fetch['cond_id']?>"><?php echo $fetch['cond_name']?></option>
                 
                 <?php
                     }
                 ?>      
                 </select>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div class="form-row">
        <div class="form-group col-md-2">
        </div>
        <div class="form-group col-md-4">
           <label for="simple-select2">Electricity Remote</label>
           <select name="electricity" id="electricity" class="form-control select2"  required>
                            <option></option>
                                    <?php
                                         $sql = $conn->prepare("SELECT * FROM `tbl_conditions`");
                                         $sql->execute();
                                         while($fetch = $sql->fetch()){
                                     ?>
                             
                                 <option value="<?php echo $fetch['cond_id']?>"><?php echo $fetch['cond_name']?></option>
                             
                             <?php
                                 }
                             ?>      
                             </select>
        </div> <!-- form-group -->
        <div class="form-group col-md-4">
          <label for="multi-select2">Condition of Bulbs</label>
          <select name="bulb" id="bulb" class="form-control select2" required>
               <option>Choose one</option>
                     <?php
                         $sql = $conn->prepare("SELECT * FROM `tbl_conditions`");
                         $sql->execute();
                         while($fetch = $sql->fetch()){
                     ?>
             
                 <option value="<?php echo $fetch['cond_id']?>"><?php echo $fetch['cond_name']?></option>
             
             <?php
                 }
             ?>      
             </select>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div class="form-row">
        <div class="form-group col-md-2">
        </div>
        <div class="form-group col-md-4">
          <label for="simple-select2">Paint on the Wall</label>
           <select name="paint" id="paint" class="form-control select2" required>
                            <option></option>
                                     <?php
                                         $sql = $conn->prepare("SELECT * FROM `tbl_conditions`");
                                         $sql->execute();
                                         while($fetch = $sql->fetch()){
                                     ?>
                             
                                 <option value="<?php echo $fetch['cond_id']?>"><?php echo $fetch['cond_name']?></option>
                             
                             <?php
                                 }
                             ?>      
                             </select>
        </div> <!-- form-group -->
        <div class="form-group col-md-4">
          <label for="multi-select2">Condition of Windows</label>
          <select name="window" id="window" class="form-control select2"  required>
               <option></option>
                     <?php
                         $sql = $conn->prepare("SELECT * FROM `tbl_conditions`");
                         $sql->execute();
                         while($fetch = $sql->fetch()){
                     ?>
             
                 <option value="<?php echo $fetch['cond_id']?>"><?php echo $fetch['cond_name']?></option>
             
             <?php
                 }
             ?>         
        </select>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div class="form-row">
        <div class="form-group col-md-2">
        </div>
        <div class="form-group col-md-4">
          <label for="simple-select2">Door Lock</label>
           <select name="door" id="door" class="form-control select2" required>
            <option></option>
                 <?php
                     $sql = $conn->prepare("SELECT * FROM `tbl_conditions`");
                     $sql->execute();
                     while($fetch = $sql->fetch()){
                 ?>
         
             <option value="<?php echo $fetch['cond_id']?>"><?php echo $fetch['cond_name']?></option>
         
         <?php
             }
         ?>      
         </select>
        </div> <!-- form-group -->
        <div class="form-group col-md-4">
          <label for="multi-select2">Comments</label>
          <textarea name="comments" class="form-control" placeholder="comment"></textarea>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div class="form-row">
        <div class="form-group col-md-2">
        </div>
        <div class="form-group col-md-4">
          <button type="submit" name="btn-submit" class="btn btn-primary">Submit</button>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      
  <div name='display_specs' id='display_specs'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->
            <div class="module-body">
           
                     
                     <div class="control-group">
                    <div name='display_specs' id='display_specs'>
                    </div>
                </div>



				</form>
              </div>
 </div>
    
</div><!-- End Content -->
 

 
</div><!--/.module-->

</div><!--/.content-->
</div><!--/.span9-->

				</form>
                       </div>
 </div>
    
 
</div><!--/.span9-->
<script type="text/javascript">
    $(document).ready(function () {

        $("#ten").change(function () {
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('load_ten_leave?ten=' + $(this).val(), function (data) {
             $("#display_specs").html(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>
