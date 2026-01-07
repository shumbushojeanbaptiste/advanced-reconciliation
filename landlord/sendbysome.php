<?php 
 require_once ("../include/initialize.php");
$dataFrom=$_REQUEST['slectedStaff'];
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
         $cp_name_char2=$fli_adminMM['short_name'];
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
                      $sql2 = $conn->prepare("SELECT SUM(invoice_fees) AS totalinv FROM `tbl_invoice` WHERE inv_ref_no = '".$ten_ref."'  ");
                        $sql2->execute(array()); // execute the query with parameter $code...
                                $rowcount = $sql2->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $row1 = $sql2->fetch(PDO::FETCH_ASSOC);
                                $tot_inv_yes = $tot_inv_yes + $row1['totalinv'];
                                $totinv2 = $row1['totalinv'];
                               
                                 }
                                 else{
                                   
                                 }
                                 
                                ##Payment 
                        $sql4 = $conn->prepare("SELECT SUM(pay_amount) AS totalpay FROM `tbl_payment` WHERE pay_ref_no = '".$ten_ref."'  "); 
                        $sql4->execute(array()); // execute the query with parameter $code...
                                $rowcount = $sql4->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $row3 = $sql4->fetch(PDO::FETCH_ASSOC);
                                $payment = $row3['totalpay'];
                                $tot_pay = $tot_pay + $row3['totalpay'];
                                
                               
                                 }
                                 else{
                                    
                                 }
                                 
                                 
                                 $tot_diff = $totinv2 - $payment;
                              
                                 if($tot_diff>0){
                                $tot_diff2=number_format($tot_diff);
                                $msg="Mukiriya wacu $t_names ,$cp_name_char2 irakwibutsa kwishyura amafaranga yose angana $tot_diff2 Frw  kuri  Account number: $acc_number,mukoresheje code yanyu:$ten_ref . Murakoze Tel: $company_number . (Powered by ITEC Ltd) ";  
                                $phone222=$t_phones;     
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
   }

echo 1; 
}


?>