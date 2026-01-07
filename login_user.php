<?php
    ob_start();
    require_once "./include/initialize.php";
    include 'hd.php';
            $now_date = date('Y-m-d');
            	
            	 // Randomly generate code.
                function sessioncode() {
                    $chars = "003232303232023232023456789";
                    srand((double)microtime()*1000000);
                    $i = 0;
                    $pass = '' ;
                    while ($i <= 7) {
            
                        $num = rand() % 33;
            
                        $tmp = substr($chars, $num, 1);
            
                        $pass = $pass . $tmp;
            
                        $i++;
            
                      }
                    return $pass;
                }
                $scode=''.sessioncode();
                $sess_id= md5($scode);
                
                if(!empty($_POST["login-remember-me"])) {
            	setcookie ("login-email",$_POST["login-email"],time()+ 3600);
            	setcookie ("login-password",$_POST["login-password"],time()+ 3600);
            // 	echo "Cookies Set Successfuly";
            } else {
            	setcookie("login-email","");
            	setcookie("login-password","");
            // 	echo "Cookies Not Set";
            }
            	
            	if(ISSET($_POST['loginbtn'])){
            		if($_POST['login-email'] != "" || $_POST['login-password'] != ""){
            			$usn = $_POST['login-email'];
            			
            			$password = $_POST['login-password'];
            			
            			$pwd = md5($_POST["login-password"]);
            		
            			$tm = date("Y-m-d H:i:s");
                        $ip = $_SERVER['REMOTE_ADDR'];
            		
            		   $stmt = $db->query("SELECT * FROM tbl_users_login WHERE username='$usn' AND password = '$pwd' AND log_status='1'");
                        try {
                            $rowcount = $stmt->rowCount();
            
                            if ($rowcount > 0)// if there is data for that reg. number...
                                {
                                    
   $smsAPI = $db->query("SELECT * FROM tbl_smsAPI");
//   $smsAPI->execute();
   $smsAPI = $smsAPI->fetch();
   $APIuser= $smsAPI['username'];
   $APIpwd= $smsAPI['pwd'];
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    
                                    $role = $row['role_id'];
                                    $profile = $row['profile_id'];
                                    $f_name = $row['f_name'];
                                    $l_name = $row['l_name'];
                                    $user_phone = $row['telephone'];
                                    $date = $row['reg_date'];
                                    $lgn_id = $row['login_id'];
                                     $side_id = $row['side'];
                                     
                                      $park = $row['park'];
                                    
                                    include('./include/new_session.php');
                                     
                                    // Include Page for Invoice
                                    // include('./landlord/invoice_query.php');
                                    // Include Message Query
                                    
                                    $register_user = $db->prepare("INSERT INTO tbl_user_session(sess_id,phone,ip,time_login)VALUES(?,?,?,?)");
                                    try {
                                        // insert into tbl_personal_ug
                                        $register_user->execute(array($sess_id, $user_phone, $ip, $tm));
                                        // end to insert
                                        echo "<p class=data><center><font style='color: #f2f2f2;'>Successfully,Logged in </font><br><br><a href='logout'> Log OUT </a><br>
                                        <br><a href=index>Click here if your browser is not redirecting automatically or you don't want to wait.</a><br></center>";
                                        echo "<center><font style='color: #f2f2f2;'>Your accessing Tenant from the IP address " . $ip . "</font></center>";
                                        print "<script>";
                                        if($_SESSION['role']==1)
            							{
            						        print "self.location='superadmin/index';"; // Comment this line if you don't want to redirect
            							}
            							
            							else if($_SESSION['role']==2 && $_SESSION['side_value']==1)
            							{
            							    print "self.location='landlord/index';";
            							}
            								else if($_SESSION['role']==2 && $_SESSION['side_value']==2)
            							{
            							    print "self.location='expenses/index';";
            							}
            							else if($_SESSION['role']==3)
            							{
            							    print "self.location='control/index';";
            							}
            							
            							else if($_SESSION['role']==4)
            							{
            							    print "self.location='receiption/index';";
            							}
            							
            								else if($_SESSION['role']==5)
            							{
            							    print "self.location='finance/index';";
            							}
            							
                                        else
                                        print "self.location='index';";
                                        print "</script>";
                                    }
                                    catch (PDOException $ex) {
                                        //Something went wrong rollback!			
                                        echo $ex->getMessage();
                                    }
                                }
                                }
                            else
                                {
                              $msg="Logging credentials are incorrect!";
                                header('Location:sign-in?msg='.$msg);
                                //die();
                                }
                        }
                        catch (PDOException $ex) {
                            //Something went wrong rollback!
                            echo $ex->getMessage();
                        }
            			
            		}
            	}	
            		
            ob_end_flush();
            ?>
   
<?php include 'ft.php';?>