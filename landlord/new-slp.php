<style>
    .modal { display:none; }
    .modal-title {color: #111;}
    td {
        font-size: 12px;
    }
    .title{
        margin:10px;
        font-size:15px;
    }
    .form-group{
        display: inline-flex;
    }
</style>
<?php // Add tenant payment
 include 'rnd1.php';
 if(ISSET($_POST['add_tenant_payment'])){
         try{
             $payment_id = $_fetch['pay_id'];
             $ten_ref = $_POST['tenant_ref'];
             $received=$_POST['sms_receive'];
             
            $li_quIPInvoiceCC = $db->prepare("SELECT  * FROM   tbl_tenants where ten_ref_no ='".$ten_ref."' ");
            $li_quIPInvoiceCC->execute(); 
            $InvoiceCC=$li_quIPInvoiceCC->fetch();
            // $reff=$Invoice['inv_ref_no'];
             
             	$currency_id=$InvoiceCC['currency_id'];
             		  $li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
            $li_quIPaidNMoney->execute(); 
            $newDMoney=$li_quIPaidNMoney->fetch();
            $converted=$newDMoney['intoR'];
            
           if($currency_id==2){
                    $mbz="usd";
                    
                }
                else{
                    $mbz="Rwf";
                }  
             
             
             $bank = $_POST['bank'];
             $slip_no = $_POST['slip_no'];
             $payment_fees = $_POST['payment_fees']*$converted;
             $payment_fees2 = $_POST['payment_fees'];
             $payment_desc = $_POST['comment'];   
             $date = $_POST['date'];
             $todaysdate = date("Y-m-d H:i:s");
             $feesAMT=$payment_fees;
             $chicFees= $_POST['payment_fees'];
            // generate payment
            
             $li_quIduplicate = $db->prepare("SELECT *  FROM  tbl_payment where slip_no ='".$slip_no."' and  pay_user='".$company."' and bank_id='".$bank."'");  
            $li_quIduplicate->execute(); 
            $duplicate=$li_quIduplicate->rowCount();
            if($duplicate>=0){
                
              $li_quIPaidNTT = $db->prepare("SELECT *  FROM  tbl_tenants where ten_ref_no ='".$ten_ref."' ");
            $li_quIPaidNTT->execute(); 
            $newDTT=$li_quIPaidNTT->fetch();
            $TEN_id=$newDTT['ten_id'];
            
            if($company==9 || $company==10) 
            {
             	   
            // $li_quI = $db->prepare("SELECT * FROM  tbl_invoice where inv_ref_no='".$ten_ref."' and DATE_FORMAT(invoice_date, '%Y-%m') IN (select DATE_FORMAT(rent_date, '%Y-%m') from  tbl_payment  group by rent_date )");
            // $li_quI->execute(); 
            $li_quI = $db->prepare("SELECT * FROM  tbl_invoice where inv_ref_no='".$ten_ref."' group by invoice_date order by invoice_date asc");
            $li_quI->execute();
            $rowI=$li_quI->rowCount();
            //$msg="Number Of invoice"."#".$rowI;
            
            if($rowI>0)
            {
              $set_break=0; 
              $looped=0;
                while($paid=$li_quI->fetch()){
                $looped++;
                $roomid=$paid['inv_room_id']; 
                
             $invoAmount=$paid['invoice_fees']; 
             $inv_date=$paid['invoice_date'];
             $date_check=substr($inv_date,0,7);
            $date33=date("Y-m-d");
            $rend=0;
            $li_quIPaid = $db->prepare("SELECT SUM(pay_amount) as p_AMT  FROM  tbl_payment where DATE_FORMAT(rent_date, '%Y-%m')='".$date_check."' ");
            $li_quIPaid->execute(); 
            $DATA1=$li_quIPaid->rowCount();
            $payF=0;
            $looped1=0;
            while($amaunt2=$li_quIPaid->fetch())
            {
             $looped1++;   
              //echo "GGGG#".$amaunt2['p_AMT']."<br>";    
             if($invoAmount>$amaunt2['p_AMT']){
                 
                 $remain=$invoAmount-$amaunt2['p_AMT'];    
                 if($payment_fees>$remain)
                 {
                     
                    $payF=$payment_fees-$remain;
                    $rend=$payment_fees-$remain;
                    
             $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by)
             VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$remain.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_date.'","'.$converted.'","'.$_SESSION['USER_ID'].'")';
             $conn->exec($sql);
             $lastId = $conn->lastInsertId(); 
            
            if($rend>0){
         
                
         
    
          
                
                 $payment_fees= $rend;
                 
                  break;
            }
            else if($rend==0){
                
                $payment_fees=$payF;
                //  $set_break=1;
                $msg = "Payment saved";  
                break;
            }
            
            
            
            //echo $remain."#".$payF."<br>";
                
               
                
                 }
        
                 else{
                     
            $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by)
             VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$payment_fees.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_date.'","'.$converted.'","'.$_SESSION['USER_ID'].'")';
             $conn->exec($sql);
              $lastId = $conn->lastInsertId();
                     
                   // echo  $remain."&".$payment_fees."<br>";
                    $set_break=1;
                    $payment_fees=0;
                  break;
                 
                 }
                 
                 
             }
             
             
             else{
              
               
             }
             
              
            }
              if($set_break==1){
                break;  
              }
                }
              if($payment_fees>0){
                 
                $li_quIPaidNoo = $db->prepare("SELECT SUM(amount) as rent_AMT  FROM  tbl_assign where tent_id ='".$TEN_id."' ");
            $li_quIPaidNoo->execute(); 
            $newDoo=$li_quIPaidNoo->fetch();
            $AMTrent=$newDoo['rent_AMT'];
                                       
            $li_quIPaidNCC = $db->prepare("SELECT *  FROM  tbl_payment where pay_ref_no='".$ten_ref."' order by rent_date desc limit 1 ");
            $li_quIPaidNCC->execute(); 
            $newDCC=$li_quIPaidNCC->fetch();
             $dataR=$li_quIPaidNCC->rowCount();
            if($dataR==0){
                
             $currentDate=$date;  
                
            }
            else{
            $currentDate=$newDCC['rent_date'];
            }
             $currentDate=substr($currentDate,0,7);
            $currentDate=$currentDate."-05"; 
          
    
                
                //echo "####".$rend."<br>";
                
                if($AMTrent>=$payment_fees) {
                 
              $daysss1=31;
              $days1=$daysss1."days";
              $inv_next_date = date('Y-m-d', strtotime($currentDate. " + $days1"));
            
              $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by)
             VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$payment_fees.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_next_date.'","'.$converted.'","'.$_SESSION['USER_ID'].'")';
             $conn->exec($sql);
             $lastId = $conn->lastInsertId();
                }
                
                else  {
                    $xx=1;
                    $xx1=1;
                    $everyAMT=$payment_fees/$AMTrent;
                    $newRem=$payment_fees-$AMTrent;  
                   
                         $everyAMT1= intval($everyAMT);
                         $intL=$everyAMT-$everyAMT1;
                       
                      $contsAMT=$payment_fees*$intL; 
                      
                      $unAMT=$AMTrent*$intL;
                        // echo "##".$everyAMT1."&&".$everyAMT."@@".$intL;
                     while($xx<=$everyAMT1){
                         
                         
                                     
            $li_quIPaidNCC = $db->prepare("SELECT *  FROM  tbl_payment where pay_ref_no='".$ten_ref."' order by rent_date desc limit 1 ");
            $li_quIPaidNCC->execute(); 
            $newDCC=$li_quIPaidNCC->fetch();
            $dataR=$li_quIPaidNCC->rowCount();
            if($dataR==0){
                
             $currentDate=$date;  
                
            }
            else{
            $currentDate=$newDCC['rent_date'];
            }
            
                         
                         
            $newRem=$payment_fees-$AMTrent;
                       
                        $daysss1=31;
              $days1=$daysss1."days";
              $inv_next_date = date('Y-m-d', strtotime($currentDate. " + $days1"));
            
              $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by)
             VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$AMTrent.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_next_date.'","'.$converted.'","'.$_SESSION['USER_ID'].'")';
             $conn->exec($sql);
             $lastId = $conn->lastInsertId();
                       
                    $payment_fees=$newRem;  
                    $xx++;
                    }  
              $li_quIPaidNCC = $db->prepare("SELECT *  FROM  tbl_payment where pay_ref_no='".$ten_ref."' order by rent_date desc limit 1 ");
            $li_quIPaidNCC->execute(); 
            $newDCC=$li_quIPaidNCC->fetch();
            $currentDate=$newDCC['rent_date'];   
                    
                    
              $daysss1=31; 
              $days1=$daysss1."days";
              $inv_next_date = date('Y-m-d', strtotime($currentDate. " + $days1"));
            
              $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by)
             VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$unAMT.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_next_date.'","'.$converted.'","'.$_SESSION['USER_ID'].'")';
             $conn->exec($sql);
             $lastId = $conn->lastInsertId();
                        
                    
            //         else{
            //           $everyAMT1= intval($everyAMT);
                       
            //           $contsAMT=$AMTrent*$everyAMT;
            //           $unAMT=$payment_fees-$contsAMT;
                       
            //                 while($xx1<=$everyAMT1){
            // $li_quIPaidNCC = $db->prepare("SELECT *  FROM  tbl_payment where pay_ref_no='".$ten_ref."' order by rent_date desc limit 1 ");
            // $li_quIPaidNCC->execute(); 
            // $newDCC=$li_quIPaidNCC->fetch();
            // $dataR=$li_quIPaidNCC->rowCount();
            // if($dataR==0){
                
            //  $currentDate=$date;  
                
            // }
            // else{
            // $currentDate=$newDCC['rent_date'];
            // }   
                                
            //          // $newRem=$payment_fees-$AMTrent;
                       
            //             $daysss1=30;
            //   $days1=$daysss1."days";
            //   $inv_next_date = date('Y-m-d', strtotime($currentDate. " + $days1"));
            
            //   $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date)
            //  VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$AMTrent.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_next_date.'")';
            //  $conn->exec($sql);
            //  $lastId = $conn->lastInsertId();
                       
            //         $payment_fees=$newRem;  
            //         $xx1++;
            //         }
            //         $payment_fees=$unAMT;
            //      //   if($payment_fees>0){
            // $li_quIPaidNCC = $db->prepare("SELECT *  FROM  tbl_payment where pay_ref_no='".$ten_ref."' order by rent_date desc limit 1 ");
            // $li_quIPaidNCC->execute(); 
            // $newDCC=$li_quIPaidNCC->fetch();
            // $currentDate=$newDCC['rent_date'];   
                    
                    
            //   $daysss1=31; 
            //   $days1=$daysss1."days";
            //   $inv_next_date = date('Y-m-d', strtotime($currentDate. " + $days1"));
            
            //   $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date)
            //  VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$unAMT.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_next_date.'")';
            //  $conn->exec($sql);
            //  $lastId = $conn->lastInsertId();
                       
            //         $payment_fees=$newRem;    
            //         //}
                        
            //         }
                   
                    
                    
                    
                    
                    
                }   
            
            // echo "#####".$payment_fees;    
                   
              } 
                
                
                
            
            //$msg = "Payment Successfuly saved";
            }
            else
            {
                
                     
            $li_quIPaidNoo = $db->prepare("SELECT SUM(amount) as rent_AMT  FROM  tbl_assign where tent_id ='".$TEN_id."' ");
            $li_quIPaidNoo->execute(); 
            $newDoo=$li_quIPaidNoo->fetch();
            $AMTrent=$newDoo['rent_AMT'];
                                       
             
    
                
                //echo "####".$rend."<br>";
                
                if($AMTrent>=$payment_fees){ 
                    
                    
            $li_quIPaidNCC = $db->prepare("SELECT *  FROM  tbl_payment where pay_ref_no='".$ten_ref."' order by rent_date desc limit 1 ");
            $li_quIPaidNCC->execute(); 
            $newDCC=$li_quIPaidNCC->fetch();
            $dataR=$li_quIPaidNCC->rowCount();
            if($dataR==0){
             
              $currentDate=$date;     
                
            }
            else{
            $currentDate=$newDCC['rent_date'];
            }
             $currentDate=substr($currentDate,0,7);
            $currentDate=$currentDate."-05"; 
            
              $daysss1=31;
              $days1=$daysss1."days";
              $inv_next_date = date('Y-m-d', strtotime($currentDate. " + $days1")); 
            
              $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by)
             VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$payment_fees.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_next_date.'","'.$converted.'","'.$_SESSION['USER_ID'].'")';
             $conn->exec($sql);
             $lastId = $conn->lastInsertId();
                }
                
                else{
                      $xx=1;
                    $xx1=1;
                    $everyAMT=$payment_fees/$AMTrent;
                    $newRem=$payment_fees-$AMTrent;  
                   
                         $everyAMT1= intval($everyAMT);
                         $intL=$everyAMT-$everyAMT1;
                       
                      $contsAMT=$payment_fees*$intL;
                      
                      $unAMT=$AMTrent*$intL;
                        // echo "##".$everyAMT1."&&".$everyAMT."@@".$intL;
                     while($xx<=$everyAMT1){
                         
                         
                                     
            $li_quIPaidNCC = $db->prepare("SELECT *  FROM  tbl_payment where pay_ref_no='".$ten_ref."' order by rent_date desc limit 1 ");
            $li_quIPaidNCC->execute(); 
            $newDCC=$li_quIPaidNCC->fetch();
            $dataR=$li_quIPaidNCC->rowCount();
            if($dataR==0){
                
             $currentDate=$date;  
                
            }
            else{
                
            $currentDate=$newDCC['rent_date']; 
            
            }
            
                         
                         
            $newRem=$payment_fees-$AMTrent;
                       if($dataR>0)
                       {
                         $daysss1=31;  
                        }
                       else
                       {
                         $daysss1=0;  
                           
                       }
                        
              $days1=$daysss1."days";
              $inv_next_date = date('Y-m-d', strtotime($currentDate. " + $days1"));
            
              $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by)
             VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$AMTrent.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_next_date.'","'.$converted.'","'.$_SESSION['USER_ID'].'")';
             $conn->exec($sql);
             $lastId = $conn->lastInsertId();
                       
                    $payment_fees=$newRem;  
                    $xx++;
                    }  
              $li_quIPaidNCC = $db->prepare("SELECT *  FROM  tbl_payment where pay_ref_no='".$ten_ref."' order by rent_date desc limit 1 ");
            $li_quIPaidNCC->execute(); 
            $newDCC=$li_quIPaidNCC->fetch();
            $currentDate=$newDCC['rent_date'];   
                    
                    
              $daysss1=31; 
              $days1=$daysss1."days";
              $inv_next_date = date('Y-m-d', strtotime($currentDate. " + $days1"));
            
              $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,rent_date,c_value,rec_by)
             VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$unAMT.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$inv_next_date.'","'.$converted.'","'.$_SESSION['USER_ID'].'")';
             $conn->exec($sql);
             $lastId = $conn->lastInsertId();
                    
                    
                    
                    
                    
                }   
                
              // $msg = "Payment Successfuly saved";   
                
            }
            
                
            }
            
            else{
            $year =  date('Y');
            $year = substr( $year, -2);
            $li_qu = $db->prepare("SELECT * FROM tbl_payment ORDER BY pay_id DESC LIMIT 1");
            $li_qu->execute();
            $fli = $li_qu->fetch();
            $li = str_pad($fli['pay_id'], 6, "0", STR_PAD_LEFT);
            $new_pay_id = $year.'-'.$li;
 
             $sql = 'INSERT INTO tbl_payment (trans_code,pay_ref_no,bank_id,slip_no,pay_due_date,pay_amount,pay_description,pay_trm,pay_user,c_value,rent_date,rec_by)
             VALUES ("'.$new_pay_id.'", "'.$ten_ref.'","'.$bank.'","'.$slip_no.'","'.$date.'","'.$payment_fees.'","'.$payment_desc.'","'.$todaysdate.'","'.$company.'","'.$converted.'","'.$date.'","'.$_SESSION['USER_ID'].'")';
             $conn->exec($sql);
             $lastId = $conn->lastInsertId();
             
            }
             
        $li_qu_admin = $db->prepare("SELECT * FROM tbl_admins where company_id='".$company."' ");
        $li_qu_admin->execute();
        $fli_admin = $li_qu_admin->fetch();
        $company_number=$fli_admin['phone'];
        
         $li_qu_adminTT = $db->prepare("SELECT * FROM tbl_tenants where ten_ref_no='".$ten_ref."' ");
        $li_qu_adminTT->execute();
        $fli_adminTT = $li_qu_adminTT->fetch();
        $phone12=$fli_adminTT['ten_phone'];
        $names=$fli_adminTT['te_names'];
         $li_qu_adminMM = $db->prepare("SELECT * FROM tbl_company where cpny_ID='".$company."' ");
        $li_qu_adminMM->execute();
        $fli_adminMM = $li_qu_adminMM->fetch();
        $cp_name_char=$fli_adminMM['msg_char'];
        $cp_name_char2=$fli_adminMM['short_name'];
        $short_name=$fli_adminMM['side'];
        // $id="https://xode.rw/xode-receipt?code=$rnd".$ten_ref;
        
             if($company==6 || $company==7 || $company==8){
            $msg="Mukiriya wacu Mwiza $names  $cp_name_char2 irabamenyesha ko amafaranga angana $payment_fees2 Frw yakiriwe kuri Konti yayo. Tel: $company_number";
            
           
             $data=array(
                      "sender"=>"$cp_name_char",
                        "recipients"=>"$phone12",
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
            
            $msg = "Payment Successfully Added!";
                 
             } 
             else if($company==9 || $company==10){ 
                 
            
             $msg="Mukiriya wacu Mwiza $names $cp_name_char2 irabamenyesha ko amafaranga angana $payment_fees2 $mbz yakiriwe kuri Konti yayo . Murakoze Tel: $company_number";
              
              if($received==1){
              $data=array(
                      "sender"=>"$cp_name_char",
                        "recipients"=>"$phone12",
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
             $msg = "Payment Successfully Added!";
                 
             }
            
            else{
                if($company==13){
                    
            $msg="Mukiriya wacu Mwiza $names $cp_name_char2 irabamenyesha ko amafaranga Yubukode yakiriwe. Murakoze Tel: $company_number";
               }
                else {
              $msg="Mukiriya wacu Mwiza $names $cp_name_char2 irabamenyesha ko amafaranga angana $payment_fees2 $mbz yakiriwe kuri Konti yayo . Murakoze Tel: $company_number";
                }
              if($received==1){
              $data=array(
                      "sender"=>"$cp_name_char",
                        "recipients"=>"$phone12",
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
             $msg = "Payment Successfully Added!";
            }
            
            }
            
            else {
                 $msge="payment Already recorded,"." Please Check slip number:".$slip_no;
            }
                
            
             
            //  echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=new-slp">';
             
         }catch(PDOException $e){
             $msg = $e->getMessage();
         }
         
     }
  
 ?>   
<div class="span9">
  <div class="content">
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
    	<div class="alert alert-danger" role="alert">
        <span class="fe fe-minus-circle fe-16 mr-2"></span> <?php echo $msge; ?> </div>
        <?php
           }
        ?>
 <div class="module">
 <ul class="nav nav-tabs">
    <li class="active"><a href="#" data-toggle="tab">New Payment</a></li>
</ul>
<br>
</div><!--/.module-->
</div><!--/.content-->
</div><!--/.span9-->
<div class="row">
<div class="col-md-12"><br><br>
<div class="col-md-12"><br><br>

<form class="form-horizontal row-fluid"  role="form" id="add_form" action="" method="POST" name="frmPayRecord" onSubmit="return validateform()">
  <div class="card shadow mb-4">
    <div class="card-body">
        <br><br>
      <div class="form-row">
          
        <div class="form-group col-md-2">
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationCustom3">Tenant name</label>
           <select id="tenant_ref" name="tenant_ref" class="form-control select2"  required>
                           <option></option>
                 <?php
                     $sql = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_staff = $company and ten_id not in (select tenant_id from tbl_tenant_leave group by tenant_id)");
                     $sql->execute();
                     while($fetch = $sql->fetch()){
                ?>
                 <option value="<?php echo $fetch['ten_ref_no']?>"><?php echo $fetch['te_names']?></option>
                 
                 <?php
                     }
                 ?>      
                 </select>
        </div> <!-- form-group -->
        <div class="col-md-3 mb-3">
          <label for="multi-select2">Account name</label>
          <select id="bank" name="bank" class="form-control select2" required>
               <option></option>
             <?php
                 $sql = $conn->prepare("SELECT * FROM `tbl_bank`");
                     $sql->execute();
                     while($fetch = $sql->fetch()){
                 ?>
         
                     <option value="<?php echo $fetch['bank_id']?>"><?php echo $fetch['bank_name']?></option>
                 
                 <?php
                     }
                 ?>      
                 </select>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div class="form-row">
        <div class="form-group col-md-2">
        </div>
        <div class="col-md-3 mb-3">
          <label for="simple-select2">Slip No</label>
           <input type="text" name="slip_no" id="slip_no" class="form-control" placeholder="Slip Number" />
        </div> <!-- form-group -->
        <div class="col-md-3 mb-3">
          <label for="multi-select2">Amount</label>
          <input type="number" name="payment_fees" id="amount" class="form-control" placeholder="Amount"/>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div class="form-row">
        <div class="form-group col-md-2">
        </div>
        <div class="col-md-3 mb-3">
          <label for="simple-select2">Date</label>
          <input type="date" class="form-control" name="date" id="date" max="<?php echo date('Y-m-d')?>" required />
        </div> <!-- form-group -->
        <div class="col-md-3 mb-3">
          <label for="multi-select2">Comments</label>
          <textarea rows="3" name="comment" id="comment" placeholder="Enter Comment" class="form-control" ></textarea>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div class="form-row">
        <div class="form-group col-md-2">
        </div>
            <div class="col-md-3 mb-3">
          <label for="multi-select2">Receive SMS</label>
          <select id="sms_receive" name="sms_receive" class="form-control select2" required>
               <option value="1"> Yes</option>
               <option value="0"> No</option>
                   
                 </select>
        </div>
        <div class="col-md-2 mb-2"></div>
        <div class="col-md-2 mb-2">
             <br>
          <button type="submit" name="add_tenant_payment" class="btn btn-primary btn-sm">Save</button>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      </form>
  <div name='display_specs' id='display_specs'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>

        </div>
</div> <!-- end section -->


