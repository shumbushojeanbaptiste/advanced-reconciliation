<?php
    if(ISSET($_POST['newuser-form'])){
    $sql_check = $db->prepare("SELECT * FROM tbl_blocks WHERE name = ?");
    try {
        $name = $_POST['name'];
		$status = $_POST["status"];
		
        
        $sql_check->execute(array($name));
        $row_count_check = $sql_check->rowCount();
                if ($row_count_check >= 1)
                {
                $snap_msg="This Block is Already Registered!";
                   
                }else{
                    
            //for user..
            $date = date("Y-m-d");
            $time = date("H:i:s");
            
            $sql_us = $db->prepare("SELECT * FROM tbl_user WHERE role_id = '2' AND profile_id = ?");
            $sql_us->execute([$profile]);
            $fetus = $sql_us->fetch();
            $owner = $fetus['profile_id'];
            
            
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `tbl_blocks` (`name`, `owner`,`status`,`reg_Date`) 
            VALUES ('$name', '$owner', '$status', '$date')";
            $conn->exec($sql);

            $msg="Block with Name: <b>".$name."</b> has been registered Successfully!";
            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=new_block">';
                }
            }catch(PDOException $e){
                echo $e->getMessage();
            }
         }
?>
<div class="span9">
	<div class="content">

		<div class="module">
			<div class="module-head">
				<h3>Add New Block</h3>
			</div>
			<div class="module-body">

					<br />

					<form class="form-horizontal row-fluid" id="newuser-form" method="post">
					    <?php
					        if(!empty($msg)) {?>
    					<div class="alert alert-success">
    						<button type="button" class="close" data-dismiss="alert">×</button>
    						<strong>Well done!</strong> <?php echo $msg; ?> 
    					</div>
					            
					    <?php
					      }
					       else if(!empty($snap_msg)) {
					    ?>
						<div class="alert alert-error">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<strong>Oh snap!</strong>  <?php echo $snap_msg; ?> 
						</div>
					    <?php
					       }
					    ?>
    					
						<div class="control-group">
							<label class="control-label" for="basicinput">Block Name</label>
							<div class="controls">
								<input type="text" id="basicinput" name="name" placeholder="Block Name..." class="span8" required>
								<span class="help-inline">*</span>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="basicinput">Status</label>
							<div class="controls">
								<select tabindex="1" data-placeholder="Select here.." name="status" class="span8 form-control select2">
									<option value="1" selected>Available</option>
									<option value="0">Occupied</option>
								</select>
							</div>
						</div>

						<div class="control-group">
							<div class="controls">
								<button type="submit" name="newuser-form" class="btn btn-success">Submit</button>
							</div>
						</div>
					</form>
			</div>
		</div>
	</div><!--/.content-->
</div><!--/.span9-->