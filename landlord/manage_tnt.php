<?php
 if(ISSET($_POST['insert'])){
    $sql_check = $db->prepare("SELECT * FROM tbl_tenants WHERE ten_phone = ? and ten_staff=?");
    try {
        $currency_id=$_POST['currency_id'];
        $tename = $_POST['tename'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $Reprsented_name=$_POST['Reprsented_name'];
        $t_type=$_POST['t_type'];  
        $address = $_POST['address']; 
        $added_by = $_POST['added_by'];
        $today = date("Y-m-d");
        $tin_no=$_POST['tin_no'];
        $id_number=$_POST['passport'];
        $ref_no = random_strings(7); 
        $year = date("Y");
        $RefNo  = substr($year, -2).$ref_no;
        
        $sql_check->execute(array($phone,$cpny_ID));
        $row_count_check = $sql_check->rowCount();
                if ($row_count_check >= 1)
                {
                $msge="This Tenant is Already Registered!";
                   
                }
                else {
                     $li_qu_admin = $db->prepare("SELECT * FROM tbl_admins where company_id='".$cpny_ID."' ");
        $li_qu_admin->execute();
        $fli_admin = $li_qu_admin->fetch();
        $company_number=$fli_admin['phone'];
        
        $li_qu_adminMM = $db->prepare("SELECT * FROM tbl_company where cpny_ID='".$cpny_ID."' ");
        $li_qu_adminMM->execute();
        $fli_adminMM = $li_qu_adminMM->fetch();
        $cp_name_char=$fli_adminMM['msg_char'];
        $short_name=$fli_adminMM['side'];
                    //for user..
                    $time = date("H:i:s");
        
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO `tbl_tenants` (`ten_ref_no`, `te_names`, `ten_phone`, `ten_email`, `ten_address`, `ten_staff`, `ten_date`,`tin_no`,`tenant_type`,`leader_name`,`id_number`,`currency_id`) 
                    VALUES ('$RefNo', '$tename', '$phone', '$email', '$address', '$cpny_ID', '$today','$tin_no','".$t_type."','".$Reprsented_name."','".$id_number."','".$currency_id."')";
                    $qr = $conn->prepare($sql);
                    $qr->execute();
                   
                   
                    
                    $msgee="Mukiriya wacu $tename ,ubaruwe muri system yaba client bacu. code: $RefNo. Murakoze Tel: $company_number";
                     
            
            
            
            // $data=array(
            //           "sender"=>"$cp_name_char",
            //             "recipients"=>"$phone",
            //             "message"=>"$msgee",
            //     );
        
            // $url="https://www.intouchsms.co.rw/api/sendsms/.json";
            // $data=http_build_query($data);
            // $username="twagiramungus";
            // $password="M00dle!!";
        
            // $ch=curl_init();
            // curl_setopt($ch,CURLOPT_URL,$url);
            // curl_setopt($ch,CURLOPT_USERPWD,$username.":".$password);
            // curl_setopt($ch,CURLOPT_POST,true);
            // curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            // curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
            // curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
            // $result=curl_exec($ch);
            // $httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
            // curl_close($ch);
            
                             ?>
       <script>
               jQuery(function validation(){
        swal("Well done! ", "Client added!. ", "success", {
        button: "ok",
          });
        });
        </script>
   <?php 
                
                    }
               
            }catch(PDOException $e){
                echo $e->getMessage();
            }
         }
                 
    function random_strings($length_of_string) 
    { 
        $str_result = '1234567890'; 
        return substr(str_shuffle($str_result), 0, $length_of_string); 
    }
?>

<?php
	  // Delete ....
     if(ISSET($_GET['delete_tenant'])){
		$sql = $db->prepare("DELETE from `tbl_tenants` WHERE `ten_id`='".$_GET['delete_tenant']."' ");
		$sql->execute();
	
	                         ?>
       <script>
               jQuery(function validation(){
        swal("Well done! ", "Client Deleted!. ", "warning", {
        button: "ok",
          });
        });
        </script>
   <?php 
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
                echo '<meta http-equiv="refresh"'.'content="3;URL=?file=tenant-agree">';
    		}catch(PDOException $e){
    			echo $e->getMessage();
    		}
    		
    	}
    	//  update tenant
    	
        if(ISSET($_POST['update_tenant'])){
            $t_id = $_POST['tid'];
            $tename = $_POST['tename'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $address = $_POST['address'];
            $tin_no=$_POST['tin_no'];
            $receive=$_POST['receive'];
             $Reprsented_name=$_POST['Reprsented_name'];
              $id_number=$_POST['passport'];
            
            $sql = "UPDATE `tbl_tenants` set  `id_number`='$id_number',`leader_name`='$Reprsented_name', `tin_no`='$tin_no', `te_names` = '$tename', `ten_phone` = '$phone', `ten_email` = '$email', `ten_address` = '$address' ,
            rent_status ='".$receive."' where ten_id= '$t_id'";
            $conn->exec($sql);

                                   ?>
       <script>
               jQuery(function validation(){
        swal("Well done! ", "Client Updated!. ", "success", {
        button: "ok",
          });
        });
        </script>
   <?php 
        }
?>
    <?php
    if(ISSET($_POST['assign'])){
    
    try{
        $date = $_POST['date'];
        $tenant_id = $_POST['tid'];
        $rooms_id = $_POST['rooms'];
        $today = date("Y-m-d H:i:s");
        
        // select values from table rooms
        $max = $db->prepare("SELECT * FROM  `tbl_rooms` WHERE room_id='".$rooms_id."'");
        $max->execute(array());
        $rowcount = $max->rowCount();
        if ($rowcount > 0)
        {
        $row1 = $max->fetch(PDO::FETCH_ASSOC);
        $descr = $row1['descr'];
        $price = $row1['price'];
        $company_id = $row1['company_id'];
        }
        
        $sql_tes = $db->prepare("SELECT * FROM  `tbl_assign` WHERE room_id='".$rooms_id."' AND assgn_status = 1");
        $sql_tes->execute(array());
        $rowcount = $sql_tes->rowCount();
        
        // check if row not existpayment
        //  if ($rowcount == 0)
        if ($rowcount == 0 || $rowcount>0)
        {
            try{
            $assign = "INSERT INTO `tbl_assign` (`room_id`, `tent_id`,`company_id`,`rent_date`,`amount`,`description`,`recorded_date`,`assgn_status`) 
            VALUES ('$rooms_id', '$tenant_id', '$company_id','$date','$price','$descr','$today','1')";
            $conn->exec($assign);
                                         ?>
       <script>
               jQuery(function validation(){
        swal("Well done! ", "Client Assigned. ", "success", {
        button: "ok",
          });
        });
        </script>
   <?php 
            }catch(PDOException $e){
    			echo $e->getMessage();
    		}
    		
    // 		update table rooms
    
        $update = $conn->prepare("UPDATE `tbl_rooms` SET availability = 4 WHERE room_id = '".$rooms_id."' ");
        $update->execute();
    		
        }
        else{
                                             ?>
       <script>
               jQuery(function validation(){
        swal("Well done! ", "Room alread Assigned. ", "warning", {
        button: "ok",
          });
        });
        </script>
   <?php 
        }
  
       }catch(PDOException $e){
    	echo $e->getMessage();
    	 }
    }
    
    // remove
    
     if(ISSET($_GET['remove_room'])){
$assign_id=$_GET['assign_id'];

//     	$sqld = $db->prepare("DELETE from `tbl_assign` WHERE `assgn_id`='".$assign_id."' ");
// 		$sqld->execute();
	
		
		$update = $conn->prepare("UPDATE `tbl_assign` SET assgn_status =0 WHERE assgn_id = '".$assign_id."' ");
        $update->execute();
        
		$update = $conn->prepare("UPDATE `tbl_rooms` SET availability =3 WHERE room_id = '".$_GET['remove_room']."' ");
        $update->execute();
        //$msg="Room is successfully Removed";
		
                                              ?>
       <script>
               jQuery(function validation(){
        swal("Well done! ", "Room Removed From client. ", "warning", {
        button: "ok",
          });
        });
        </script>
   <?php 


	
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
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>Sorry!</strong>  <?php echo $msge; ?> 
			</div>
		    <?php
		       }
		    ?>
              <div class="row align-items-center mb-2">
         <?php 
         $ten_stmtTotal = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_staff='".$cpny_ID."' and ten_id  in (select tent_id from tbl_assign where assgn_status=1 group by tent_id) and ten_id not in (select tenant_id from tbl_tenant_leave group by tenant_id) ");
                $ten_stmtTotal->execute();
                $total=$ten_stmtTotal->rowCount();
         ?>
                <div class="col">
                  <h2 class="h5 page-title">All Tenants(<?php echo $total; ?>)   
                  <!--<label class="text-success fa fa-circle"></label> Assigned <label class="text-warning fa fa-circle"></label> Not Assigned -->
                  </h2>
            
                </div>
                 <div class="col-auto">
                  <button type="button" class="btn btn-lg btn-primary" name="add" id="add" data-toggle="modal" data-target="#tenantsModal"><span class="fe fe-plus fe-16 mr-3"></span>Add New</button>
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
                             <a href="exporttenant.php" style='float:right;'>Export</a>
<table class="table datatables" style="font-size:11px">	
		<thead>
			<tr>
			    <th>#</th>
				<th>Names</th>
				<th>Phone</th>
				<th>TIN No</th>
				<th>Coution</th>
				<th>Monthly Amount</th>
				<th>Room</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php
		   	    $i= 0 ;
		   	    $totalAMT = 0; // Initialize total amount variable
                $ten_stmt = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_staff='".$cpny_ID."' order by ten_id asc");
                $ten_stmt->execute();
                while($fetch_ten = $ten_stmt->fetch()) {
                    $t_id = $fetch_ten['ten_id'];
                    $ten_staff = $fetch_ten['ten_staff'];
                    $ten_ref_no=$fetch_ten['ten_ref_no'];
                    
                    $sqlstm = $conn->prepare("SELECT * FROM tbl_assign WHERE tent_id = :tid AND assgn_status = 1");
$sqlstm->bindParam(':tid', $t_id, PDO::PARAM_INT);
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
                    
                
                    
                    // check if row not exist
                    // if ($notf_Count == 0)  {
                    //     $bullet = '<label class="text-warning fa fa-circle"></label>';
                    //     $hidden= 0;
                        
                    // } 
                    // else{
                         $bullet = '<label class="text-success fa fa-circle"></label>';
                         $hidden= 1;
                    // }
                    
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
    			    
    			      $coution = $conn->prepare("SELECT SUM(coution_amaunt) AS totalCtion FROM tbl_ten_contracts
                      WHERE tbl_ten_contracts.tenant_id = '".$t_id."' and cntr_status=1  ");
                        $coution->execute(array()); 
                        $rowcoution = $coution->rowCount();
                        $coutiondata = $coution->fetch(PDO::FETCH_ASSOC);
                         
                         echo number_format($coutiondata['totalCtion']);
    			    ?>
    			</td>
    			<td>
    			    
    			      <?php 
    			      
    			      
                         
                         
                     $sql12 = $conn->prepare("SELECT SUM(price) AS total FROM tbl_assign
                     
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
                       
                       $tenant_id = $ten_ref_no; // assuming $ten_ref_no is defined somewhere above


                        

                     ?>
    			</td>
    			<td class="center" style="width:150px;">
				  <?php
				  if($fetch_ten['ten_address'] == ""){
				 // echo "-";
				    }else{
				 // echo $fetch_ten['ten_address'];
				    }
				  ?>
				   <a href="" data-toggle="modal" data-target="#viewRoom<?php echo $fetch_ten['ten_id']?>" class="edit_data"><i class="fe fe-eye"></i> Show room(<?php  echo $rowCountRoom ?>)</a>
    			</td>
    				<?php  include'viewRoom.php'; ?>
                <td class="cell-icon text-center">
                   <a href="" data-toggle="modal" data-target="#update<?php echo $fetch_ten['ten_id']?>" class="edit_data"><i class="fe fe-edit"></i> </a>
                
                     
                     <?php 
                     
                      $notify500 = $db->prepare("SELECT * FROM  `tbl_invoice` WHERE  `inv_ref_no`='".$ten_ref_no."'   ");
                 $notify500->execute(array());
                 
                 $notf_Count30 = $notify500->rowCount();
                 $notf_Count302 = $notify500->fetch();
                
                
                
                
                
                 
                      if($notf_Count30<=0){
                
                
                
          
                     ?>
                   <?php 
                      }
                   ?> 
                     
                   
                </td>
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
                        <input type="text" name="phone" id="phone" value="<?php echo $fetch_ten['ten_phone']; ?>"  class="form-control"  >
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
                           <label class="control-label" for="basicinput">Receive sms</label>
             	         
             	        </div>
                        <div class="col-md-9">
                   
                        <div class="form-group mb-10">
                        
                        <select id="receive" class="form-control select2" name="receive" data-placeholder="Choose one..." style="width:100%" required >
                            <?php if($fetch_ten['rent_status']==0){  ?>
                             
                              <option value="0"> Yes</option>
                              <?php  } else { ?>
                             <option value="1"> No</option>
                             <?php } ?>
                             
                              <?php if($fetch_ten['rent_status']==1){  ?>
                              
                               <option value="0"> Yes</option>
                              <?php  } else { ?>
                            <option value="1"> No</option>
                             <?php } ?>
                            </select>
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
 <div class="modal fade" id="tenantsModal"  tabindex="-1" role="dialog" aria-labelledby="tenantsModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header">  
        <h4 class="modal-title"> <i class="fe fe-plus-circle"></i> Add Tenant Info</h4> 
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
              
        </div>  
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="add_tenant" method="post" action="" enctype="multipart/form-data">
              <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Client Type	<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                    
                  <select id="t_type" class="form-control select2" name="t_type" data-placeholder="Choose one..." style="width:100%" required >
                             <option></option>
                                 <?php
                                     $stmt = $db->query('SELECT * FROM tbl_tenant_type');
        
                                            try
                                            {
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $row['t_type_id']; ?>"><?php echo $row['type_name']; ?></option>
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

   <script type="text/javascript">
    $(document).ready(function () {

        $("#t_type").change(function () {
           
			$(this).after('<div id="loader11a"><img src="../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('other_tenant_info?t_type=' + $(this).val(), function (data) {
             $("#other_tenant_info").html(data);
                $('#loader11a').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>

<script>
    function loadmodroomassigns(ten_id){
$('#tenantlist').hide();
/// Loading....
$("#loadingimg").show();
//alert(module_code+" - "+acad_cycle_id+" - "+campus);

$.post("load_modassignsroom.php", {ten_id:ten_id}, function(data){
$("#loadingimg").hide();
$('#modassignsrooms').html(data);
$('#modassignsrooms').show();
});

}
</script>



    
