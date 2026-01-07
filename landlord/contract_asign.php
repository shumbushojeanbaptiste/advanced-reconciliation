<?php // Add tenant payment

$msg = "";
$msge = "";

if(ISSET($_POST['update-contract']))
{
    $description=$_POST['description'];
  $interval2=$_POST['interval'];   
  $contract_id=$_POST['contract_id']; 
  $c_from_date=$_POST['from_date'];
 
  
  
  $stmtcheck = $db->query('SELECT * FROM tbl_ten_contracts WHERE contr_id="'.$contract_id.'" ');
    $stmtcheck->execute();
    $costcheck=$stmtcheck->fetch();
    $Trent=$costcheck['c_rent_AMT'];
    $interval=$costcheck['m_interval'];
    $ten_id600=$costcheck['tenant_id'];
     
     $rentBY1=$Trent/$interval;
     $newTrent=$rentBY1*$interval2;
     
     $sql = "INSERT INTO `tbl_renewed_contracts` (`contr_id`,`tenant_id`, `c_start_date`,`c_month`,`c_end_date`,`coution_amaunt`,`c_amaunt`,`adv_month`,`adv_end_date`,`cpn_id`,`m_interval`,`c_rent_AMT`,`doc`)
             VALUES ('".$contract_id."','".$costcheck['tenant_id']."','".$costcheck['c_start_date']."','".$costcheck['c_month']."','".$costcheck['c_end_date']."','".$costcheck['coution_amaunt']."',
             '".$costcheck['c_amaunt']."', '".$costcheck['adv_month']."','".$costcheck['adv_end_date']."','".$costcheck['cpn_id']."','".$costcheck['m_interval']."','".$costcheck['c_rent_AMT']."','".$costcheck['doc']."')";
             $conn->exec($sql);
     
     
    $contract_period=$_POST['contract_period'];
    $daysss=30*($contract_period+$costcheck['m_interval']);
    $days=$daysss."days";
    $c_end_date = date('Y-m-d', strtotime($c_from_date. " + $days")); 
  
  
     
    $sql = "UPDATE `tbl_ten_contracts` SET `c_start_date`='$c_from_date',`c_month`='$contract_period',`c_end_date`='$c_end_date',
    `m_interval`='$interval2',`c_rent_AMT`='$newTrent',changing_reasons='$description' where contr_id='$contract_id'";
    $conn->exec($sql); 
 
          foreach($_FILES['files100']['tmp_name'] as $key => $tmp_name )
	    {
	         $messave="  Successfully recorded".$tmp_name;
			   
			 //$stms_last = $db->prepare('SELECT * FROM tbl_ten_contracts where contr_id="'.$contract_id.'"  ');

				try {

				
						$appl_doc_id =$contract_id;
				
				}
				catch (PDOException $ex) {
					echo $ex->getMessage();
				}  
			   
			   
			   $file_name = $appl_doc_id."_".$_FILES['files100']['name'][$key];
			   $file_size =$_FILES['files100']['size'][$key];
			   $file_tmp =$_FILES['files100']['tmp_name'][$key];
			   $file_type=$_FILES['files100']['type'][$key];	
				if($file_size > 5097152){
				  $errors[]='File size must be less than 5 MB';
				}		
			
				$desired_dir="docs";
				if(empty($errors)==true){
				  if(is_dir($desired_dir)==false){
					mkdir("$desired_dir", 0700);		// Create directory if it does not exist
				  }
				  if(is_dir("$desired_dir/".$file_name)==false){
					  move_uploaded_file($file_tmp,"docs/".$file_name);
				  }else{
				 //rename the file if another one exist
					  $new_dir="docs/".$file_name.time();
					   rename($file_tmp,$new_dir) ;				
				   }
					//mysql_query($query);
					
				
					
			$sql2 = "UPDATE `tbl_ten_contracts` SET `doc` ='".$file_name."' WHERE `contr_id` = '$appl_doc_id'";
            //  $sql = "DELETE FROM tbl_payment WHERE `pay_id` = '$pay_id'"; 
              $conn->exec($sql2);
				}else{
				   print_r($errors);
				}
			}
			
			
			 $sqlDEL = "DELETE FROM tbl_next_pay WHERE `contract_id` = '$contract_id'"; 
              $conn->exec($sqlDEL);
              
			   $checking=12/$interval2; 
           if($checking<=1){
             $sql2 = "INSERT INTO tbl_next_pay (tenant_id,next_date,contract_id,comp_id) 
             VALUES ('".$ten_id600."', '".$c_from_date."','".$contract_id."','".$company."')";  
             $conn->exec($sql2);    
               
           }
           
           else{
               
               $abc=1;
               while($abc<=$checking){
                   if($abc==1){
                       
                $sql2 = "INSERT INTO tbl_next_pay (tenant_id,next_date,contract_id,comp_id) 
             VALUES ('".$ten_id600."', '".$c_from_date."','".$contract_id."','".$company."')";     
             $conn->exec($sql2);
                   }
                   else{
                 $stmt1NEXT = $db->query('SELECT * FROM tbl_next_pay order by pnext_id desc limit 1 ');
     $stmt1NEXT->execute();
     $cost1NEXT=$stmt1NEXT->fetch();
            $date11=$cost1NEXT['next_date'];
            
             $daysss1NEXT=30*$interval2;
    $days1NEXT=$daysss1NEXT."days";
    $NEXTdatepay= date('Y-m-d', strtotime($date11. " + $days1NEXT"));
             $sql2 = "INSERT INTO tbl_next_pay (tenant_id,next_date,contract_id,comp_id)
             VALUES ('".$ten_id600."', '".$NEXTdatepay."','".$contract_id."','".$company."')";      
             $conn->exec($sql2); 
                   }
               
                   
                $abc++;   
               }
               
           }
     
          ?>
       <script>
               jQuery(function validation(){
        swal("Well done! ", "Contract Updated Successfully. ", "success", {
        button: "ok",
          });
        });
        </script>
   <?php
  
  // $msg = " Contract Updated Successfully "; 
}

