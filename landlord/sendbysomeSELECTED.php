<?php 
 require_once ("../include/initialize.php");
$dataFrom=$_REQUEST['slectedStaff'];
$smscontent=$_POST['smscontentSelected'];

if(!empty($smscontent)){
if(empty($dataFrom)){
echo 2;
}
else {
    $number=0;
     $tot_inv_yes=0;
                $tot_pay=0;
                $tot_diff=0;
                $payment=0;
                $totinv2=0;
                
        $li_qu_admin = $db->prepare("SELECT * FROM tbl_admins where company_id='".$company."' ");
        $li_qu_admin->execute();
        $fli_admin = $li_qu_admin->fetch();
        $company_number=$fli_admin['phone'];
        
        $li_qu_adminMM = $db->prepare("SELECT * FROM tbl_company where cpny_ID='".$company."' ");
        $li_qu_adminMM->execute();
        $fli_adminMM = $li_qu_adminMM->fetch();
        $cp_name_char=$fli_adminMM['msg_char'];
        $short_name=$fli_adminMM['side'];
        $acc_number=$fli_adminMM['acc'];
        
   foreach ($dataFrom as $value){
       
 $sql = $db->prepare("SELECT * FROM tbl_tenants  WHERE ten_ref_no='".$value."' limit 1 ");
    $sql->execute();
     $aa=0;
     while($fetch = $sql->fetch()) {     
                     
                   $number++;
                   
                   $ten_id = $fetch['ten_id'];
                    $ten_ref = $fetch['ten_ref_no']; 
                    $t_names=$fetch['te_names']; 
                    $t_phones=$fetch['ten_phone']; 
                      ##invo
                    
                    
                $msg=$smscontent." (Powered by ITEC Ltd) ";  
                $phone222=$t_phones;  
                //  $phone222="0788644687";
                 $data=array(
                 "sender"=>"$cp_name_char",
                 "recipients"=>"$phone222",
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
                                     
                                 
                                 
                                  
                                 
                     
                 }
   }

echo 1; 
}
}
else{
    
    echo 5;
}

?>