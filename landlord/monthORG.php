<?php 
include 'rnd1.php';
 if(isset($_POST['btn_save'])){
     $month = $_POST['month'];
     $SETYEAR =  date('Y');
     $chk_dt = $SETYEAR."-".$month;
     
    // $te_invoice = $db->prepare("SELECT * FROM tbl_invoice WHERE auto = 'yes' AND DATE_FORMAT(invoice_date, '%Y-%m') = '".$chk_dt."' AND cpny_ID = '".$cpny_ID."' ORDER BY invoice_id DESC LIMIT 1");
    // $te_invoice->execute();
    // if($te_invoice->rowCount() == 0){
     
  //Check Company
  $stmt = $db->prepare("SELECT * FROM tbl_admins INNER JOIN tbl_users_login  ON tbl_admins.adm_id=tbl_users_login.profile_id  WHERE  tbl_users_login.role_id = '2' AND tbl_users_login.profile_id = ?");
  $stmt->execute(array($profile));
  $rows = $stmt->fetch();
   
    $comp = $rows['company_id'];
    $stmt = $db->query('SELECT *FROM tbl_company WHERE cpny_ID="'.$comp.'" ORDER BY cpny_ID ASC');
    try
    {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cmp_ID =$row['cpny_ID'];
            $short =$row['short_name'];
            
             }
        }
        catch (PDOException $ex) {
        //Something went wrong rollback!
        echo $ex->getMessage();
     }
    
    // fetch 
    $sql = $db->prepare("SELECT * FROM tbl_assign 
    INNER JOIN tbl_rooms ON tbl_assign.room_id=tbl_rooms.room_id    
    INNER JOIN tbl_tenants ON tbl_assign.tent_id=tbl_tenants.ten_id
    WHERE tbl_assign.assgn_status = 1 AND tbl_rooms.company_id='".$cmp_ID."' AND tbl_tenants.rent_status=0    group by   tbl_tenants.ten_ref_no order by tbl_tenants.ten_ref_no asc"); 
    // and ten_ref_no=228247906 
    
    // and ten_ref_no=229768201 224165872  
    //  and ten_ref_no=227836914 
    // and tbl_tenants.ten_ref_no=229836405 
    // and ten_ref_no=224230658   229643180 223078124 223078124
    //   0783562100
    //0788307456
    
    $ACCNO="0130105783036";
    $sql->execute();
    $data_y= $sql->rowCount();
    //  WHERE tbl_assign.assgn_status = 1 AND tbl_rooms.company_id='".$cmp_ID."' and tbl_tenants.ten_ref_no not in (select tbl_tenant_leave.tenant_id from tbl_tenant_leave group by tenant_id) order by ten_ref_no asc   ");
    $dd=0;
     while($fetch = $sql->fetch()) {
        //  $dd++;
        //  echo "##".$dd;
        $currency_id=$fetch['currency_id']; 
         $TEN_id= $fetch['ten_id'];
         $tenant_phone = $fetch['ten_phone'];
         $names=$fetch['te_names'];
         $room_no = $fetch['room_no'];
         
    $te_invoiceKK = $db->prepare("SELECT SUM(amount) AS amount1, SUM(discount) AS discount1 FROM tbl_assign WHERE tent_id= '".$TEN_id."'  AND company_id = '".$cpny_ID."' ");
    $te_invoiceKK->execute();
  // $data_tKK=$te_invoiceKK->rowCount();
    $data_tKK=$te_invoiceKK->fetch();
    
    
         
         $amount = $fetch['amount'];
         $discount = $fetch['discount'];
         
         
          $amount3 = $data_tKK['amount1'];
         $discount3 = $data_tKK['discount1'];
         
          if($currency_id==2){
                    $mbz="usd";
                    
                }
                else{
                    $mbz="Rwf";
                } 
        $li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
        $li_quIPaidNMoney->execute(); 
        $newDMoney=$li_quIPaidNMoney->fetch();
        $rent_fees = $amount-$discount;
        $unitValue=$newDMoney['intoR'];
        $rent_fees12=($amount3-$discount3)/$newDMoney['intoR'];

        //date
        $date = date('Y-m-d');
        $date_time = date('Y-m-d H:i:s');
        $yes = "yes";
        $sub_mon = substr($month, -1);
        
        //Invoice No.
        $year1 =  date('Y');
        $year = substr( $year1, -2);
        $li_qu = $db->prepare("SELECT * FROM tbl_invoice ORDER BY invoice_id DESC LIMIT 1");
        $li_qu->execute();
        $fli = $li_qu->fetch();
        $li = str_pad($fli['invoice_id'], 6, "0", STR_PAD_LEFT);
        $invoice = $year.'-'.$li;
        #########################################
        
        //Check dateTime
        $due_day =  date("d", strtotime($fetch['rent_date']));
        $paid_dt = $year1."-".$month."-".$due_day;
        $paid_dt2 = $year1."-".$month."-01";
        #########################################
       
        try
          {
              
        $li_qu_admin = $db->prepare("SELECT * FROM tbl_admins where company_id='".$cmp_ID."' ");
        $li_qu_admin->execute();
        $fli_admin = $li_qu_admin->fetch();
        $company_number=$fli_admin['phone'];
        
        $li_qu_adminMM = $db->prepare("SELECT * FROM tbl_company where cpny_ID='".$cmp_ID."' ");
        $li_qu_adminMM->execute();
        $fli_adminMM = $li_qu_adminMM->fetch();
        $cp_name_char=$fli_adminMM['msg_char'];
        $short_name=$fli_adminMM['side'];
        
        $te_invoice = $db->prepare("SELECT * FROM tbl_invoice WHERE inv_ref_no= '".$fetch['ten_ref_no']."' and auto='yes' AND DATE_FORMAT(invoice_date, '%Y-%m') = '".$chk_dt."' AND cpny_ID = '".$cpny_ID."' ");
        $te_invoice->execute();
        $data_t=$te_invoice->rowCount();
        if($te_invoice->rowCount() == 0){
       
        
        if($cmp_ID==9 || $cmp_ID==10 || $cmp_ID==14){
            
             $contract = $db->prepare("SELECT * FROM tbl_ten_contracts WHERE tenant_id= '".$fetch['ten_id']."' and cpn_id = '".$cpny_ID."' and cntr_status=1 ");
             $contract->execute();
             $contract_month = $contract->fetch();
             $contract_count= $contract->rowCount();
             
             if($contract_count==0){
           
            
        $stms177 = $db->prepare("SELECT sum(pay_amount) as sm from tbl_payment where pay_ref_no='".$fetch['ten_ref_no']."' 
    			");
    		$stms177->execute();
            $rows177 = $stms177->fetch();
            $code32=$fetch['ten_ref_no'];
            
              $stms177invo = $db->prepare("SELECT sum(invoice_fees) as Sinvo from tbl_invoice where inv_ref_no='".$fetch['ten_ref_no']."' 	");
    		$stms177invo->execute();
            $rows177invo = $stms177invo->fetch();
            
            $umwenda=$rows177invo['Sinvo']-$rows177['sm'];
        
            
            
    $te_invoiceKKroom = $db->prepare("SELECT * FROM tbl_assign WHERE tent_id= '".$fetch['ten_id']."'  AND company_id = '".$cpny_ID."' and assgn_status=1 ");
    $te_invoiceKKroom->execute();
  // $data_tKK=$te_invoiceKK->rowCount();
  $smsAMT=0;
    while($data_tKKroom=$te_invoiceKKroom->fetch()){
        $nowdte2=$chk_dt."-1";  
        $amt34=$data_tKKroom['amount']-$data_tKKroom['discount'];
        $smsAMT=$smsAMT+$amt34;
        $inv_insert_qu = $db->prepare("INSERT into tbl_invoice(`invoice`, `invoice_fees`, `inv_desc`, `invoice_date`, `inv_ref_no`, `cpny_ID`, `auto`,`inv_date`,`inv_message`,`unit_value`,`inv_room_id`,`done_by`)
        VALUES ('".$invoice."', '".$amt34."', 'rent', '$nowdte2', '".$fetch['ten_ref_no']."', '$cpny_ID', 'yes', '$date_time','1','".$unitValue."','".$data_tKKroom['room_id']."','".$_SESSION['USER_ID']."')");
        $inv_insert_qu->execute(); 
    }
    
        if($rows177['sm'] < $smsAMT){
                if($currency_id==2){
                    $mbz="usd";
                    
                }
                
                else{
                    $mbz="Rwf";
                }
    
          // 
          //$msg="Mukiriya wacu,$cp_name_char irakwibutsa kwishyura ubukode  bungana $smsAMT $mbz, Code yo kwishyuriraho muri COGEBANK:$code32 . Murakoze Tel: 0788620612";   
            $mint=number_format($smsAMT); 
             $phone222=$fetch['ten_phone'];
            if($umwenda>0){
                $umwendaa=number_format($umwenda);
                $total989=$umwenda+$smsAMT;
                $total989=number_format($total989);
            
                $ACCNO="0130105783036";
                if($cmp_ID==9){
                    $msg="Mukiriya wacu,$cp_name_char irakwibutsa kwishyura  ubukode  bungana $mint $mbz nibirarane $umwendaa Total=$total989, Code yo kwishyuriraho muri COGEBANK:$code32 .
                    Acc No  $ACCNO Tel: $company_number , reba fagitire hano:";
                }
              
                else{
                    $msg="Mukiriya wacu,$cp_name_char irakwibutsa kwishyura  ubukode  bungana $mint $mbz nibirarane $umwendaa Total=$total989 . Murakoze Tel: $company_number "; 
                } 
            }
            
            else{
                if($cmp_ID==9){
                    $msg="Mukiriya wacu,$cp_name_char irakwibutsa kwishyura  ubukode bungana $mint $mbz, Code yo kwishyuriraho muri COGEBANK:$code32 Acc No  $ACCNO Tel: $company_number, reba fagitire hano:";  
                }
                  
                else{
                    $msg="Mukiriya wacu,$cp_name_char irakwibutsa kwishyura ubukode  bungana $mint $mbz. Murakoze Tel: $company_number  ";   
                }
            }
            
            // $inv_insert_qu = $db->prepare("INSERT into tbl_invoice(`invoice`, `invoice_fees`, `inv_desc`, `invoice_date`, `inv_ref_no`, `cpny_ID`, `auto`,`inv_date`,`inv_message`,`unit_value`)
            // VALUES ('".$invoice."', '".$rent_fees."', 'rent', '$paid_dt', '".$fetch['ten_ref_no']."', '$cmp_ID', 'yes', '$date_time','1','".$unitValue."')");
            // $inv_insert_qu->execute(); 
            // $lastId = $db->lastInsertId();
            
            
             $phone222=$fetch['ten_phone'];
             //$phone222="0788644687";
             
            $email222a=$fetch['ten_email'];
            $xxode = substr($chk_dt,0,7); 
            $xxode321=$chk_dt."-1"; 
            
            if($cmp_ID==10){
                
                 $id="https://xode.rw/KIC-invoice?code=$rnd".$fetch['ten_ref_no']."&xode=".$chk_dt;
            }
            else{
            $id="https://xode.rw/xode-invoice?code=$rnd".$fetch['ten_ref_no']."&xode=".$chk_dt;
            
            }
            //include 'save_inv_history.php';
             $msg=$msg.":".$id;
            $msg=$msg."/"."(Powered by ITEC Ltd)";
            
             $inv_insert_qu20= $db->prepare("INSERT into tbl_invo_link(`li_date`, `link_text`, `ten_ref`, `receiced_phone`, `invo_type`,`co_id`,`sms`)
            VALUES ('".date("Y-m-d")."', '".$id."', '".$fetch['ten_ref_no']."', '$phone222', '1','".$cmp_ID."','$msg')");
            $inv_insert_qu20->execute();
            
           
            
            $data=array(
                      "sender"=>"$cp_name_char",
                        "recipients"=>"$phone222",
                        "message"=>"$msg",
                );
        
            $url="https://www.intouchsms.co.rw/api/sendsms/.json";
            $data=http_build_query($data);
            $username="twagiramungus";
            $password="M00dle!!";
        
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
            echo "#900";
            
            include'invoiceMail2.php';
           
            }
            
             
   
            
        }
        else  {
            $mdayz = $contract_month['m_interval']*28;         
            $start_date = $paid_dt2;
            $days=$mdayz."days";
            $end_date = date('Y-m-d', strtotime($start_date. " + $days"));
            $edte = substr($end_date,0,7);
                $stms177 = $db->prepare("SELECT sum(pay_amount) as sm from tbl_payment where pay_ref_no='".$fetch['ten_ref_no']."' ");
    			$stms177->execute();
            $rows177 = $stms177->fetch();
            
            
            $stms177invo1 = $db->prepare("SELECT sum(invoice_fees) as Sinvo from tbl_invoice where inv_ref_no='".$fetch['ten_ref_no']."'	");
    		$stms177invo1->execute();
            $rows177invo1 = $stms177invo1->fetch();
            
            $umwenda=$rows177invo1['Sinvo']-$rows177['sm'];
            
            
            $code32=$fetch['ten_ref_no'];
            $intv1=$contract_month['m_interval'];
            $intv=$contract_month['m_interval']*$rent_fees12;
         
                
                
                $start    = new DateTime($start_date);
                $start->modify('first day of this month');
                $end      = new DateTime($end_date);
                $end->modify('first day of next month');
                $interval = DateInterval::createFromDateString('1 month');
                $period   = new DatePeriod($start, $interval, $end);
                 $ee=0;
                foreach ($period as $dt) {
                    $nowdte = $dt->format("Y-m");
                    $nowdte2 =$nowdte."-01";
                  // if($cmp_ID==10){
                     
    $te_invoiceKKroom = $db->prepare("SELECT * FROM tbl_assign WHERE tent_id= '".$fetch['ten_id']."'  AND company_id = '".$cmp_ID."' and assgn_status=1 ");
    $te_invoiceKKroom->execute();
  // $data_tKK=$te_invoiceKK->rowCount();
    $smsAMT123=0;
   
    $ee++;
    while($data_tKKroom=$te_invoiceKKroom->fetch())
    {
        
         $amt34=$data_tKKroom['amount']-$data_tKKroom['discount'];
        $smsAMT123=$smsAMT123+$amt34;
        if($ee==1){
          $amt34=$amt34*$intv1;
        }
        else{
          $amt34=0;  
        }
       
        $inv_insert_qu = $db->prepare("INSERT into tbl_invoice(`invoice`, `invoice_fees`, `inv_desc`, `invoice_date`, `inv_ref_no`, `cpny_ID`, `auto`,`inv_date`,`inv_message`,`unit_value`,`inv_room_id`,`done_by`)
            VALUES ('".$invoice."', '".$amt34."', 'rent', '$nowdte2', '".$fetch['ten_ref_no']."', '$cmp_ID', 'yes', '$date_time','1','".$unitValue."','".$data_tKKroom['room_id']."','".$_SESSION['USER_ID']."')");
            $inv_insert_qu->execute();    
        
    }
    
    
  
            $xxodee = $chk_dt;
            
                }
                
             
            $imit1=number_format($intv); 
            if($umwenda>0){
                 $umwendaa=number_format($umwenda);
                 $total765=$umwenda+$intv;
                 $total765=number_format($total765);
                 
                 if($cmp_ID==9){
                  
                
    $msg="Mukiriya wacu,$cp_name_char irakwibutsa kwishyura  ubukode  bungana $imit1 $mbz nibirarane $umwendaa Total=$total765, Code yo kwishyuriraho muri COGEBANK:$code32 Acc No  $ACCNO Tel: $company_number , reba fagitire hano:";  
              }
              
              else{
                  
             $msg="Mukiriya wacu,$cp_name_char irakwibutsa kwishyura ubukode bw'amezi $intv1 bungana $imit1 $mbz nibirarane $umwendaa Total=$total765. Murakoze Tel: $company_number ";  
              }
                 
            }
            else{
                
              if($cmp_ID==9){
                  
                $msg="Mukiriya wacu,$cp_name_char irakwibutsa kwishyura  ubukode  bungana $imit1 $mbz, Code yo kwishyuriraho muri COGEBANK:$code32 Acc No  $ACCNO Tel: $company_number ,reba fagitire hano:";    
              }
              
              else
              {
                  
              $msg="Mukiriya wacu,$cp_name_char irakwibutsa kwishyura ubukode bw'amezi $intv1 bungana $imit1 $mbz . Murakoze Tel: $company_number ";     
              }
                  
              }
            
             $phone222a=$fetch['ten_phone'];
              //$phone222a="0788644687";
             
            $email222a=$fetch['ten_email'];
            
            
            
              if($cmp_ID==10){
            
            $id="https://xode.rw/KIC-invoice?code=$rnd".$fetch['ten_ref_no']."&xode=".$xxodee;
            }
            else{
            
            $id="https://xode.rw/xode-invoice?code=$rnd".$fetch['ten_ref_no']."&xode=".$xxodee;
            
            }
              
           
            // include 'save_inv_history.php';
            
             $msg = $msg.$id;
             $msg = $msg."/"."(Powered by ITEC Ltd)";;
             
             $inv_insert_qu20= $db->prepare("INSERT into tbl_invo_link(`li_date`, `link_text`, `ten_ref`, `receiced_phone`, `invo_type`,`co_id`,`sms`)
            VALUES ('".date("Y-m-d")."', '".$id."', '".$fetch['ten_ref_no']."', '$phone222a', '1','".$cmp_ID."','".$msg."')");
            $inv_insert_qu20->execute();
            
            $data=array(
                      "sender"=>"$cp_name_char",
                        "recipients"=>"$phone222a",
                        "message"=>"$msg",
                );
        
            $url="https://www.intouchsms.co.rw/api/sendsms/.json";
            $data=http_build_query($data);
            $username="twagiramungus";
            $password="M00dle!!";
        
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
                
         
         
        include'invoiceMail2.php';   
        }
            
        }  
        else{
            
            
            $li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
            $li_quIPaidNMoney->execute(); 
            $newDMoney=$li_quIPaidNMoney->fetch();
            
            $li_quIPaidNoo20 = $db->prepare("SELECT SUM(amount) as rent_AMT ,SUM(discount) as disc_count FROM  tbl_assign where tent_id ='".$TEN_id."' ");
            $li_quIPaidNoo20->execute(); 
            $newDoo20=$li_quIPaidNoo20->fetch(); 
            $discount=$newDoo20['disc_count'];
            $AMTrent=$newDoo20['rent_AMT'];
            $rent_fees200=$AMTrent-$discount; 
            $rent_fees200b=$rent_fees200/$newDMoney['intoR'];     
            
            
             
            
            $inv_insert_qu = $db->prepare("INSERT into tbl_invoice(`invoice`, `invoice_fees`, `inv_desc`, `invoice_date`, `inv_ref_no`, `cpny_ID`, `auto`,`inv_date`,`inv_message`,`unit_value`,`done_by`)
            VALUES ('".$invoice."', '".$rent_fees200."', 'rent', '$paid_dt', '".$fetch['ten_ref_no']."', '$cmp_ID', 'yes', '$date_time','1','".$unitValue."','".$_SESSION['USER_ID']."')");
            $inv_insert_qu->execute(); 
            $lastId = $db->lastInsertId();
            $phone222="0788357488";
            
        
            if($cmp_ID==6 || $cmp_ID==7 || $cmp_ID==8){
              
            //$rows177 = $stms177->fetch();
            $msg="Kuri $names,Turakwibutsa kwishyura $rent_fees200b $mbz y'ukwezi kwa $sub_mon kuri $short_name. kubufatanye na Grobal Businnes Exor Ltd,Tel: $company_number (Powered by ITEC Ltd)";
            } 
            
            else{
                
            $msg="Mukiriya wacu,$cp_name_char irakumenyesha ko ubukode bw'ukwezi kwa $sub_mon ari $rent_fees200b $mbz. Murakoze Tel: $company_number (Powered by ITEC Ltd)";   
            }
           
                 
            $data=array(
                      "sender"=>"$cp_name_char",
                        "recipients"=>"$tenant_phone",
                        "message"=>"$msg",
                );
       
            $url="https://www.intouchsms.co.rw/api/sendsms/.json";
            $data=http_build_query($data);
            $username="twagiramungus";
            $password="M00dle!!";
        
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
            
          ?>
      <script>
              jQuery(function validation(){
        swal("Well done! ", "Invoice Sent And Save.", "success", {
        button: "ok",
          });
        });
        </script>
  <?php
        
    }
    
    else {
          ?>
      <script>
              jQuery(function validation(){
        swal("Soory! ", "Invoice Already Sent And saved.", "error", {
        button: "ok",
          });
        });
        </script>
  <?php
    }
         }
        catch (PDOException $ex) {
        //Something went wrong rollback!
        echo $ex->getMessage();
      }
       
     }
     //While End
     
    // }else {
    //     $msge = "Failed! Invoice Already Saved";
    //   echo'<meta http-equiv="refresh"'.'content="10;URL=index?file=monthly_inv">';
    // }
    
    }//Isset
?>
<style>
    .modal { display:none; }
    .modal-title {color: #111;}
    td {
        font-size: 12px;
    }
    .title{
        margin-left:10px;
        font-size:15px;
    }
    .form-group{
        display: inline-flex;
        margin: 10px;
        padding: 10px;
    }
    #mon{
        margin: auto;
        margin-right: inherit;
    }
    #inv-dt{
        margin-top: 50px;
    }
    #inv-dt{
        margin-top: 50px;
    }
</style>
<div class="span9">
  <div class="content">

 <div class="module">
<label class="title">Monthly Invoice</label>
<hr>

        <?php
            if($message1){
        ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Well done!</strong> <?php echo $message; ?> 
        </div>
        <?php
          }
           else if($msge1){
        ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Sorry!</strong>  <?php echo $msge; ?> 
        </div>
        <?php
           }
        ?>
      <div class="row">
<div class="col-md-12"><br><br>
<div class="card shadow mb-4">
    <div class="card-body">
    <form name="invoice" method="POST" action="" onsubmit="return validate(this);">
     <div class="form-group">
      <label id="mon">Select Month</label>
      </div>
      <div class="form-group">
         <select name="month" id="month" class="form-control select2">
				        <?php $i = 1;
    						$month = 12;
    						$date_month = date('m');
    						$date_year = date('Y');
    						//$date = date('Y-m-d');
    						?>
    					   <option></option>
    						<?php
    						
    							while ($i <= $month)
    						{
    						$i = str_pad($i, 2, 0, STR_PAD_LEFT);
    							if($date_month==$i)
    								{
    								?>
    								<option value="<?php echo $date_month;?>" selected><?php echo date('F', mktime(0, 0, 0, $i, 1))?></option>
    								
    								<?php
    								break;
    								}
    								else
    								
    								{
    								?>
    								<option value="<?php echo $i;?>"><?php echo date('F', mktime(0, 0, 0, $i, 1))?></option>
    								
    								<?php
    								
    								}
    							$i++;				
    						}
			    
			    
			
			
			?>
				   </select>
         </div>
         <div class="form-group">
             <button type="submit" name="btn_save" id="sentInvo" class="btn btn-sm btn-primary" >Send</button>
             
              <button class="btn btn-primary" type="button" disabled id="btnslide">
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      <span class="sr-only">Loading...</span>
                    </button>
         </div>
        </form>
        <?php 
        $contract = $db->prepare("SELECT * FROM tbl_ten_contracts WHERE tenant_id= 279 ");
             $contract->execute();
             $contract_month = $contract->fetch();
             
            $mdayz = $contract_month['m_interval']*28;
            $start_date = "2022-06-01";
            $days=$mdayz."days";
             $end_date = date('Y-m-d', strtotime($start_date. " + $days"));
               
                
                
                $start    = new DateTime($start_date);
                $start->modify('first day of this month');
                $end      = new DateTime($end_date);
                $end->modify('first day of next month');
                $interval = DateInterval::createFromDateString('1 month');
                $period   = new DatePeriod($start, $interval, $end);
                
                foreach ($period as $dt) {
                    // echo $dt->format("Y-m") . "<br>";
                }
    
    ?>
        
        
</div>
</div>
</div>
</div>
        
    </div><!--/.module-->
  </div><!--/.content-->
</div><!--/.span9-->
  
<script type="text/JavaScript">  
    function validate() 
    {
    if( document.invoice.month.value == "0" )
       {
         alert( "Please Select Month Invoice!" );
         return false;
       }
    }
</script>

 <script type="text/javascript">
    $(document).ready(function () {
     $('#btnslide').hide();
     $('#sentInvo').show();
        $("#sentInvo").click(function () {
           $('#sentInvo').hide();
           $('#btnslide').show();
        });

    });
</script>