if(ISSET($_POST['insert_contract'])){
    $interval=$_POST['interval'];
    $room_number=$_POST['c_room'];
    $tenant_id=$_POST['tenant_id'];
    $c_from_date=$_POST['from_date'];
    $contract_period=$_POST['contract_period'];
    $daysss=30*$contract_period;
    $days=$daysss."days";
    $c_end_date = date('Y-m-d', strtotime($c_from_date. " + $days"));
    
    
    /**
     * check for contact existance
     * $contractExistance =$conn->prepare("SELECT *from tbl_c_room where room_id ='".$room_number."'");
     * 
     */
     
    
    $advance_month=$_POST['advance_month'];
    
    $daysss1=30*$advance_month;
    $days1=$daysss1."days";
    $adv_end_date = date('Y-m-d', strtotime($c_from_date. " + $days1"));  
    $coution=$_POST['coution'];
    $from_date=$_POST['from_date'];
    $total_amaount1=0;
    $remain_room=0;
    $a56=0;
    foreach( $_POST['c_room'] as $value3){
        $a56++;   
    }
    
    // foreach( $_POST['c_room'] as $value){
        $stmt = $conn->prepare('SELECT * FROM tbl_assign WHERE tbl_assign.room_id="'.$_POST['c_room'].'" ');
        $stmt->execute();
        $cost=$stmt->fetch();
        $total_amaount1=$total_amaount1+$cost['amount']; 
         
        $stmtcheck = $conn->prepare("SELECT room_id FROM tbl_c_room WHERE room_id = '".$_POST['c_room']."' and croom_status=1");
        $stmtcheck->execute();
        $costcheck=$stmtcheck->rowCount();
        if($costcheck<0){
                $msg = "Contract Exists!!";
                }
        else{
            if(empty($_POST['advance_month']) || $_POST['advance_month']==0){
                $advAMT=0;
                $total_amaount=(($total_amaount1*$a56)+$advAMT)+$coution;
            }
            else{
                
                $advAMT=$_POST['advance_month']*($total_amaount1*$a56);   
                $total_amaount=$advAMT+$coution;
            }
    
        $c_rent_AMT=$total_amaount1*$interval*$a56;
        if($remain_room==0){
            $sql = "INSERT INTO `tbl_ten_contracts` (`tenant_id`, `c_start_date`,`c_month`,`c_end_date`,`coution_amaunt`,`c_amaunt`,`adv_month`,`adv_end_date`,`cpn_id`,`m_interval`,`c_rent_AMT`)
            VALUES ('$tenant_id', '$c_from_date','$contract_period','$c_end_date','$coution','$total_amaount','$advance_month','$adv_end_date','$company','$interval','$c_rent_AMT')";
            $conn->exec($sql);
            $lastId = $conn->lastInsertId();
                  
                  
            foreach($_FILES['files1']['tmp_name'] as $key => $tmp_name ){
    	        $messave="  Successfully recorded".$tmp_name;
    			$stms_last = $db->prepare("SELECT contr_id FROM tbl_ten_contracts ORDER BY contr_id DESC LIMIT 1");
    			try {
    
    					$stms_last->execute(array());
    					$row_count_last = $stms_last->rowCount();
    					if ($row_count_last > 0)
    						{
    						$rows = $stms_last->fetch(PDO::FETCH_ASSOC);
    							$appl_doc_id = $rows['contr_id'];
    						}
    					else
    						{
    						$appl_doc_id = 1;
    						
    						}
    				}
    				catch (PDOException $ex) {
    					echo $ex->getMessage();
    				}
    				
    			    $file_name = $appl_doc_id."_".$_FILES['files1']['name'][$key];
    			    $file_size =$_FILES['files1']['size'][$key];
    			    $file_tmp =$_FILES['files1']['tmp_name'][$key];
    			    $file_type=$_FILES['files1']['type'][$key];	
    			    if($file_size > 5097152){
    				  $errors[]='File size must be less than 5 MB';
    				}		
    			
    				$desired_dir="docs";
    				if(empty($errors)==true){
    				  if(is_dir($desired_dir)==false){
    					mkdir("$desired_dir", 0700);		// Create directory if it does not exist
    				  }
    				  if(is_dir("$desired_dir/".$file_name)==false){
    					  move_uploaded_file($file_tmp,"docs/".$file_name);
    				  }else{
    				 //rename the file if another one exist
    					  $new_dir="docs/".$file_name.time();
    					   rename($file_tmp,$new_dir) ;				
    				   }
    					//mysql_query($query);
    					
    				
    					
    					 $sql = "UPDATE `tbl_ten_contracts` SET `doc` ='".$file_name."' WHERE `contr_id` = '$appl_doc_id'";
                //  $sql = "DELETE FROM tbl_payment WHERE `pay_id` = '$pay_id'"; 
                  $conn->exec($sql);
    				}else{
    				   print_r($errors);
    				}
    			}
            
                 
                //  foreach($_POST['c_room'] as $value2){
                    if($_POST['c_room']!=0){
                        $stmt1 = $db->query('SELECT * FROM tbl_assign WHERE tbl_assign.room_id="'.$_POST['c_room'].'" ');
                        $stmt1->execute();
                        $cost1=$stmt1->fetch();
                        $room_id=$cost1['room_id'];
                        $rent=$cost1['amount']; 
                                 
                        $sql2 = "INSERT INTO tbl_c_room (contr_id, room_id, rent_amaunt, next_pay, cop_id) VALUES ('".$lastId."', '".$room_id."','".$rent."','".$interval."','".$company."')";  
                        $conn->exec($sql2); 
                        $del=$conn->prepare("DELETE FROM tbl_c_room WHERE room_id=0");
                        $del->execute();
                 
                     } else{
                         
                     }  
                //  }
                 
               $checking=12/$interval; 
               if($checking<=1){
                 $sql2 = "INSERT INTO tbl_next_pay (tenant_id,next_date,contract_id, comp_id) 
                 VALUES ('".$tenant_id."', '".$c_from_date."','".$lastId."', '".$company."')";  
                 $conn->exec($sql2);    
                   
               }
               
                else{
                   
                    $abc=1;
                    while($abc<=$checking){
                        if($abc==1){
                                       
                            $sql2 = "INSERT INTO tbl_next_pay (tenant_id,next_date,contract_id, comp_id) 
                            VALUES ('".$tenant_id."', '".$c_from_date."','".$lastId."', '".$company."')";  
                            $conn->exec($sql2);
                        }
                        else{
                            $stmt1NEXT = $db->query('SELECT * FROM tbl_next_pay order by pnext_id desc limit 1 ');
                            //  $stmt1NEXT->execute();
                            $cost1NEXT=$stmt1NEXT->fetch();
                            $date11=$cost1NEXT['next_date'];
                                
                            $daysss1NEXT=30*$interval;
                            $days1NEXT=$daysss1NEXT."days";
                            $NEXTdatepay= date('Y-m-d', strtotime($date11. " + $days1NEXT"));
                            $sql2 = "INSERT INTO tbl_next_pay (tenant_id,next_date,contract_id, comp_id) VALUES ('".$tenant_id."', '".$NEXTdatepay."','".$lastId."', '".$company."')";  
                            $conn->exec($sql2); 
                       }
                    $abc++;   
                   }
                }
                $msg = "Contract Saved Successfully";  
            }
        }
    // }
}
 
 if(ISSET($_POST['add_tenant_payment'])){
         try{
             $payment_id = $_fetch['pay_id'];
             $ten_ref = $_POST['tenant_ref'];
             $bank = $_POST['bank'];
             $slip_no = $_POST['slip_no'];
             $payment_fees = $_POST['payment_fees'];
             $payment_desc = $_POST['comment'];
             $date = $_POST['date'];
             $todaysdate = date("Y-m-d H:i:s");
             
            // generate payment
            
            $year =  date('Y');
            $year = substr( $year, -2);
            $li_qu = $db->prepare("SELECT * FROM tbl_payment ORDER BY pay_id DESC LIMIT 1");
            $li_qu->execute();
            $fli = $li_qu->fetch();
            $li = str_pad($fli['pay_id'], 6, "0", STR_PAD_LEFT);
            $new_pay_id = $year.'-'.$li;
 
             $sql = "INSERT INTO `tbl_payment` (`trans_code`, `pay_ref_no`,`bank_id`,`slip_no`,`pay_due_date`,`pay_amount`,`pay_description`,`pay_trm`,`pay_user`)
             VALUES ('$new_pay_id', '$ten_ref','$bank','$slip_no','$date','$payment_fees','$payment_desc','$todaysdate','$company')";
             $conn->exec($sql);
             $lastId = $conn->lastInsertId();
             
             $msg = " Successfully Added Payment!";
             
             echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=tenant-payments">';
             
         }catch(PDOException $e){
             $msg = $e->getMessage();
         }
         
     }
  
 ?>
 
    <!--update Invoice-->
     <?php
     if(ISSET($_POST['update_payment'])){
         try{
             $pay_id = $_POST['pay_id'];
             $slip_no = $_POST['slip_no'];
             $amount = $_POST['pay_amount'];
             $date = $_POST['pay_date'];
             $pay_comment = $_POST['pay_descr'];
     
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             
             $sql = "UPDATE `tbl_payment` SET `slip_no` = '$slip_no', `pay_due_date` = '$date', `pay_amount` = '$amount', `pay_description` = '$pay_comment' WHERE `pay_id` = '$pay_id'";
             $conn->exec($sql);
   
             $msg = "Updated Successfully";
             echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=tenant-payments">';
             
         }catch(PDOException $e){
             echo $e->getMessage();
         }
     }
     ?>   
     <!-- delete PACKAGE-->
         <?php
             if(ISSET($_GET['del_pay_id'])){
             $pay_id = $_GET['del_pay_id'];
             $sql = $conn->prepare("DELETE from `tbl_payment` WHERE `pay_id`='$pay_id' ");
             $sql->execute();
             
             $msge = "Deleted Successfully!";
             echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=tenant-payments">';
         }
 ?>

