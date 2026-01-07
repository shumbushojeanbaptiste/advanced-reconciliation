<?php
    require_once ("../include/initialize.php");
    try{
        $sms = $_REQUEST['sms'];
        $tenant= $_REQUEST['tenant'];
        $sender= $_REQUEST['sender'];
		$today = date('Y-m-d');
		$data=array(
                      "sender"=>"$sender",
                        "recipients"=>"$tenant",
                        "message"=>"$sms",
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
            
		?>
		<br />
			<center>
			<div class="alert alert-success" role="alert" style="width:50%;">
			<strong><i class="fa fa-fw fa-check-square"></i> 
			Well done !!!</strong> Message Sent <?php echo $tenant; ?>
			</div>
			</center><br />
			<?php
		
		    ?>
			<?php
		
        
		}catch(PDOException $e){
			echo $e->getMessage();
		}
    ?>
           
			
		