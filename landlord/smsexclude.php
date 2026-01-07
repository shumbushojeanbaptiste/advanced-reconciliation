
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
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>Sorry!</strong>  <?php echo $msge; ?> 
			</div>
		    <?php
		       }
		    ?>
              <div class="row align-items-center mb-2">
         <?php 
         $ten_stmtTotal = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_staff='".$cpny_ID."' and rent_status=1 and ten_id  in (select tent_id from tbl_assign where assgn_status=1 group by tent_id) and ten_id not in (select tenant_id from tbl_tenant_leave group by tenant_id) ");
                $ten_stmtTotal->execute();
                $total=$ten_stmtTotal->rowCount();
         ?>
                <div class="col">
                  <h2 class="h5 page-title">All Tenants with SMS exclusive (<?php echo $total; ?>)   
                  <!--<label class="text-success fa fa-circle"></label> Assigned <label class="text-warning fa fa-circle"></label> Not Assigned -->
                  </h2>
            
                </div>
                 <div class="col-auto">
                  <!--<button type="button" class="btn btn-lg btn-primary" name="add" id="add" data-toggle="modal" data-target="#tenantsModal"><span class="fe fe-plus fe-16 mr-3"></span>Add New</button>-->
                </div>
                <div class="col-auto">
                 
                </div>
              </div>
              <div class="row my-4">
               <div class="col-md-12">
                  <div class="card shadow">
                    <div class="card-body">
                   <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Manage Tenant</a>
                        </li>
                        <!--<li class="nav-item">-->
                        <!--  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Assign to Rooms</a>-->
                        <!--</li>-->
                      
                      </ul>
                      <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"> 
                         <div class="module-body table">
