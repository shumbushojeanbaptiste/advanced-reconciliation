<?php
    if(ISSET($_POST['insert'])){
    $sql_check = $db->prepare("SELECT * FROM tbl_tenants WHERE phone = ?");
    try {
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $added_by = $_POST['added_by'];
        $room = $_POST['room'];
        $starting_date = $_POST['starting_date'];
        $today = date("Y-m-d");
        $ref_no = random_strings(7); 
        $year = date("Y");
        $RefNo  = substr($year, -2).$ref_no;
        
        $sql_check->execute(array($phone));
        $row_count_check = $sql_check->rowCount();
                if ($row_count_check >= 1)
                {
                $msge="This Tenant is Already Registered!";
                   
                }else{
                    // Set image placement folder
                    $target_dir = "../img/contract_docs/";
                    // Get file path
                    $target_file = $target_dir . basename($_FILES["contract"]["name"]);
                    // Get file extension
                    $imageExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    // Allowed file types
                    $allowd_file_ext = array("pdf");
            
                    if (!file_exists($_FILES["contract"]["tmp_name"])) {
                        echo '<script language="javascript">
                              alert("Select new image to upload. ");
                              window.location.href = "?file=tenant-agree";
                              </script>';
                    } else if (!in_array($imageExt, $allowd_file_ext)) {
            		    $msge = "Allowed file format is .pdf only";
                    } else if ($_FILES["contract"]["size"] > 2097152) {
            		    $msge = "File is too large. File size should be less than 2 megabytes.  ";
                        echo '<meta http-equiv="refresh"'.'content="2;URL=?file=tenant-agree">';
                    } else {
                        if (move_uploaded_file($_FILES["contract"]["tmp_name"], $target_file)) {
                            
                            //for user..
                            $time = date("H:i:s");
                
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = "INSERT INTO `tbl_tenants` (`ref_no`, `first_name`, `last_name`, `phone`, `email`, `address`, `added_by`, `reg_date`) 
                            VALUES ('$RefNo', '$fname', '$lname', '$phone', '$email', '$address', '$added_by', '$today')";
                            $qr = $conn->prepare($sql);
                            $qr->execute();
                            $t_id = $conn->lastInsertId();
                
                            // update date variation
                            $sql = "INSERT into `tbl_contracts` (`tenant_id`, `room_id`, `starting_date`, `contract_path`) VALUES('$t_id', '$room', '$starting_date', '$target_file')";
                            $qr = $conn->prepare($sql);
                            $qr->execute();
                            
                            // update room availability
                            $sql = $db->prepare("UPDATE `tbl_rooms` set availability = 4 WHERE `id` = '$room' ");
		                    $sql->execute();
                
                            $msg="<b>".$fname."</b> has been added Successfully!";
                            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=tenant-agree">';
                        }
                    }
                }
            }catch(PDOException $e){
                echo $e->getMessage();
            }
         }
         
         if(ISSET($_POST['assign'])){
                $tnt_id = $_POST['tnt_id'];
                $room = $_POST['room'];
                $starting_date = $_POST['starting_date'];
    
                // Set image placement folder
                $target_dir = "../img/contract_docs/";
                // Get file path
                $target_file = $target_dir . basename($_FILES["contract"]["name"]);
                // Get file extension
                $imageExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                // Allowed file types
                $allowd_file_ext = array("pdf");
        
                if (!file_exists($_FILES["contract"]["tmp_name"])) {
                    echo '<script language="javascript">
                          alert("Select new image to upload. ");
                          window.location.href = "?file=tenant-agree";
                          </script>';
                } else if (!in_array($imageExt, $allowd_file_ext)) {
        		    $msge = "Allowed file format is .pdf only";
                } else if ($_FILES["contract"]["size"] > 2097152) {
        		    $msge = "File is too large. File size should be less than 2 megabytes.  ";
                    echo '<meta http-equiv="refresh"'.'content="2;URL=?file=tenant-agree">';
                } else {
                    if (move_uploaded_file($_FILES["contract"]["tmp_name"], $target_file)) {
            
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
                        // update date variation
                        $sql = "INSERT into `tbl_contracts` (`tenant_id`, `room_id`, `starting_date`, `contract_path`) VALUES('$tnt_id', '$room', '$starting_date', '$target_file')";
                        $qr = $conn->prepare($sql);
                        $qr->execute();
                        
                        // update room availability
                        $sql = $db->prepare("UPDATE `tbl_rooms` set availability = 4 WHERE `id` = '$room' ");
	                    $sql->execute();
            
                        $msg="<b>".$fname."</b> has been assigned new room Successfully!";
                        echo '<meta http-equiv="refresh"'.'content="2;URL=?file=tenant-agree">';
                    }
                }
         }
         
        //  update tenant
        if(ISSET($_POST['Update'])){
            $tnt_id = $_POST['tnt_id'];
            $fname = $_POST['first_name'];
            $lname = $_POST['last_name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $address = $_POST['address'];
            $room = $_POST['room'];
            $contract = $_POST['contract'];
            $starting_time = $_POST['starting_time'];

            $sql = "select *from `tbl_tenants` where id = '$tnt_id'";
            $qu = $conn->prepare($sql);
            $qu->execute();
            $ftnt = $qu->fetch();
            if($contract != $ftnt['contract_type'] && $contract == 2) {
                $sql = "DELETE from `tbl_contract_agreements` where tenant_id = '$tnt_id'";
                $conn->exec($sql);
            }
            
            else if($contract != $ftnt['contract_type'] && $contract == 1) {
                $sql = "insert into `tbl_contract_agreements` (`tenant_id`) VALUES ('$tnt_id')";
                $conn->exec($sql);
            }
            
            $sql = "UPDATE `tbl_tenants` set `first_name` = '$fname', `last_name` = '$lname', `phone` = '$phone', `email` = '$email',
            `address` = '$address' where id= '$tnt_id'";
            $conn->exec($sql);
            
            $sql = "UPDATE `tbl_contracts` set `room_id` = '$room', `contract_type` = '$contract' where tenant_id= '$tnt_id'";
            $conn->exec($sql);

            $msg="<b>".$fname."</b> has been modified Successfully!";
            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=tenant-agree">';
        }
?>
<?php               
    function random_strings($length_of_string) 
    { 
        $str_result = '1234567890'; 
        return substr(str_shuffle($str_result), 0, $length_of_string); 
    }
?>

<?php
	 // Delete ....
     if(ISSET($_GET['delete_tenant'])){
		
		$sql = $db->prepare("SELECT * from `tbl_tenants` WHERE `id`='".$_GET['delete_tenant']."' ");
		$sql->execute();
		$fet = $sql->fetch();
		$roomid = $fet['room_id'];
		
		$sql = $db->prepare("UPDATE `tbl_rooms` set availability = 3 WHERE `id` = '$roomid' ");
		$sql->execute();
		
		$sql = $db->prepare("DELETE from `tbl_tenants` WHERE `id`='".$_GET['delete_tenant']."' ");
		$sql->execute();
		
		$sql = $db->prepare("DELETE from `tbl_date_variation` WHERE `tenant_id`='".$_GET['delete_tenant']."' ");
		$sql->execute();
	
		$msge="<b>".$_GET['tenant_name']."</b> has successfully Removed";
        echo '<meta http-equiv="refresh"'.'content="2;URL=?file=tenant-agree">';
	}
	
    // for activate Subject
    if(isset($_GET['acid']))
    {

        try{
    			$change_status = 1;
    			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    			$sql = "UPDATE `tbl_tenants` SET `status` = '".$change_status."' WHERE `id` = '".$_GET['acid']."'";
    			$acidq = $db->prepare($sql);
    			$acidq->execute();
    			$msg="Tenant Activated successfully";
                echo '<meta http-equiv="refresh"'.'content="2;URL=?file=tenant-agree">';
    		}catch(PDOException $e){
    			echo $e->getMessage();
    		}
    		
      }
    
     // for Deactivate Subject
        else if(isset($_GET['did']))
          {
     try{
    			$change_status = 0;
    			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    			$sql = "UPDATE `tbl_tenants` SET `status` = '".$change_status."' WHERE `id` = '".$_GET['did']."'";
    			$didq = $db->prepare($sql);
    			$didq->execute();
    			$msge="Tenant Deactivated successfully";
                echo '<meta http-equiv="refresh"'.'content="2;URL=?file=tenant-agree">';
    		}catch(PDOException $e){
    			echo $e->getMessage();
    		}
    		
    	}
?>

<style>
    .modal { display:none; }
    .modal-title {color: #111;}
    td {
        font-size: 10px;
    }
</style>
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
  <div class="module-head">
        <button type="button" class="btn btn-info pull-right" name="add" id="add" data-toggle="modal" data-target="#tenantsModal">Add New</button>
        <h3>All Tenants(<?php echo $fetch_tnts['total_tenants']; ?>)</h3>
 </div>
<div class="module-body table">
	<table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display" width="100%" style="font-size:11px;">
		
		<thead>
			<tr>
				<th>#</th>
				<th>Contract</th>
				<th>Names</th>
				<th>Address</th>
				<th>Room</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
                $sql_contracts = $conn->prepare("SELECT * FROM tbl_contracts where status = 1");
                $sql_contracts->execute();
                $i = 0;
                while($fecont = $sql_contracts->fetch()) {
                    $contr_id = $fecont['contr_id'];
                    $rm_id = $fecont['room_id'];
                    $tnt_id = $fecont['tenant_id'];
                    
                    $sql_rooms = $conn->prepare("SELECT * FROM tbl_rooms where id = '$rm_id'");
                    $sql_rooms->execute();
                    $ferooms = $sql_rooms->fetch();
                    
                    $sql_tenants = $conn->prepare("SELECT * FROM tbl_tenants where id = '$tnt_id'");
                    $sql_tenants->execute();
                    $fetenants = $sql_tenants->fetch();
                    $i += 1;
            ?>
			<tr class="gradeA">
				<td class="center"><?php echo $i; ?></td>
				<td class="center">
				    <a href="<?php echo $fecont['contract_path']; ?>" target="_blank"><img style="width: 100px; height: 50px;" src="../img/contract_docs/pdf_icon.png"></a><br/>
				    <span><b>Starting Time:</b> <?php echo $fecont['starting_date']; ?></span><br/>
				</td>
				<td class="center">
				    <span><b>First Name:</b> <?php echo $fetenants['first_name']; ?></span><br/>
				    <span><b>Last Name:</b> <?php echo $fetenants['last_name']; ?></span><br/>
				    <span><b>Ref No.:</b> <?php echo $fetenants['ref_no']; ?></span><br/>
				</td>
				<td class="center">
				    <span><b>Phone:</b> <?php echo $fetenants['phone']; ?></span><br/>
    				<span><b>Email:</b> <?php echo !empty($fetenants['email']) ? $fetenants['email'] : '-'; ?></span><br/>
    				<span><b>Location:</b> <?php echo $fetenants['address']; ?></span>
				</td>
				<td class="center">
				    <span><b>Name:</b> <?php echo $ferooms['room_name']; ?></span><br/>
    				<span><b>No:</b> <?php echo $ferooms['room_no']; ?></span><br/>
    				<span><b>Cost:</b> <?php echo number_format($ferooms['price']); ?></span>
    			</td>
                <td class="cell-icon text-center">
                    <a href="#tenantsModal" data-target="#tenantsModal" class="btn btn-default edit_data" id="<?php echo $contr_id;?>"><i class="icon-edit"></i> Edit</a>
                     |
                    <a href="#assignModal" data-target="#assignModal" class="btn btn-default assign_tenant"  id="<?php echo $fetenants['id'];?>" room-id="<?php echo $ferooms['id'];?>"><i class="icon-plus"></i> Assign</a>
                   
                </td>
			</tr>
			<?php
                }
			?>
		 </tbody>
	</table>
 </div>
</div><!--/.module-->

</div><!--/.content-->
</div><!--/.span9-->


<!-- Form modal for tenant insertion -->
<div class="modal fade" id="tenantsModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="tenantsModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header">  
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
             <h3 class="modal-title">Tenant Details</h3>  
        </div>  
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="add_tenant" method="post" action="" enctype="multipart/form-data">
			<div class="control-group">
				<label class="control-label" for="basicinput">First Name</label>
				<div class="controls">
					<input type="hidden" name="tnt_id" id="tnt_id" value="<?php echo $tnts_id; ?>" class="span8" required>
					<input type="hidden" name="added_by" id="added_by" value="<?php echo $profile; ?>" class="span8" required>
					<input type="text" name="first_name" id="first_name" placeholder="First Name..." class="span8" required>
					<span class="help-inline">*</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="basicinput">Family Name</label>
				<div class="controls">
					<input type="text" name="last_name" id="last_name" placeholder="Last Name..." class="span8" required>
					<span class="help-inline">*</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="basicinput">Phone Number</label>
				<div class="controls">
					<input type="text" name="phone" id="phone" placeholder="Phone Number..." class="span8" required>
					<span class="help-inline">*</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="basicinput">Email</label>
				<div class="controls col-md-12">
					<input type="email" name="email" id="email" placeholder="email..." class="span8">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="basicinput">Address</label>
				<div class="controls col-md-12">
					<textarea class="form-control" name="address" id="address" placeholder="address..." class="span8" rows="2"></textarea>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="basicinput">Select Room</label>
				<div class="controls col-md-12">
					<select tabindex="1" name="room" id="room" class="form-control select2" placeholder="Choose one..." required>
                        <?php
                            $sql_rooms = $db->prepare("SELECT * FROM tbl_rooms where assignee = '$profile' and (availability = 3 AND status = 1)");
                            $sql_rooms->execute();
                            while($ferooms = $sql_rooms->fetch()) {
                        ?>
						<option value="<?php echo $ferooms['id']; ?>"><?php echo $ferooms['room_name'].'('.$ferooms['room_no'].')'; ?></option>
            			<?php
                            }
            			?>
					</select>
					<span class="help-inline">*</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="basicinput">Starting Time</label>
				<div class="controls col-md-12">
					<input type="date" name="starting_date" id="starting_date" class="span8" required>
					<span class="help-inline">*</span>
				</div>
			</div>
			<div class="control-group" id="contract_upload_cont">
				<label class="control-label">Upload Contract</label>
				<div class="controls col-md-12">
					<input type="file" name="contract" id="contract" class="span8 contract" required>
					<span class="help-inline">*</span>
				</div>
					<span class="alert alert-danger pdf_warning" style="display: none;">Please Upload PDF file</span>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="insert" id="insert" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /form modal -->

<!-- Form modal for another room assignement -->
<div class="modal fade" id="assignModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="assignModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header">  
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
             <h3 class="modal-title">Assign Room to Tenant</h3>  
        </div>  
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="add_tenant" method="post" action="" enctype="multipart/form-data">
			<div class="control-group">
				<label class="control-label" for="basicinput">Names</label>
				<div class="controls">
			        <input type="hidden" name="tnt_id" id="tntid" class="span8" required>
					<input type="text" name="names" id="names" class="span8" readonly>
					<span class="help-inline">*</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="basicinput">Select Room</label>
				<div class="controls col-md-12">
					<select tabindex="1" name="room" id="room" class="form-control select2" required>
						<option value="">Choose One...</option>
                        <?php
                            $sql_rooms = $db->prepare("SELECT * FROM tbl_rooms where assignee = '$profile' AND id != '' AND (availability = 3 AND status = 1)");
                            $sql_rooms->execute();
                            while($ferooms = $sql_rooms->fetch()) {
                        ?>
						<option value="<?php echo $ferooms['id']; ?>"><?php echo $ferooms['room_name'].'('.$ferooms['room_no'].')'; ?></option>
            			<?php
                            }
            			?>
					</select>
					<span class="help-inline">*</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="basicinput">Starting Time</label>
				<div class="controls col-md-12">
					<input type="date" name="starting_date" id="starting_date" class="span8" required>
					<span class="help-inline">*</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Upload Contract</label>
				<div class="controls col-md-12">
					<input type="file" name="contract" id="contract" class="span8 contract" required>
					<span class="help-inline">*</span>
				</div>
					<span class="alert alert-danger pdf_warning" style="display: none;">Please Upload PDF file</span>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="insert" id="insert" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /form modal -->