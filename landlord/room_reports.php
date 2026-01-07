    <?php
    require_once ("../include/initialize.php");
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
                 $max = $db->prepare("SELECT * FROM tbl_block_old order by blck_id asc limit 7");
                        $max->execute(array()); 
                        // $data100=$max->rowCount(); 
                        while($data100=$max->fetch()){
                            
                         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `tbl_block_old`( `block_name`, `Block_code`,`company_id`) 
            VALUES ('".$data100['block_name']."', '".$data100['Block_code']."',9)";
            $conn->exec($sql);   
                        }
                  
                  
                  ?>
         
                
               
                <div class="col-auto">
                 
                </div>
              </div>
              
               <div class="row my-4">
               <div class="col-md-12">
                  <div class="card shadow">
                    <div class="card-body">
                     <div class="form-group row">
                        <div class="col-md-1"></div> 
                         <div class="col-md-3">
                           <?php
                        
                        if($_REQUEST['view1']==1)
                        {
                         echo "<h4>Available Rooms</h4>" ; 
                        }
                        else if($_REQUEST['view1']==0)
                        {
                        echo "<h4>Occupied Rooms</h4>" ; 
                            
                        }
                        else{
                           echo "<h4>All Rooms</h4>" ; 
                        }
                        ?>
                             
                         </div> 
                          <div class="col-md-2"></div> 
                         <div class="col-md-1"></div>
                        <div class="col-md-1"><label for="simpleinput">Option </span></label></div> 
                        <div class="col-md-3">
                              
                        <?php
                        $originalDate1 =date("Y-m"); $newDate1 = date("F Y", strtotime($originalDate1));
                        ?>
                       <select id="optionroom" class="form-control select2" name="room_no" data-placeholder="Choose one..." style="width:100%" required >
                             <option></option>
                                <option value="1">Available Room</option>
                                  <option value="0">Occupied Room</option>
                                  <option value="3">All</option>
                            </select>
                     </div>
                   </div>
                    </div>
                  </div>
              </div>
              <!-- .row -->
            </div> <!-- .col-12 -->
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
							<th>Descriptions</th>
							<!--<th>Room Status</th>-->
							<th>Price</th>
						
						</tr>
					</thead>
                       <tbody>
                        <?php
                        
                        if($_REQUEST['view1']==1)
                        {
                         $sql_rooms = $db->prepare("SELECT * FROM  tbl_rooms WHERE tbl_rooms.company_id = '".$company."' and room_id not in (SELECT room_id FROM tbl_assign) order by room_no asc");   
                            
                        }
                        else if($_REQUEST['view1']==0)
                        {
                         $sql_rooms = $db->prepare("SELECT * FROM  tbl_rooms WHERE tbl_rooms.company_id = '".$company."' and room_id  in (SELECT room_id FROM tbl_assign) order by room_no asc");   
                        }
                        else{
                            $sql_rooms = $db->prepare("SELECT * FROM  tbl_rooms WHERE tbl_rooms.company_id = '".$company."' order by room_no asc");
                            
                        }
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
                                
                                $sqlass = $db->prepare("SELECT * FROM tbl_assign where room_id='".$rm_id."' ");
                            $sqlass->execute();
                            $romm = $sqlass->fetch();
                            
                            $sqlass700 = $db->prepare("SELECT * FROM tbl_tenants where ten_id='".$romm['tent_id']."' ");
                            $sqlass700->execute();
                            $romm700 = $sqlass700->fetch();
                        ?>
						<tr >
							<td><?php echo $i; ?></td>
            				<td class="center">
                				<span><?php echo $fetrooms['room_no']; ?></span><br/>
            				</td>
						
							<td><?php echo $fetrooms['room_area']; ?> m<sup>2</sup></td>
							<td><?php echo $fetrooms['descr']; ?></td>
						
							<td><?php echo number_format($fetrooms['price']).".00"; ?></td>
                         
						</tr>
						
                       
						<?php
                            }
						?>
					</tbody>
					     <tfoot>
                        <tr>  
                          <td >Total.</td>
                          <td  colspan="3" ></td>
                          <td  ><b><?php echo number_format($tot_price).".00";?></b></td>
                        
                        </tr>
                      </tfoot>
				</table>
                      </table>
                    </div>
                  </div>
              </div>
              <!-- .row -->
            </div> <!-- .col-12 -->
            
                 <div class="row my-4">
               <div class="col-md-12">
                  <div class="card shadow">
                    <div class="card-body">
                     <div class="form-group row">
                        <div class="col-md-1"></div> 
                         <div class="col-md-3">
                           <?php
                        
                        if($_REQUEST['view1']==1)
                        {
                         echo "<h4>Available Rooms</h4>" ; 
                        }
                        else if($_REQUEST['view1']==0)
                        {
                        echo "<h4>Occupied Rooms</h4>" ; 
                            
                        }
                        else{
                           echo "<h4>All Rooms</h4>" ; 
                        }
                        ?>
                             
                         </div> 
                          <div class="col-md-2"></div> 
                         <div class="col-md-1"></div>
                        <div class="col-md-2"><label for="simpleinput"> </span></label></div> 
                        <div class="col-md-2">
                              
                        <?php
                        $originalDate1 =date("Y-m"); $newDate1 = date("F Y", strtotime($originalDate1));
                        ?>
                       <a href="roomReportExcel.php?view1=<?php echo $_REQUEST['view1']; ?>&cpny=<?php echo $company; ?>">
    <button class="btn btn-success pull-left" style="margin:7px;">Export Excel</button>
    </a>
                     </div>
                   </div>
                    </div>
                  </div>
              </div>
              <!-- .row -->
            </div> <!-- .col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        
        <?php include'new_rooms.php'; ?>
                  <script type="text/javascript">
    $(document).ready(function () {

        $("#optionroom").change(function () {
            // var c_id=<?php echo $company ?>;
             var view1 = $('#optionroom').val();
            // var auto_code=$('#auto_code').val();
            // var last_CODE = $('#last_CODE').val();
            // var floor_code=$('#floor_code').val(); 
            window.location.href="?file=rpt-rooms&view1=" + view1;
		
        });

    });
</script>
  