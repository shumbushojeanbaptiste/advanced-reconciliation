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
        if(isset($_REQUEST['update_currency'])){
        
        
         $money_id = $_REQUEST['money_id'];
          $rwf_value = $_REQUEST['rwf_value'];
          
          
                 $AssignUpdate = "UPDATE `tbl_money` SET `intoR` = '$rwf_value' WHERE money_id = '$money_id'  ";
                 $conn->exec($AssignUpdate); 
          
        $msg="Currency have been changed";
            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=setcurrency">';
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
                  <h2 class="h5 page-title">Manage Currency </h2>
            
                </div>
                <!--<div class="col-auto">-->
                <!--  <button type="button" class="btn btn-lg btn-primary" name="add" id="add" data-toggle="modal" data-target="#modaldiscount">Setup</button>-->
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
						<tr >
							<td class="cell-icon"></td>
							<td class="cell-title">Currency Name.</td>
							<td class="cell-author hidden-phone hidden-tablet">Unit Value</td>
							<td class="cell-author hidden-phone hidden-tablet">Rwandan Value(Rwf)</td>
						
							<td class="cell-time align-right"></td>
						</tr>
					</thead>
                       <tbody>
                       
						<?php 
						
						  $SqlRoom44 = $db->prepare("SELECT * FROM tbl_money WHERE m_status=1 and money_id!=1 and cpn_id='".$company."' ");
                            	$SqlRoom44->execute();
                            	
                            while($rows = $SqlRoom44->fetch()){
						?>
						
						<tr >
							<td class="cell-icon"><i class="icon-checker high"></i></td>
							<td class="cell-title"><div><?php echo $rows['m_name'];?></div></td>
							<td class="cell-title"><div><?php echo $rows['m_unit']." "."$";?></div></td>
							<td class="cell-author hidden-phone hidden-tablet"><?php echo $rows['intoR']." "."Rwf";?></td>
						
							<td class="cell-time align-right">
							  <a href="#" data-toggle="modal" data-target="#modal-currency<?php echo $rows['money_id']; ?>" style="color:#DC3545;">  <i class="fe fe-edit"></i> </a> 
							</td>
						</tr>
						
						<?php 
						include'currency-edit.php';
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
        
        
   