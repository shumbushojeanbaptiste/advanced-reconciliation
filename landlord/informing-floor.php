
<?php
// Add tenant payment


if (isset($_REQUEST['Client_inform'])) {

    $sms_content = trim($_POST['smscontent']);
       $floor=$_POST['floor'];

    if (!empty($sms_content)) {

        // ✅ SANITIZE SMS CONTENT (InTouchSMS does NOT allow Unicode apostrophes)
        $msg1 = $sms_content;

        // Replace curly apostrophes with normal '
        $msg1 = str_replace("’", "'", $msg1);

        // Replace curly double quotes with normal "
        $msg1 = str_replace(["“", "”"], '"', $msg1);

        // Replace other invisible Unicode characters
        $msg1 = preg_replace('/[\x{00A0}\x{200B}\x{FEFF}]/u', ' ', $msg1);

        // Convert other incompatible symbols to safe ASCII forms
        $msg1 = iconv('UTF-8', 'UTF-8//IGNORE', $msg1);


        try {

            // ================================================
            // 1. GET ALL ACTIVE TENANTS FROM COMPANY
            // ================================================
            $sql100 = $conn->prepare("
                SELECT * FROM tbl_tenants 
                INNER JOIN tbl_assign ON tbl_assign.tent_id = tbl_tenants.ten_id
                WHERE tbl_tenants.ten_staff = :cpny_id 
                AND tbl_assign.assgn_status = 1 and tbl_assign.room_id in (select room_id from tbl_rooms where r_floor='".$floor."' )
                GROUP BY tbl_assign.tent_id
            ");
            $sql100->execute([':cpny_id' => $cpny_ID]);


            // ================================================
            // 2. GET COMPANY NAME, SENDER ID, COMPANY NUMBER
            // ================================================
            $stmtCompany = $db->prepare("SELECT * FROM tbl_company WHERE cpny_ID = :id");
            $stmtCompany->execute([':id' => $cpny_ID]);
            $company = $stmtCompany->fetch();

            $cp_name_char  = $company['msg_char'];  // Sender name


            // ================================================
            // 3. LOOP THROUGH CLIENTS & SEND SMS
            // ================================================
            while ($fetch100 = $sql100->fetch()) {

                $ten_phone = trim($fetch100['ten_phone']);
                if ($ten_phone == "" || strlen($ten_phone) < 10) continue;

                // ----------------------------------------------
                // SEND SMS THROUGH INTOUCHSMS API
                // ----------------------------------------------
                $postData = [
                    "sender"     => $cp_name_char,
                    "recipients" => $ten_phone,
                    "message"    => $msg1
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.intouchsms.co.rw/api/sendsms/.json");
                curl_setopt($ch, CURLOPT_USERPWD, "$smsusername:$smspwd");
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
                $result = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);


                // ✅ OPTIONAL: Log only if API sent successfully
                if ($httpcode == 200) {

                    $inv_insert = $db->prepare("
                        INSERT INTO tbl_informingSMS(received_no, content, comp_id)
                        VALUES (:phone, :content, :comp)
                    ");

                    $inv_insert->execute([
                        ':phone'   => $ten_phone,
                        ':content' => $msg1,
                        ':comp'    => $cpny_ID
                    ]);
                }
            }

        } catch (PDOException $e) {
            echo "<div style='color:red;'>ERROR: " . $e->getMessage() . "</div>";
        }
?>
        <!-- Success Message -->
        <script>
            jQuery(function () {
                swal("Sent!", "SMS Sent to all Clients!", "success");
            });
        </script>
<?php
    } else {
?>
        <!-- Empty message -->
        <script>
            jQuery(function () {
                swal("Not Sent!", "Text message is Empty!", "warning");
            });
        </script>
<?php
    }
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
 <div class="module">
<div class="col">
  <h2 class="h5 page-title">Informing Message</h2>
</div>
     <div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
        
        <div class="row my-4">
             <div class="col-md-2"></div>
                <div class="col-md-8">
                  <div class="card shadow bg-primary text-center mb-4">
                    <div class="card-body p-5">
                      <span class="circle circle-md bg-primary-light">
                        <i class="fe fe-mail fe-24 text-white"></i>
                      </span>
                       <hr class="my-4">
                        <form action="" method="POST">
                        <div class="form-group">
                          <label for="exampleFormControlTextarea1" class="sr-only">Your Message </label>
                         <select id="floor_code" class="form-control select2" name="floor" data-placeholder="Choose Floor..." style="width:100%" required >
                             <option></option>
                                 <?php
                                     $stmt = $db->query('SELECT Block_code, block_name FROM tbl_block where company_id="'.$cpny_ID.'" AND status=1 ORDER BY Block_code ASC');
        
                                            try
                                            {
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $row['Block_code']; ?>"><?php echo $row['block_name']; ?></option>
                                                    <?php
                                                     }
                                                }
                                                catch (PDOException $ex) {
                                                //Something went wrong rollback!
                                                echo $ex->getMessage();
                                        }
                                  ?>	
                            </select>
                      
                        </div>
                           <h6 class="mb-3">Write Your text message in the box below:</h6>
                      <!--<form action="" method="POST">-->
                        <div class="form-group">
                          <label for="exampleFormControlTextarea1" class="sr-only">Your Message</label>
                          <textarea class="form-control bg-light" name="smscontent" id="exampleFormControlTextarea1" rows="3"  ></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="form-check form-check-inline ml-1">
                            
                          </div>
                          <div class="flex-fill mr-2 text-right">
                           
                          </div>
                          <button  type="button" data-toggle="modal" data-target=".modal-fullsending2" class="btn btn-lg bg-primary-light text-white">Send<i class="fe fe-arrow-right fe-16 ml-2"></i></button>
                          
                          <div class="modal fade modal-fullsending2" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
<button aria-label="" type="button" class="close px-2" data-dismiss="modal" aria-hidden="true">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-dialog modal-dialog-right" role="document">
  <div class="modal-content">
    <div class="modal-body text-center">
        <?php 
        
        
         $sql100Count = $conn->prepare("SELECT * FROM tbl_tenants 
                    INNER JOIN tbl_assign ON tbl_assign.tent_id = tbl_tenants.ten_id
                    WHERE tbl_tenants.ten_staff = $cpny_ID  AND tbl_assign.assgn_status = 1  group by tbl_assign.tent_id ");
                 $sql100Count->execute();
                 $data4Count=$sql100Count->rowCount();?>
      <p>Are you sure Want to Send message To the Client(s) </p>
      
        
      
    </div>
    <div class="modal-footer">
    <button type="button" class="btn mb-2 btn-outline-secondary btn-sm" data-dismiss="modal">No</button>
    <button type="submit" class="btn mb-2 btn-outline-warning btn-sm" name="Client_inform" >Yes</button>
        
    </div>
  </div>
</div>
</div>
                          
                        </div>
                      </form>
                      
                    </div> <!-- .card-body -->
                  </div> <!-- .card -->
                </div> <!-- .col-md-->
                <div class="col-md-2"></div>
              </div>
         </div>
       
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
</script>