<style>
    .modal { display:none; }
    .modal-title {color: #111;}
    td {
        font-size: 12px;
    }
</style>
<div class="span9">
  <div class="content">

        <?php
            if(($msg =="Contract Saved Successfully")) {
            ?>
    	<div class="alert alert-success">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Well done!</strong> <?php echo $msg; ?> 
    	</div>
        <?php
          }
           else if($msg =="Contract Exists!!"){
        ?>
    	<div class="alert alert-danger">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Oh sorry!</strong>  <?php echo $msg; ?> 
    	</div>
        <?php
           }
        ?>
 <div class="module">
      <ul class="nav nav-tabs">
    <li class="active"><a href="#view_pay" data-toggle="tab">Manage Contract</a></li>
</ul>
     <div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
        <br><br>
      <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link <?php  if(empty($_REQUEST['keywordV'])){ ?> active <?php } else{   }?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
              <i class="fe fe-plus"></i> New  Contract</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(!empty($_REQUEST['keywordV'])){ ?> active <?php } else{   }?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
              <i class="fe fe-eye"></i> View Tenant Contract</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade <?php if(empty($_REQUEST['keywordV'])){ ?> show active <?php } else{   }?>" id="home" role="tabpanel" aria-labelledby="home-tab"> 
         <?php include 'new-Contract.php'; ?>
         </div>
        <div class="tab-pane fade <?php if(!empty($_REQUEST['keywordV'])){ ?> show active <?php } else{   }?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            
            
        <?php include 'view-Contract.php'; ?>
       
      </div> <!-- form-row --> 
      
      <br><br>
            
      <div id="display_specs_pay"></div>      
            
    
    </div>
      
  <div name='display_specs' id='display_specs'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->
 
 
</div><!--/.module-->

</div><!--/.content-->
</div><!--/.span9-->


<!--modal for payment update-->
<script>
    $('#myTable').DataTable( {
    responsive: true
} );
    
</script>

<script type="application/javascript">
var alphaExp = /^[a-zA-Z]+$/; //Variable to validate only alphabets
var alphaspaceExp = /^[a-zA-Z\s]+$/; //Variable to validate only alphabets and space
var numericExpression = /^[0-9]+$/; //Variable to validate only numbers
var alphanumericExp = /^[0-9a-zA-Z]+$/; //Variable to validate numbers and alphabets
var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/; //Variable to validate Email ID 

function validateform()
{
	if(document.frmPayRecord.tenant_ref.value == "")
	{
		alert("Tenant name should not be empty..");
		document.frmPayRecord.tenant_ref.focus();
		return false;
	}
	else if(document.frmPayRecord.bank.value == "")
	{
		alert("Bank should not be empty..");
		document.frmPayRecord.bank.focus();
		return false;
	}
	else if(document.frmPayRecord.bank.value == "")
	{
		alert("Bank time should not be empty..");
		document.frmPayRecord.bank.focus();
		return false;
	}
	else if(document.frmPayRecord.slip_no.value == "")
	{
		alert("Bank Slip should not be empty..");
		document.frmPayRecord.slip_no.focus();
		return false;
	}
	else if(document.frmPayRecord.amount.value == "")
	{
		alert("Amount number should not be empty..");
		document.frmPayRecord.amount.focus();
		return false;
	}
	else if(!document.frmPayRecord.amount.value.match(numericExpression))
	{
		alert("Amount number not valid..");
		document.frmPayRecord.amount.focus();
		return false;
	}
	else if(document.frmPayRecord.comment.value == "")
	{
		alert("Comment should not be empty..");
		document.frmPayRecord.comment.focus();
		return false;
	}
	
	else if(document.frmPayRecord.date.value == "")
	{
		alert("DATE should not be empty..");
		document.frmPayRecord.date.focus();
		return false;
	}

	else
	{
		return true;
	}
}
</script><script type="text/javascript">
    $(document).ready(function () {
        $("#contract_period").keyup(function () {
            var from_date=$('#from_date').val();
            var contract_period=$('#contract_period').val();
            if(contract_period!=''&&  from_date!=''){
                
              	$(this).after('<div id="loader66"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('load_end_date.php?contract_period=' + contract_period + '&from_date=' + from_date , function (data) {
             $("#display_end_date").show();   
             $("#display_end_date").html(data);
                $('#loader66').slideUp(910, function () {
                    $(this).remove();
                });
            }); 
            }
            else{
                $("#display_end_date").hide();
            }
           
        });
        
        
        
                $("#contract_period2").keyup(function () {
            var from_date=$('#from_date').val();
            var contract_period=$('#contract_period').val();
            if(contract_period!=''&&  from_date!=''){
                
              	$(this).after('<div id="loader66"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('load_up_end.php?contract_period=' + contract_period + '&from_date=' + from_date , function (data) {
             $("#display_end_date2").show();   
             $("#display_end_date2").html(data);
                $('#loader66').slideUp(910, function () {
                    $(this).remove();
                });
            }); 
            }
            else{
                $("#display_end_date").hide();
            }
           
        });

    });
    
    
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#date_to").change(function () {
            var date_from = $('#date_from').val();
            var company=<?php echo $company  ?>;
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('load_payment.php?date_to=' + $(this).val() + '&date_from=' + date_from + '&company=' + company , function (data) {
             $("#display_specs_pay").html(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>