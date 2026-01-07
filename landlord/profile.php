<?php
    // update profile pic
    // if(isset($_POST["submit2"])) {
    // 	$id1=$_POST['id1'];
    //     // Set image placement folder
    //     $target_dir = "../img/profile_pics/";
    //     // Get file path
    //     $target_file = $target_dir . basename($_FILES["profileImage"]["name"]);
    //     // Get file extension
    //     $imageExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    //     // Allowed file types
    //     $allowd_file_ext = array("jpg", "jpeg", "png");
        

    //     if (!file_exists($_FILES["profileImage"]["tmp_name"])) {
    //         echo '<script language="javascript">
    //               alert("Select new image to upload. ");
    //               window.location.href = "?file=profile";
    //               </script>';
    //     } else if (!in_array($imageExt, $allowd_file_ext)) {
    //         echo '<script language="javascript">
    //               alert("Allowed file formats .jpg, .jpeg and .png. ");
    //               window.location.href = "?file=profile";
    //               </script>';
    //     } else if ($_FILES["profileImage"]["size"] > 2097152) {
    //         echo '<script language="javascript">
    //               alert("File is too large. File size should be less than 2 megabytes. ");
    //               window.location.href = "?file=profile";
    //               </script>';
    //     } else {
    //         if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)) {
    //             $sql1 = "SELECT *from tbl_admins where id = '$id1'";
    //             $stmt1 = $conn->prepare($sql1);
    //             $stmt1->execute(); 
    //             echo $stmt1->rowCount();
    //             if($stmt1->rowCount() > 0) {
    //                 $sql2 = "UPDATE tbl_admins set profile_pic = '$target_file' where id='$id1'";
    //                 $stmt2 = $conn->prepare($sql2);
    //                  if($stmt2->execute()){
    //                     echo '<script language="javascript">
    //                           alert("Profile Updated successfully. ");
    //                           window.location.href = "?file=profile";
    //                           </script>';
    //                     echo'<meta http-equiv="refresh"'.'content="1;URL=?file=profile">'; 
    //                  }
    //             }
                
    //         } else {
    //             echo '<script language="javascript">
    //                   alert("Image coudn\'t be uploaded. ");
    //                   window.location.href = "?file=profile";
    //                   </script>';
    //         }
    //     }

    // }
    
    // update password
    if(ISSET($_POST['btn-update'])){
    	try{
    	    $id2=$_POST['id2'];
            $passwordcur=$_POST['passwordcur'];
            $password=$_POST['password'];
            $new_password=$_POST['new_password'];
            $c_password=$_POST['c_password'];
            $profile = $_POST['profileImage'];

            if(empty($password)){
                echo '<script language="javascript">
                      alert("Please Fill All Fields!!! ");
                      </script>';
            }
            else if((md5($password) == $passwordcur) AND !empty($new_password) AND ($c_password == $new_password)){
    			
    			//Update to User Login Account//
    			$new_password_encrypted = md5($new_password);
                
    			$sql = "UPDATE `tbl_users_login` SET `password` = '$new_password_encrypted' WHERE `role_id` = 2 AND `profile_id` = '$id2' ";
    			$q2 = $db->prepare($sql);
    			$q2->execute();
    		
                echo '<script language="javascript">
                      alert("Successfully Changed Password ");
                      window.location.href = "../logout";
                      </script>';
    			
            }
            
            else{
                echo '<script language="javascript">
                    alert(" Something Went Wrong ");
                    window.location.href = "?file=profile";
                </script>';
            }
            
    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }
?>


<?php
	$sql = $conn->prepare("SELECT * FROM `tbl_users_login` WHERE `login_id` = '$USER_ID' AND `role_id` = 2");
	$sql->execute();
	$fetchus = $sql->fetch();
	$u_id = $fetchus['profile_id'];

	$sql = $conn->prepare("SELECT * FROM `tbl_admins` WHERE `adm_id` = '$u_id' ");
	$sql->execute();
	$fetch = $sql->fetch();
    $admin_id = $fetch['adm_id'];
    $profile_pic = $fetch['profile_pic'];
    $firstName = $fetch['firstName'];
    $lastName = $fetch['lastName'];
    
    $company_id = $fetch['company_id'];
?>
<div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12 col-lg-12 col-xl-8">
                  <div class="card shadow mb-4">
                 <div class="card-body">
              <!--<h2 class="h3 mb-4 page-title">Profile</h2>-->
              <div class="my-4">
                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                     <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                        <span class="fe fe-24 fe-key"></span> Credentials</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"> 
                    <span class="fe fe-24 fe-user-check"></span> General</a>
                  </li>
                 
                  <!--<li class="nav-item">-->
                  <!--  <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Notifications</a>-->
                  <!--</li>-->
                </ul>
                 <div class="tab-content" id="myTabContent">
                     <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                     
                    
                  <div class="row mt-5 align-items-center">
                 
                    
                  </div>
                  <hr class="my-4">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="firstname">Firstname</label>
                      <input type="text" id="firstname" value="<?php echo $firstName; ?>" class="form-control" readonly>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="lastname">Lastname</label>
                      <input type="text" id="lastname" value="<?php echo $lastName; ?>" class="form-control" readonly>
                    </div>
                  </div>
                   <div class="form-row">
                      <div class="form-group col-md-6">
                          <label for="inputEmail4">Phone</label>
                    <input type="text" class="form-control" value="<?php echo $fetch['phone']?>" id="inputEmail4" readonly>
                        </div>
                      <div class="form-group col-md-6">
                    <label for="inputEmail4">Email</label>
                    <input type="email" class="form-control" value="<?php echo $fetch['email']?>" id="inputEmail4" readonly>
                    </div>
                  </div>
                   <?php
                                                     $sql = $conn->prepare("SELECT *FROM tbl_company WHERE cpny_ID='".$company_id."'");
                                                     $sql->execute();
                                                     $fetch_company = $sql->fetch();
                                                    
                                                    
                                                 
                                                 ?>
                 
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="inputCompany5">Company</label>
                      <input type="text" class="form-control"  value="<?php  echo $fetch_company['full_name'];?>" id="inputCompany5" placeholder="Nec Urna Suscipit Ltd" readonly>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputAddress5">Address</label>
                    <input type="text" class="form-control" value="<?php echo $fetch['address']?>" id="inputAddress5" placeholder="P.O. Box 464, 5975 Eget Avenue" readonly>
                  
                    </div>
                  
                  </div>
                  <div class="form-group">
                     <form id="profile_form" method="post" action="" enctype="multipart/form-data">
                                        <div class="stream-attachment photo">
                                            <div class="responsive-photo">
                                                <div class="thumbnail">
                                                    <div class="image view view-first">
                                                        <div class="col-md-3 text-center mb-5">
                  
                    </div>
                     <div class="form-group col-md-3">                                   
					<input type="hidden" id="basicinput" name="id1" value="<?php echo $u_id; ?>" class="span8" required>
                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                  <!--/.stream-headline-->
                                    <div class="stream-options">
                                        <center>
                                    <button type="submit" name="submit" class="btn btn-primary"><i class="icon-edit shaded"></i>Update Profile </button>
                                    </center>
                                    </div>
                                 </form>
                      
                                 
                          
                  </div>
                  <hr class="my-4">
                  
                  
                
                      </div>
                        
                        
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab"> 
                        
                         <form id="profile_pass_form" data-toggle="validator" method="post" action="" enctype="multipart/form-data">
						<div class="row mb-4">
						    <div class="col-md-3">
						        <label for="inputPassword4">Old Password</label>
						        </div>
                      <div class="col-md-8">
                      <div class="form-group">
                        <input type="hidden" id="basicinput" name="id2" value="<?php echo $admin_id; ?>" class="span8" required>
						<input type="hidden" id="passwordcur" name="passwordcur" value="<?php echo $fetchus['password']; ?>" class="span8">
						<input type="password" id="password" name="password" value="" placeholder="Type Current Password..." class="form-control" required>
                      </div>
                      </div>
                      </div>
                      
                      <div class="row mb-4">
                        <div class="col-md-3">
						 <label for="inputPassword5">New Password</label>
						 </div>  
						 <div class="col-md-8">
                      <div class="form-group">
                        
                        
                    <input type="password" id="new_password" minlength="5" name="new_password" placeholder="Type New Password..." class="form-control" required>

                      </div>
                      </div>
                      </div>
                      <div class="row mb-4">
                        <div class="col-md-3">
						 <label for="inputPassword6">Confirm Password</label>
						 </div>  
						 <div class="col-md-8">
                      <div class="form-group">
                        
                        
                        <input type="password" id="c_password" data-match="#new_password" data-match-error="Passwords don't match" name="c_password" placeholder="Confirm Password..." class="form-control" required>

                      </div>
                      </div>
                     </div>
                      <div class="row mb-4">
                        <div class="col-md-4">
						 
						 </div>  
						 <div class="col-md-4">
                      <div class="form-group">
                        	<button type="submit" id="btn-update" name="btn-update" class="btn btn-primary">Make Change</button>
                      </div>
                      </div>
                      </div>
                    </div>
                   
                  </div>

							

						

						
							</form>
							
                  
                  
                  
                  
                        </div>
                        </div>
                
              </div> <!-- /.card-body -->
            </div> <!-- /.col-12 -->
          </div> <!-- .row -->
        </div>