<table class="table datatables">	
		<thead>
			<tr>
			    <th>#</th>
				<th>Names</th>
				<th>Phone</th>
				<th>TIN No</th>
				<th>Monthly Amount</th>
				<th></th>
			
			</tr>
		</thead>
		<tbody>
			<?php
		   	    $i= 0 ;
		   	    $totalAMT = 0; // Initialize total amount variable
                $ten_stmt = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_staff='".$cpny_ID."' and rent_status=1 order by ten_id asc");
                $ten_stmt->execute();
                while($fetch_ten = $ten_stmt->fetch()) {
                    $t_id = $fetch_ten['ten_id'];
                    $ten_staff = $fetch_ten['ten_staff'];
                    $ten_ref_no=$fetch_ten['ten_ref_no'];
                    
                    $sqlstm=$conn->prepare("SELECT * FROM tbl_assign WHERE tent_id='".$t_id."' and assgn_status=1 ");
                    $sqlstm->execute();
                    $fettbl_assign = $sqlstm->fetch(PDO::FETCH_ASSOC);
                    if ($fettbl_assign) {
    $r_id = $fettbl_assign['room_id'];
} else {
    $r_id = null;
}
                    $rowCountRoom= $sqlstm->rowCount();
                    
                        
                        $sqlstm1=$conn->prepare("SELECT * FROM tbl_rooms WHERE room_id='".$r_id."' ");
                    $sqlstm1->execute();
                    $fetchsqlstm1=$sqlstm1->fetch();
                    $counter=$sqlstm1->rowCount();
                    if($counter > 0 && $fetchsqlstm1){
                        $r_no=$fetchsqlstm1['room_no'];
                    } else {
                        $r_no = "";
                    }
                      
                    
                    
                    $i += 1;
                    
                    // Initialize notf_Count to avoid undefined variable error
                    $notf_Count = $rowCountRoom;
                    
                    // check if row not exist
                    if ($notf_Count == 0)  {
                        $bullet = '<label class="text-warning fa fa-circle"></label>';
                        $hidden= 0;
                        
                    } 
                    else{
                         $bullet = '<label class="text-success fa fa-circle"></label>';
                         $hidden= 1;
                    }
                    
                 if($cpny_ID==10){
                     
                    // $sqlstm1D=$conn->prepare("DELETE FROM tbl_payment WHERE pay_ref_no='".$ten_staff."' ");
                    // $sqlstm1D->execute(); 
                 }   
             ?>
			<tr class="gradeA">
			    <td class="center">
				    <?php echo $i; ?>
				</td>
			
				<td class="center">
				    <?php if($r_no==""){
				    echo $fetch_ten['te_names']." ";   
				    
				    }
				    else {
				     
				     echo $fetch_ten['te_names']." ";   
				    
				    } ?>
				</td>
				<td class="center">
				    <?php echo $fetch_ten['ten_phone']; ?>
				</td>
				<td class="center">
				<?php if($fetch_ten['tin_no'] == ""){
				     echo "-";
				    }else{
				 echo $fetch_ten['tin_no']; 
				 }?>
    			</td>
    			<td>
    			    
    			      <?php 
                     $sql12 = $conn->prepare("SELECT SUM(amount) AS total FROM tbl_assign
                     
                     INNER JOIN tbl_rooms ON tbl_rooms.room_id=tbl_assign.room_id             
                     WHERE tbl_assign.tent_id = '".$t_id."' and assgn_status=1  ");
                        $sql12->execute(array()); 
                        $rowcount = $sql12->rowCount();
                       
                         $row12 = $sql12->fetch(PDO::FETCH_ASSOC);
                         $tot_assign =$row12['total'];
                       
                                 
                        $sql13 = $conn->prepare("SELECT SUM(discount) AS disc FROM tbl_assign
                        INNER JOIN tbl_rooms ON tbl_rooms.room_id=tbl_assign.room_id  
                        WHERE tent_id = '".$t_id."' and assgn_status=1 ");
                        $sql13->execute(array()); 
                        $rowcount13 = $sql13->rowCount();
                       
                       $row13 = $sql13->fetch(PDO::FETCH_ASSOC);
                         $tot_disc = $row13['disc'];
                        
                        
                       $paymon = $row12['total'] - $row13['disc'];
                       
                       $tot_paymon = $tot_assign-$tot_disc;
                       $totalAMT=$totalAMT+$tot_paymon;
                       echo $tot_paymon;
                     ?>
    			</td>
    			<td></td>
    		
			</tr>
		
			<!-- Form modal for tenant update -->
    <div class="modal fade" id="update<?php echo $fetch_ten['ten_id']?>" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"><div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
    <div class="modal-header"> 
         <h4 class="modal-title"><i class="fe fe-edit"></i> Edit Tenant Info</h4>
         <button type="button" class="close" data-dismiss="modal">&times;</button>  
           
    </div>  
    <div class="modal-body">
    <form class="form-horizontal row-fluid" id="add_tenant" method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="tid" value="<?php echo $fetch_ten['ten_id']?>"/>
        
                        <div class="row">
                        <div class="col-md-3">
             	         	<label class="control-label" for="basicinput"> 
             	         	<?php  
                        if($fetch_ten['tenant_type']==1){
                            ?> Company names <?php } else {  ?>  Names<?php } ?> </label>
             	        </div>
                        <div class="col-md-9">
                   
                        <div class="form-group mb-10">
                    
                        	<input type="text" name="tename" id="tename" value="<?php echo $fetch_ten['te_names']; ?>" class="form-control" required>
                        </div>
                        </div>
                        </div>
                         <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="basicinput">Tin No </label>
             	         
             	        </div>
                        <div class="col-md-9">
                   
                        <div class="form-group mb-10">
                        <input type="text" name="tin_no" id="tin_no" value="<?php echo $fetch_ten['tin_no']; ?>"  class="form-control" >
                        </div>
                        </div>
                        </div>
                        <?php  
                        if($fetch_ten['tenant_type']==1){
                            ?>
                           
                             <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">REPERSENTED BY </label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="Reprsented_name" id="Reprsented_name" value="<?php echo $fetch_ten['leader_name']; ?>" class="form-control" >
                     </div>
                </div>
                </div> 
                        <?php
                        }
                        ?>
    	                <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="basicinput">Phone </label>
             	         
             	        </div>
                        <div class="col-md-9">
                   
                        <div class="form-group mb-10">
                        <input type="text" name="phone" id="phone" value="<?php echo $fetch_ten['ten_phone']; ?>"  class="form-control" >
                        </div>
                        </div>
                        </div>
                        
                            <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">ID/PASSPORT </label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="passport" id="passport"  class="form-control" value="<?php echo $fetch_ten['id_number']; ?>" >
                     </div>
                </div>
                </div>
    
    	                <div class="row">
                        <div class="col-md-3">
                           <label class="control-label" for="basicinput">Email</label>
             	         
             	        </div>
                        <div class="col-md-9">
                   
                        <div class="form-group mb-10">
                        
                        	<input type="email" name="email" id="email" value="<?php echo $fetch_ten['ten_email']; ?>" class="form-control">
                        </div>
                        </div>
                        </div>
                        
                         <div class="row">
                        <div class="col-md-3">
                           	<label class="control-label" for="basicinput">Address</label>
             	         
             	        </div>
                        <div class="col-md-9">
                   
                        <div class="form-group mb-10">
                        
                        <textarea class="form-control" name="address" id="address" value="" class="form-control" rows="2" > <?php echo $fetch_ten['ten_address']; ?></textarea>
    		
    		            </div
                        </div>
                        </div>
                        </div>
    	
    
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" name="update_tenant" id="update_tenant" class="btn btn-primary">Submit</button>
    </form>
    </div>
    </div>
    </div>
    </div>
<!-- /form modal -->

			<?php
                }
			?>
		 </tbody>
	</table>
 </div>
 </div>
               
                        
                      </div>
                    </div>
                  </div>
              </div>
              <!-- .row -->
            </div> <!-- .col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        
        <div style="display:none;" id="loadingimg"><img src="../images/loader.gif" ></div>
         <div id="modassignsrooms"></div> 
    </div>
    
</div><!-- End Content -->
 

 
</div><!--/.module-->

</div><!--/.content-->
</div><!--/.span9-->

<!-- Form modal for tenant insertion -->
 <div class="modal fade" id="tenantsModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="tenantsModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header">  
        <h4 class="modal-title"> <i class="fe fe-plus-circle"></i> Select a Tenant to exclude SMS</h4> 
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
              
        </div>  
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="addex" method="post" action="" enctype="multipart/form-data">
              <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Client name	<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                  <select id="cname" class="form-control select2" name="cname" data-placeholder="Choose one..." style="width:100%" required >
                             <option></option>
                                 <?php
                                     $stmt = $db->query('SELECT * FROM tbl_tenants WHERE ten_staff="'.$cpny_ID.'" and rent_status !=1 and ten_id not in (select tenant_id from tbl_tenant_leave group by tenant_id) order by ten_id asc');
        
                                            try
                                            {
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $row['ten_id']; ?>"><?php echo $row['te_names']; ?></option>
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
                </div>
                  
                   <input type="hidden" name="tnt_id" id="tnt_id" value="<?php echo $tnts_id; ?>" class="span8" required>
				   <input type="hidden" name="added_by" id="added_by" value="<?php echo $cpny_ID; ?>" class="span8" required>
            
            <div id="other_tenant_info"></div>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="insert" id="insert" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
 </div>
<!-- /form modal -->

      <?php  //include'add_new_tenants.php'; ?>
