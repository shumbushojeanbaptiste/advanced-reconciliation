<?php 
 if(isset($_POST['btn_save'])){
     $month = $_POST['month'];
     $SETYEAR =  date('Y');
     $chk_dt = $SETYEAR."-".$month;
     
    $te_invoice = $db->prepare("SELECT * FROM tbl_invoice WHERE auto = 'yes' AND DATE_FORMAT(invoice_date, '%Y-%m') = '".$chk_dt."' AND cpny_ID = '".$cpny_ID."' ORDER BY invoice_id DESC LIMIT 1");
    $te_invoice->execute();
    if($te_invoice->rowCount() == 0){
     
  //Check Company
   $stmt = $db->prepare("SELECT * FROM tbl_admins INNER JOIN tbl_users_login ON tbl_admins.adm_id=tbl_users_login.profile_id  WHERE  tbl_users_login.role_id = '2' AND tbl_users_login.profile_id = ?");
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
    WHERE tbl_rooms.company_id='".$cmp_ID."'");
    $sql->execute();
    
     while($fetch = $sql->fetch()) {
         
         $tenant_phone = $fetch['ten_phone'];
         $amount = $fetch['amount'];
         $room_no = $fetch['room_no'];
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
        #########################################
       
        try
          {
            
            $inv_insert_qu = $db->prepare("INSERT into tbl_invoice(`invoice`, `invoice_fees`, `inv_desc`, `invoice_date`, `inv_ref_no`, `cpny_ID`, `auto`,`inv_date`,`inv_message`)
            VALUES ('".$invoice."', '".$fetch['amount']."', 'rent', '$paid_dt', '".$fetch['ten_ref_no']."', '$cmp_ID', 'yes', '$date_time','1')");
            $inv_insert_qu->execute(); 
            $lastId = $db->lastInsertId();
    
            $msg="Mukiriya wacu,$cmp_full irakumenyesha ko ubukode bw'ukwezi kwa $sub_mon ari $amount Frw y'icyumba No:$room_no. Murakoze Tel: 0788472175";
            $data=array(
                      "sender"=>"$cmp_MSG",
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
            
            $message = "Invoiced Successfully";
            echo'<meta http-equiv="refresh"'.'content="10;URL=index?file=monthly_inv">';
           
         }
        catch (PDOException $ex) {
        //Something went wrong rollback!
        echo $ex->getMessage();
       }
       
     }//While End
     
    }else {
        $msge = "Failed! Invoice Already Saved";
       echo'<meta http-equiv="refresh"'.'content="10;URL=index?file=monthly_inv">';
    }
    
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
            if($message){
        ?>
    	<div class="alert alert-success">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Well done!</strong> <?php echo $message; ?> 
    	</div>
        <?php
          }
           else if($msge){
        ?>
    	<div class="alert alert-error">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Oh Snap!</strong>  <?php echo $msge; ?> 
    	</div>
        <?php
           }
        ?>
      
    <form name="invoice" method="POST" action="" onsubmit="return validate(this);">
     <div class="form-group">
      <label id="mon">Select Month</label>
      </div>
      <div class="form-group">
         <select name="month" id="month" class="form-control select2">
                 <option value="0">Choose One...</option>
                 <?php
                     $sql = $conn->prepare("SELECT * FROM `tbl_month` ORDER BY id_month");
                     $sql->execute();
                     while($fetch = $sql->fetch()){
                 ?>
         
             <option value="<?php echo $fetch['mon_code']?>"><?php echo $fetch['month_name']?></option>
         
         <?php
             }
         ?>      
         </select>
         </div>
         <div class="form-group">
             <button type="submit" name="btn_save" class="btn btn-default">Invoice</button>
         </div>
        </form>
        
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
