<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename= ".$_REQUEST['cpny']." Monthly_report.xls");
?>
<?php
 require_once ("../include/initialize.php");

// get dates from form

$company=$_REQUEST['cpny'];

$date_from = $_REQUEST['date_from'];
$monthly =$_REQUEST['monthly'];
?>
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
                        <div class="col-md-1"><label for="simpleinput"> </span></label></div> 
                        <div class="col-md-3">
                              
                        
                     </div>
                   </div>
                    </div>
                  </div>
              </div>
              <!-- .row -->
            </div> <!-- .col-12 -->

   <div class="row my-4">
               <div class="col-md-12">
                  <div class="card shadow">
                    <div class="card-body">
                      <!-- table -->
                      <table class="table datatables" style="font-size:13px; font-family:Bookman Old Style;width:100%" id="dataTable-1" border="1" >
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