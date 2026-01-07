 <?php 
  if(isset($_REQUEST['saveDiscount'])){
      try {
          $ridNo = $_REQUEST['ridNo'];
          $amount = $_REQUEST['amount'];
          $descption = $_REQUEST['descption'];
          $today = date('Y-m-d');
          $status = 1;
          $Inactive = 0;
          
          $stms_last = $db->prepare("SELECT room_id
             FROM tbl_room_discount WHERE room_id = '".$ridNo."'
             ORDER BY room_id DESC LIMIT 1");

             try {

                 $stms_last->execute(array());
                 $row_count_last = $stms_last->rowCount();
                 if ($row_count_last > 0)
                     {
                     $SqlUpdate = "UPDATE `tbl_room_discount` SET `assgn_status` = '$Inactive' WHERE room_id= '$ridNo'";
                     $conn->exec($SqlUpdate);
                     }
                 }
                 catch (PDOException $ex) {
                     echo $ex->getMessage();
                 }  
          
                
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = "INSERT INTO `tbl_room_discount` ( `room_id`, `disc_amount`,`description`, `recorded_date`,`assgn_status`) 
                VALUES ('$ridNo', '$amount', '$descption', '$today', '$status')";
                $conn->exec($stmt);
                
                 $AssignUpdate = "UPDATE `tbl_assign` SET `discount` = '$amount' WHERE room_id = '$ridNo' AND assgn_status = 1";
                 $conn->exec($AssignUpdate);
          
             }
             catch (PDOException $ex) {
                 echo $ex->getMessage();
             }  
          
            $msg="Discount have been applied on room";
            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=setdsc">';
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
                  <h2 class="h5 page-title">Setting Discounts </h2>
            
                </div>
                <div class="col-auto">
                  <button type="button" class="btn btn-lg btn-primary" name="add" id="add" data-toggle="modal" data-target="#modaldiscount">Setup</button>
                </div>
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
						<tr >
							<td class="cell-icon"></td>
							<td class="cell-title">Room No.</td>
							<td class="cell-author hidden-phone hidden-tablet">Discount Note</td>
							<td class="cell-author hidden-phone hidden-tablet">Rent Amount</td>
							<td class="cell-author hidden-phone hidden-tablet">Discount</td>
							<td class="cell-time align-right">Total Rent</td>
						</tr>
					</thead>
                       <tbody>
                       
						<?php 
						  $SqlRoom = $db->query("SELECT * FROM tbl_company 
						    INNER JOIN tbl_rooms ON tbl_rooms.company_id = tbl_company.cpny_ID
                            INNER JOIN tbl_room_discount ON tbl_room_discount.room_id = tbl_rooms.room_id
                            WHERE tbl_rooms.company_id = '".$company."' AND tbl_room_discount.assgn_status = 1 ORDER BY room_no ASC");
                            
                            while($rows = $SqlRoom->fetch()){
						?>
						
						<tr >
							<td class="cell-icon"><i class="icon-checker high"></i></td>
							<td class="cell-title"><div><?php echo $rows['room_no'];?></div></td>
							<td class="cell-title"><div><?php echo $rows['description'];?></div></td>
							<td class="cell-author hidden-phone hidden-tablet"><?php echo number_format($rows['price'].".00");?></td>
							<td class="cell-author hidden-phone hidden-tablet"><?php echo number_format($rows['disc_amount'].".00");?></td>
							<td class="cell-time align-right"><?php echo number_format($rows['price']-$rows['disc_amount'].".00");?></td>
						</tr>
						
						<?php 
                          }
						?>
					</tbody>
					 
				</table>
                      </table>
                    </div>
                  </div>
              </div>
              <!-- .row -->
            </div> <!-- .col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        
        
       <?php 
             include ('modal_discount.php');
            ?>