       <?php
    if(ISSET($_POST['insert'])){
        $cpid = $_POST['cpid'];
        $floor = $_POST['floor'];
        $rm_code = trim($_POST['rm_code']);
        
        $max = $db->prepare("SELECT room_id,room_no FROM tbl_rooms WHERE room_no  LIKE '".$rm_code."%' ORDER BY room_id DESC LIMIT 1");
        $max->execute(array()); // execute the query with parameter $code...
        $rowcount = $max->rowCount();
        if ($rowcount > 0)// if there is data for that reg. number...
        {
        $row1 = $max->fetch(PDO::FETCH_ASSOC);
        // Verify the rooms...
        $room_no = $row1['room_no'];
        }
        
         $new = substr("$room_no", 3);                            
            $new=$new +1; 
                        
            if($new <10)
              {
                $c="0"; 
              }
            
              elseif($new >=10 AND $new < 100)
              {
                $c="";
              }
             $room_num =$rm_code.$c.$new;
             
         
    $sql_check = $db->prepare("SELECT * FROM tbl_rooms WHERE room_no = ?");
    try {
        
        $price = $_POST['price'];
        $descr = $_POST['descr'];
        $room_area = $_POST['room_area'];
        
        $sql_check->execute(array($room_num));
        $row_count_check = $sql_check->rowCount();
                if ($row_count_check >= 1)
                {
                $msge="This Room is Already Registered!";
                   
                }else{
                    
            //for user..
            $date = date("Y-m-d");
            $time = date("H:i:s");
            try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `tbl_rooms` ( `room_no`, `price`,`descr`, `room_area`,`company_id`,`r_floor`, `rm_status`) 
            VALUES ('$room_num', '$price', '$descr', '$room_area', '$cpid','$floor', 1)";
            $conn->exec($sql);
            }catch(PDOException $e){
                echo $e->getMessage();
            }

            $msg="Room with Number: <b>".$room_num."</b> has been registered Successfully!";
            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=rooms">';
                }
            }catch(PDOException $e){
                echo $e->getMessage();
            }
         }
         
        //  update room
        if(ISSET($_POST['update'])){
            $rmid = $_POST['rid'];
            $no = $_POST['room_no'];
            $price = $_POST['price'];
            $descr = $_POST['descr'];
            $area = $_POST['area'];
            
            $sql = "UPDATE `tbl_rooms` set `room_no` = '$no', `price` = '$price', `descr` = '$descr', `room_area` = '$area' where room_id= '$rmid'";
            $conn->exec($sql);

            $msg="Room with Number: <b>".$no."</b> has been modified Successfully!";
            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=rooms">';
        }
?>

<?php
	 // Delete ....
     if(ISSET($_GET['delete_room'])){
		$sql = $db->prepare("DELETE from `tbl_rooms` WHERE `room_id`='".$_GET['delete_room']."' ");
		$sql->execute();
	
		$msge="Room <b>".$_GET['room_name']."</b> is successfully Removed";
        echo '<meta http-equiv="refresh"'.'content="2;URL=?file=rooms">';
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
         
                <div class="col">
                  <h2 class="h5 page-title">Available Rooms</h2>
            
                </div>
                <!--<div class="col-auto">-->
                <!--  <button type="button" class="btn btn-lg btn-primary" name="add" id="add" data-toggle="modal" data-target="#addexampleModalCenter"><span class="fe fe-plus fe-16 mr-3"></span>New</button>-->
                <!--</div>-->
                <div class="col-auto">
                 
                </div>
              </div>
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
							<th>Availability</th>
							<th>Area(Sqm.)</th>
							<th>Descriptions</th>
							<th>Room Status</th>
							<th>Price</th>
							<!--<th>Action</th>-->
						</tr>
					</thead>
                       <tbody>
                        <?php
                            $sql_rooms = $db->prepare("SELECT * FROM  tbl_rooms WHERE tbl_rooms.company_id = '".$company."' and availability=3");
                            $sql_rooms->execute();
                            $i = 0;
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
                        ?>
						<tr >
							<td><?php echo $i; ?></td>
            				<td class="center">
                				<span><?php echo $fetrooms['room_no']; ?></span><br/>
            				</td>
							<td><?php if($fetavail['id'] == 4) {
							    $hidd = 1;
							    ?>
							    <label class="text-default fa fa-circle"></label> <?php echo $fetavail['status_name']; ?> 
							<?php
							}
							else {
							 $hidd = 0;
							?>
							    <label class="text-success fa fa-circle"></label> <?php echo $fetavail['status_name']; ?>  
							<?php
							    }
							?></td>
							<td><?php echo $fetrooms['room_area']; ?> m<sup>2</sup></td>
							<td><?php echo $fetrooms['descr']; ?></td>
							<td>
        						<?php if($fetstat['id'] == 1)
                                  { ?>
                                    <a href="?file=rooms&acid=<?php echo $rm_id;?>" onclick="if(!confirm('Do you really want to Activate This Room?'))return false;else return true;"><label class="label label-success"><?php echo $fetstat['status_name']; ?></label></a><?php } else {?>
        
                                    <a href="?file=rooms&did=<?php echo $rm_id;?>" onclick="if(!confirm('Do you really want to Deactivate This Room?'))return false;else return true;"><label class="label label-default"><?php echo $fetstat['status_name']; ?></label></a>
                                 <?php }?>
							</td>
							<td><?php echo number_format($fetrooms['price']).".00"; ?></td>
                          
						</tr>
						
                        <?php include'room_update_modal.php';?>
						<?php
                            }
						?>
					</tbody>
					     <tfoot>
                        <tr>  
                          <td >Total.</td>
                          <td  colspan="5" ></td>
                          <td  align="center"><?php echo number_format($tot_price).".00";?></td>
                          <!--<td ></td>-->
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
        
  