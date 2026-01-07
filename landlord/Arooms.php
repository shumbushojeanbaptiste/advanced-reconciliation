    <?php
    require_once ("../include/initialize.php");
 if (isset($_POST['insert'])) {

    try {
        $auto_code = $_POST['auto_code'];
        $company   = $company;   // FIX: use cpid
        $floor     = $_POST['floor'];
        $rm_code   = trim($_POST['rm_code']);
        $price     = $_POST['price'];
        $descr     = $_POST['descr'];
        $room_area = !empty($_POST['room_area']) ? $_POST['room_area'] : 0;

        // Check if room code exists for this company
        $check = $db->prepare("SELECT COUNT(*) FROM tbl_rooms WHERE room_no = ? ");
        $check->execute([$rm_code]);
        $exists = $check->fetchColumn();

        if ($exists > 0) {
            // ROOM EXISTS — stop insert
            ?>
            <script>
            jQuery(function(){
                swal("Warning!", "Room code already exists!", "error", {
                    button: "OK",
                });
            });
            </script>
            <?php
          
        }
        else {

        // INSERT ROOM
        $insert = $db->prepare("
            INSERT INTO tbl_rooms 
                (room_no, price, descr, room_area, company_id, r_floor, rm_status, auto_code)
            VALUES
                (?, ?, ?, ?, ?, ?, 1, ?)
        ");

        $insert->execute([
            $rm_code,
            $price,
            $descr,
            $room_area,
            $company,
            $floor,
            $auto_code
        ]);
        ?>

        <script>
        jQuery(function(){
            swal("Well done!", "Room has been created successfully! 6", "success", {
                button: "OK",
            });
        });
        </script>
          
        <?php
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


    //  update room
    
 if (isset($_POST['clientRoom'])) {

    $tenantId   = $_POST['client_id'];
    $roomId     = $_POST['room_id'];
    $dueDate    = $_POST['duedate']." ".date("H:i:s");

    try {
        // ✅ Begin Transaction
        $db->beginTransaction();

        // ✅ 1. Fetch room info safely
        $stmt = $db->prepare("
            SELECT room_id, descr, price, company_id 
            FROM tbl_rooms 
            WHERE room_id = :roomId
        ");
        $stmt->execute([':roomId' => $roomId]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            throw new Exception("Room not found.");
        }

        $descr      = $room['descr'];
        $price      = $room['price'];
        $companyId  = $room['company_id'];
        $today      = date("Y-m-d H:i:s");

        // ✅ 2. Insert assignment
        $assignStmt = $db->prepare("
            INSERT INTO tbl_assign (
                room_id, tent_id, company_id,
                rent_date, amount, description,
                recorded_date, assgn_status
            ) VALUES (
                :room_id, :tenant_id, :company_id,
                :rent_date, :amount, :description,
                :recorded_date, 1
            )
        ");

        $assignStmt->execute([
            ':room_id'       => $roomId,
            ':tenant_id'     => $tenantId,
            ':company_id'    => $companyId,
            ':rent_date'     => $dueDate,
            ':amount'        => $price,
            ':description'   => $descr,
            ':recorded_date' => $today
        ]);

        // ✅ 3. Update room availability
        $updateStmt = $db->prepare("
            UPDATE tbl_rooms 
            SET availability = 4 
            WHERE room_id = :roomId
        ");
        $updateStmt->execute([':roomId' => $roomId]);

        // ✅ Commit Transaction
        $db->commit();

        ?>
        <script>
            swal("Well done!", "Room has been assigned!", "success", {
                button: "OK",
            });
        </script>
        <?php

    } catch (Exception $e) {

        // ❗ Rollback on any error
        $db->rollBack();

        ?>
        <script>
            swal("Error!", "<?php echo $e->getMessage(); ?>", "error", {
                button: "OK",
            });
        </script>
        <?php
    }
}

if (isset($_POST['update'])) {

    $rmid  = $_POST['rid'];
    $no    = $_POST['room_no'];
    $price = $_POST['price'];
    $descr = $_POST['descr'];
    $area  = $_POST['area'];

    try {
        // START TRANSACTION
        $conn->beginTransaction();

        // UPDATE tbl_rooms
        $stmt1 = $conn->prepare("
            UPDATE tbl_rooms 
            SET room_no = ?, price = ?, descr = ?, room_area = ?
            WHERE room_id = ?
        ");
        $stmt1->execute([$no, $price, $descr, $area, $rmid]);


        // UPDATE tbl_assign (amount must match room price)
        $stmt2 = $conn->prepare("
            UPDATE tbl_assign 
            SET amount = ?
            WHERE room_id = ?
        ");
        $stmt2->execute([$price, $rmid]);

        // COMMIT CHANGES
        $conn->commit();
        ?>

        <script>
        jQuery(function(){
            swal("Well done!", "Room has been updated successfully.", "success", {
                button: "OK",
            });
        });
        </script>

        <?php

    } catch (Exception $e) {

        // SOMETHING FAILED → ROLLBACK
        $conn->rollback();

        ?>
        <script>
        jQuery(function(){
            swal("Error!", "Update failed: <?php echo $e->getMessage(); ?>", "error", {
                button: "OK",
            });
        });
        </script>
        <?php
    }
}

?>

<?php
	 // Delete ....
  if (isset($_GET['delete_room'])) {
    $room_id = $_GET['delete_room'];

    // 1. CHECK if room exists in tbl_assign
    $check = $db->prepare("SELECT COUNT(*) FROM tbl_assign WHERE room_id = ?");
    $check->execute([$room_id]);
    $assigned_count = $check->fetchColumn();

    if ($assigned_count > 0) {
        // ROOM IS IN USE - DO NOT DELETE
        ?>
        <script>
        jQuery(function(){
            swal("Warning!", "You cannot remove this room because has some tenant info.", "error", {
                button: "OK",
            });
        });
        </script>
        <?php
    } else {
        // 2. SAFE TO DELETE
        $sql = $db->prepare("DELETE FROM tbl_rooms WHERE room_id = ?");
        $sql->execute([$room_id]);
        ?>
        <script>
        jQuery(function(){
            swal("Done!", "Room removed successfully.", "success", {
                button: "OK",
            });
        });
        </script>
        <?php
    }
}

    // for activate Subject
    if(isset($_GET['acid']))
    {

        try{
    			$disable = 2;
    			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    			$sql = "UPDATE `tbl_rooms` SET `rm_status` = '".$disable."' WHERE `room_id` = '".$_GET['acid']."'";
    			$acidq = $db->prepare($sql);
    			$acidq->execute();
    			$msge="Room Deactivated successfully";
                echo '<meta http-equiv="refresh"'.'content="2;URL=?file=rooms">';
    		}catch(PDOException $e){
    			echo $e->getMessage();
    		}
    		
      }
    
     // for Deactivate Subject
        else if(isset($_GET['did']))
          {
     try{
    			$enable = 1;
    			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    			$sql = "UPDATE `tbl_rooms` SET `rm_status` = '".$enable."' WHERE `room_id` = '".$_GET['did']."'";
    			$didq = $db->prepare($sql);
    			$didq->execute();
    			$msg="Room Activated successfully";
                echo '<meta http-equiv="refresh"'.'content="2;URL=?file=rooms">';
    		}catch(PDOException $e){
    			echo $e->getMessage();
    		}
    		
    	}
?>
        <div class="container-fluid">
          <div class="row justify-content-center">
           
            <div class="col-12">
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
              <div class="row align-items-center mb-2">
                  
                  <?php  
               
                  
                  
                  ?>
         
                <div class="col">
                  <h2 class="h5 page-title">All Rooms</h2>
            
                </div>
                <div class="col-auto">
                  <button type="button" class="btn btn-lg btn-primary" name="add" id="add" data-toggle="modal" data-target="#addexampleModalCenter"><span class="fe fe-plus fe-16 mr-3"></span>New</button>
                </div>
                <div class="col-auto">
                 
                </div>
              </div>
               <!--<input type="text" value="<?php echo  strlen($cmp_sn); ?>">-->
              <div class="row my-4">
               <div class="col-md-12">
                  <div class="card shadow">
                    <div class="card-body">
                      <!-- table -->
                      <table class="table datatables" id="dataTable-1">
                      <thead>
						<tr>
							<th>#</th>
							<th>Room No.</th>
							<!--<th>Availability</th>-->
							<th>Area(Sqm.)</th>
							
							<th>Room Status</th>
							<th>Price</th>
							<th></th>
							<th>Action</th>
						</tr>
					</thead>
                       <tbody>
                        <?php
                            $sql_rooms = $db->prepare("SELECT * FROM  tbl_rooms
                            
                            WHERE tbl_rooms.company_id = '".$company."' and room_id not in (SELECT room_id FROM tbl_assign where assgn_status=1) and rm_status=1 and availability=3 order by room_no asc");
                            $sql_rooms->execute();
                            $i = 0;
                            $tot_price = 0;
                            //tbl_company
                            while($fetrooms = $sql_rooms->fetch()) {
                                $rm_id = $fetrooms['room_id'];
                                $stat = $fetrooms['rm_status'];
                                $avail = $fetrooms['availability'];
                                $tot_price =$tot_price+$fetrooms['price'];
                                
                                $sql_stat = $db->prepare("SELECT * FROM tbl_status where id='$stat'");
                                $sql_stat->execute();
                                $fetstat = $sql_stat->fetch();
                                
                                $sql_avail = $db->prepare("SELECT * FROM tbl_status where id='$avail'");
                                $sql_avail->execute();
                                $fetavail = $sql_avail->fetch();
                                $i += 1;
                                
                                $sqlass = $db->prepare("SELECT * FROM tbl_assign where room_id='".$rm_id."' ");
                            $sqlass->execute();
                            
                            $sqlass200 = $db->prepare("SELECT * FROM tbl_invoice where 	inv_room_id='".$rm_id."' ");
                            $sqlass200->execute();
                            $rommdata = $sqlass200->rowCount();
                            
                             $sqlass200bill = $db->prepare("SELECT * FROM tbl_bill where 	inv_room_id='".$rm_id."' ");
                            $sqlass200bill->execute();
                            $rommdatabill = $sqlass200bill->rowCount();
                            
                            $romm = $sqlass->fetch();
                            
                          
                        ?>
						<tr >
							<td><?php echo $i; ?></td>
            				<td class="center">
                				<span><?php echo $fetrooms['room_no']; ?></span><br/>
            				</td>
						
							<td><?php echo $fetrooms['room_area']; ?> m<sup>2</sup></td>
							
							<td>
        						<?php if($fetstat['id'] == 1)
                                  { ?>
                                  
                                    <!--<a href="?file=rooms&acid=<?php echo $rm_id;?>" onclick="if(!confirm('Do you really want to Activate This Room?'))return false;else return true;"><label class="label label-success"><?php echo $fetstat['status_name']; ?></label></a>-->
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="flexSwitchCheckDefault<?php echo $rm_id  ?>"  checked>
                      <label class="custom-control-label" for="customPass"></label>
                    </div>
                                    <?php } else {?>
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="flexSwitchCheckDefault<?php echo $rm_id  ?>"  >
                      <label class="custom-control-label" for="customPass"></label>
                    </div>
        
                                    <!--<a href="?file=rooms&did=<?php echo $rm_id;?>" onclick="if(!confirm('Do you really want to Deactivate This Room?'))return false;else return true;"><label class="label label-default"><?php echo $fetstat['status_name']; ?></label></a>-->
                                 <?php }?>
							</td>
							<td><?php echo  number_format($fetrooms['price'], 4, '.', ' '); ?></td>
							<td>
							 <div class="col-auto" id="addClient<?php echo $rm_id; ?>" id="add" data-toggle="modal" data-target="#allocate<?php echo $rm_id; ?>">
                              <span class="circle circle-sm bg-secondary"><i class="fe fe-user-plus fe-16 text-white"></i></span>
                            </div>   
						<script>
						    document.getElementById("addClient<?php echo $rm_id; ?>").style.cursor = "pointer";
						</script>
						
							</td>
                            <td class="cell-icon text-center">
                                <a href="" data-toggle="modal" data-target="#update<?php echo $rm_id;?>" class="edit_data"><i class="icon-edit"></i> Edit</a>
                                |
                                <?php
                                if($rommdata==0 and $rommdatabill==0){
                                ?>
                                <a href="?file=Arooms&&delete_room=<?php echo $rm_id;?>&&room_no=<?php echo $fetrooms['room_no'];?>" onclick="if(!confirm('Do you really want to Delete This Room?'))return false;else return true;" title="Delete room"><span style="color: #870813;"><i class="icon-trash"></i> Delete</span></a>
                                <?php 
                                }
                                ?>
                            
                            </td>
						</tr>
						 <?php include'clientRoom.php'; ?>
                        <?php include'room_update_modal.php';?>
						<?php
                            }
						?>
					</tbody>
					     <tfoot>
                        <tr>  
                          <td >Total.</td>
                          <td  colspan="3" ></td>
                          <td  align="center"><?php echo number_format($tot_price).".00";?></td>
                          <td ></td>
                           <td ></td>
                        </tr>
                      </tfoot>
				</table>
                      </table>
                    </div>
                  </div>
              </div>
              <!-- .row -->
            </div> <!-- .col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        
        <?php include'new_rooms.php'; ?>
        
  