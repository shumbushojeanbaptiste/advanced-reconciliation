<?php
if(ISSET($_POST['update_user'])){
    
    try {
        $admin_id=$_POST['admin_id'];
        $f_name = $_POST['firstname'];
		$l_name = $_POST['lastname'];
		$mob_no = $_POST['mobile'];
		$email = $_POST["email"];
		$company = $_POST['company'];
		$address = $_POST['address'];
		
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE tbl_admins SET  firstName='".$f_name."',lastName='".$l_name."',email='".$email."',phone='".$mob_no."',address='".$address."',company_id='".$company."'
            WHERE adm_id='".$admin_id."' ";
            $conn->exec($sql);
            //$lastId = $conn->lastInsertId();

            $pdo_crud = "UPDATE tbl_users_login SET f_name='".$f_name."',l_name='".$l_name."',username='".$f_name."' WHERE profile_id='".$admin_id."' 
           ";
            $conn->exec($pdo_crud);
            
            $msg="Admin Account of <b>".$f_name." "."$l_name"."</b> have been Updated successfully";
                
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }
         }
         
         if(ISSET($_GET['delete'])){
        try{
		    
			$delete_data = $_GET['delete'];
			
			//for Deleting user
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = " DELETE FROM tbl_users_login WHERE profile_id='$delete_data' ";
			$conn->exec($sql);
			
			
			$sql2 = " DELETE FROM tbl_admins WHERE adm_id='$delete_data' ";
			$conn->exec($sql2);
			
			$msg =" User Has Been Deleted Successfuly ";

		}catch(PDOException $e){
			echo $e->getMessage();
		}
		
		echo'<meta http-equiv="refresh"'.'content="1;URL=?file=new_user">'; 
	 }
	 
	 if(ISSET($_GET['disable'])){
        try{
		    
			$status_up = $_GET['disable'];
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = " UPDATE tbl_admins SET adm_status=0 WHERE adm_id=$status_up; ";
			$conn->exec($sql);
			
			$msg=" User Inactivated";

		}catch(PDOException $e){
			echo $e->getMessage();
		}
		
		echo'<meta http-equiv="refresh"'.'content="2;URL=?file=new_user">'; 
	 }
	 
	 	if(ISSET($_GET['enable'])){
        try{
		    
			$status_up = $_GET['enable'];
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = " UPDATE tbl_admins SET adm_status=1 WHERE adm_id=$status_up; ";
			$conn->exec($sql);
			
			$msg=" User Activated";

		}catch(PDOException $e){
			echo $e->getMessage();
		}
		
		echo'<meta http-equiv="refresh"'.'content="2;URL=?file=new_user">'; 
	 }
    if(ISSET($_POST['newuser-form'])){
    $sql_check = $db->prepare("SELECT * FROM tbl_admins WHERE phone = ?");
    try {
        $f_name = $_POST['firstname'];
		$l_name = $_POST['lastname'];
		$mob_no = $_POST['mobile'];
		$email = $_POST["email"];
		$company = $_POST['company'];
		$address = $_POST['address'];
		
		$pdwr=substr($_POST['mobile'],4,10);
	
		$password = md5($pdwr);
		$username = $_POST['mobile'];
		
        
        $sql_check->execute(array($mob_no));  
        $row_count_check = $sql_check->rowCount();
                if ($row_count_check >= 1)
                {
                $msge="Mobile number have been taken!";
                   
                }
                else{
                    
            //for user..
            //  $user_pass = openssl_encrypt($mobilenumber, "AES-128-ECB", DONE);
            $date = date("Y-m-d");
            $time = date("H:i:s");
            $role_id = '2';
            $staff_id = '1';
            $reg_date = date("Y-m-d H:i:s");
            
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `tbl_admins` (`firstName`, `lastName`,`email`,`phone`, `address`,`company_id`,`reg_Date`) 
            VALUES ('$f_name', '$l_name', '$email', '$mob_no', '$address', '$company', '$date')";
            $conn->exec($sql);
            $lastId = $conn->lastInsertId();

            $pdo_crud = "INSERT INTO `tbl_users_login` (`f_name`, `l_name`, `username`, `password`, `role_id`, `profile_id`,`telephone`, `reg_date`, `log_status`) 
            VALUES ('$f_name', '$l_name', '$username', '$password', '$role_id', '$lastId','$mob_no', '$date', '1')";
            $conn->exec($pdo_crud);
            $link="https://xode.rw/";
             $msg="Dear $f_name,Your Account has been created successfuly to access Xode Apps. username: $username and Password:$pdwr .Thank You link:www.xode.rw";   
        
              
              
        $li_qu_adminMM = $db->prepare("SELECT * FROM tbl_company where cpny_ID='".$company."' ");
        $li_qu_adminMM->execute();
        $fli_adminMM = $li_qu_adminMM->fetch();
        $cp_name_char=$fli_adminMM['msg_char'];
        $cp_name_char="ITECPAY";
            
                $data=array(
                      "sender"=>"$cp_name_char",
                        "recipients"=>"$mob_no",
                        "message"=>"$msg",  
                );
        
            $url="https://www.intouchsms.co.rw/api/sendsms/.json";
            $data=http_build_query($data);
            $username=$smsusername;
            $password=$smspwd;
        
            $ch=curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_USERPWD,$username.":".$password);
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
            $result=curl_exec($ch);
            $httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            
            $msg="Admin Account of <b>".$f_name."</b> have been created USN: ".$f_name." Pwd:".$mob_no;
                }
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
                  <h2 class="h5 page-title">All Users</h2>
            
                </div>
                <div class="col-auto">
                  <button type="button" class="btn btn-lg btn-primary" name="add" id="add" data-toggle="modal" data-target="#addexampleModalCenter"><span class="fe fe-plus fe-16 mr-3"></span>New User</button>
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
						<tr>
							<th>#</th>
							<th>Names.</th>
							<th>Phone</th>
							<th>Company</th>
							<th>Email</th>
							<th>Addres</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
                       <tbody>
                        <?php
                            $sql_rooms = $db->prepare("SELECT * FROM tbl_admins ");
                            $sql_rooms->execute();
                            $i = 0;
                            while($fetrooms = $sql_rooms->fetch()) {
                                $f_name = $fetrooms['firstName'];
                                $lastName = $fetrooms['lastName'];
                                $phone = $fetrooms['phone'];
                                $address =$fetrooms['address'];
                                $company_id =$fetrooms['company_id'];
                                $email =$fetrooms['email'];
                                $adm_status =$fetrooms['adm_status'];
                                $adm_id=$fetrooms['adm_id'];
                                
                                $sql_stat = $db->prepare("SELECT * FROM tbl_status where id='$adm_status'");
                                $sql_stat->execute();
                                $fetstat = $sql_stat->fetch();
                                
                                $sql_Canpany = $db->prepare("SELECT * FROM  tbl_company where cpny_ID='$company_id'");
                                $sql_Canpany->execute();
                                $fetCanpany = $sql_Canpany->fetch();
                                $Cname=$fetCanpany['full_name'];
                                $i += 1;
                        ?>
						<tr >
							<td><?php echo $i; ?></td>
            				<td class="center">
                				<span><?php echo $f_name." ".$lastName; ?></span><br/>
            				</td>
						
							<td><?php echo $phone; ?> </td>
							<td><?php echo $Cname; ?></td>
							<td><?php echo $email; ?></td>
							<td><?php echo $address; ?></td>
							<td>
        						<?php if($adm_status == 1)
                                  { ?>
                               <a href="?file=new_user&disable=<?php echo $adm_id;?>" >
                              <div id="clk<?php echo $adm_id;?>" class="custom-control custom-switch" >
                              <input type="checkbox" class="custom-control-input" value="<?php echo $adm_id;?>" id="c1<?php echo $adm_id;?>" checked >
                              <label class="custom-control-label" for="c1<?php echo $adm_id;?>"></label>
                              </div> </a><?php }
                              
                              else  if($adm_status == 0) {?>
        
                                    <a href="?file=new_user&enable=<?php echo $adm_id;?>" >
                                <div id="clk<?php echo $adm_id;?>" class="custom-control custom-switch"  >
                                <input type="checkbox" class="custom-control-input" value="<?php echo $adm_id;?>" id="c2<?php echo $adm_id;?>" >
                                <label class="custom-control-label" for="c2<?php echo $adm_id;?>"></label>
                              </div></a>
                                 <?php }?>
                                 <script>
                                     $("#c1<?php echo $adm_id;?>").click(function () {
                                   
                                  var id=$("#c1<?php echo $adm_id;?>").val();
                                  //alert(id);
                                    $.get('disable.php?disable='+$(this).val(), function (data){
                                    $("#record_list").html(data);
                              });
                                  
                                    
                                });
                                
                                 </script>
                                 <script>
                                     $("#c2<?php echo $adm_id;?>").click(function () {
                                   
                                  var id=$("#c1<?php echo $adm_id;?>").val();
                                  
                                  //alert(id);
                                    $.get('disable.php?enable='+$(this).val(), function (data){
                                   
                                    $("#record_list").html(data);
                              });
                                  
                                    
                                });
                                
        
                                 </script>
                            
							</td>
						
                            <td class="cell-icon text-center">
                                 <button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted sr-only">Action</span>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right">
                                 <a class="dropdown-item" href="" data-toggle="modal" data-target="#update<?php echo $adm_id;?>" class="edit_data"><i class="icon-edit"></i> Edit</a>
                                 <a  class="dropdown-item" href="?file=new_user&&delete=<?php echo $adm_id;?>" onclick="if(!confirm('Do you really want to Delete This user?'))return false;else return true;" 
                                 title="Delete room"><span style="color: #870813;"><i class="icon-trash"></i> Delete</span></a>
                               
                              </div>
                               
                            
                            </td>
						</tr>
						
                        <?php include'update_user.php';?>
						<?php
                            }
						?>
					</tbody>
					     <tfoot>
                      <div id="record_list"></div>
                      </table>
                    </div>
                  </div>
              </div>
              <!-- .row -->
            </div> <!-- .col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        
       
         <?php include'modal_new_user.php'; ?>
  
        
        
        
        
         