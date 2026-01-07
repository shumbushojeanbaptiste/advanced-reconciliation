    <!--update Invoice-->
     <?php
     
  
     
    
  
     ?>   

<div class="span9">
  <div class="content">

        
 <div class="module">
<div class="col">
  <h2 class="h5 page-title">Send SMS T Selected Client(s)</h2>
</div>

 
</div><!--/.module-->

</div><!--/.content-->
</div><!--/.span9-->
<div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
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
     
         <?php  include'send_to_all.php'; ?>
        <br><br>
        
      <div class="table-responsive">
           <form  action="sendbysomeSELECTED" id="allSelectedClient" enctype="multipart/form-data" method="POST">
	<table class="table datatables table-hover" >
		<thead>
			<tr>
			     <th>#</th>
			     <th>Ref No</th>
                 <th>Tenant</th>
                 <th>Phone No</th>
                
                 <th>For SMS</th>
			</tr>
		</thead>
		<tbody>
		    
			 <?php
			 
			  $li_qu_admin = $db->prepare("SELECT * FROM tbl_admins where company_id='".$cpny_ID."' ");
        $li_qu_admin->execute();
        $fli_admin = $li_qu_admin->fetch();
        $company_number=$fli_admin['phone'];
        
        $li_qu_adminMM = $db->prepare("SELECT * FROM tbl_company where cpny_ID='".$cpny_ID."' ");
        $li_qu_adminMM->execute();
        $fli_adminMM = $li_qu_adminMM->fetch();
          $short_name=$fli_adminMM['side'];
        $cp_name_char=$fli_adminMM['msg_char'];
        $short_name1=$fli_adminMM['short_name'];
        
        
			    $cont = 0;
                 $sql = $conn->prepare("SELECT * FROM tbl_tenants 
                    INNER JOIN tbl_assign ON tbl_assign.tent_id = tbl_tenants.ten_id
                    WHERE tbl_tenants.ten_staff = $cpny_ID AND tbl_assign.assgn_status = 1 group by tbl_tenants.ten_id ");
                 $sql->execute();
                 while($fetch = $sql->fetch()){
                     $ten_id = $fetch['ten_id'];
                     $ten_ref = $fetch['ten_ref_no'];
                     $cont +=1;
                     $totinv2=0;
                 $sql2 = $conn->prepare("SELECT * FROM `tbl_invoice` WHERE inv_ref_no = '".$ten_ref."'");
                 $sql2->execute(array()); // execute the query with parameter $code...
                 if ( $rowcount = $sql2->rowCount() > 0)// if there is data for that payment number...
                    {
                     while($row1 = $sql2->fetch(PDO::FETCH_ASSOC)){
                     $totinv2 = $totinv2+($row1['invoice_fees']/$row1['unit_value']);
                     
                     }
                    }
                     else{
                        $totinv2 = 0;
                     }
                     $payment=0;
                $sql4 = $conn->prepare("SELECT * FROM `tbl_payment` WHERE pay_ref_no = '".$ten_ref."' ");
                $sql4->execute(array()); // execute the query with parameter $code...
                    if($rowcount = $sql4->rowCount() > 0)// if there is data for that payment number...
                    {
                   while($row3 = $sql4->fetch(PDO::FETCH_ASSOC)){
                    $payment =$payment+($row3['pay_amount']/$row3['c_value']);
                   }
                     }
                     else{
                      $payment = 0;
                     }    
             ?>
             
             
		       <tr class="odd gradeX">
		         <td><?php echo $cont; ?></td>
                 <td><?php echo $ten_ref; ?></td>
                 <td><?php echo $fetch['te_names']; ?></td>
                 <td><?php echo $fetch['ten_phone']; ?></td>
                
                 
                 <td>
               
                <input type="checkbox"  id="slectedStaff" name="slectedStaff[]" value="<?php echo $ten_ref; ?>" >


                 </td>
             </tr>
             
              
             
             <?php 
             $nam = $fetch['te_names'];
             $am = number_format($totinv2-$payment);
           if($cpny_ID==6 || $cpny_ID==7 || $cpny_ID==8){
                 $msg1="Mukiriya wacu, $Tname turakwibutsa ko Utubereyemo umwenda  kuri $short_name ungana $am Frw. kubufatanye na Grobal Businnes Exor Ltd Tel: $company_number" ;
             }
             else if($cpny_ID==13){
                $msg1="Mukiriya wacu $short_name1 irakwibutsa  kwishyura ubukode bungana $am Frw mwakwishyura  kuri Account no:100080726265  muri BK,  Tel: $company_number" ;
              
            }
             else{
            //   $msg1="Mukiriya wacu, $nam turakumenyesha ko Ubereyemo $cp_name_char umwenda ungana $am Frw Tel: $company_number";   
              $msg1="Mukiriya wacu $cp_name_char irakwibutsa  kwishyura ubukode bungana $am Frw mwakwishyura  mukoresheje code Yanyu: $ten_ref kuri Account no:0130-1057830-36  muri Cogebanque,  Tel: $company_number" ;
             
                 
             }
             ?>
            
                 <?php
             
               }
            ?>
            <tr >  
              <td colspan="3"></td>
              
             
              
              
             
              <td align="right" colspan="2" >
                  
    <button type="button" name="btn_save" data-toggle="modal" data-target=".modal-fullSelected" id="sentInvo" class="btn btn-sm btn-primary" >Send SMS</button>
  <button class="btn btn-primary" type="button" disabled id="btnslide">
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    <span class="sr-only">Loading...</span>
     </button>
              </td>
              
            </tr>
		 </tbody>
		 
    	</table>
     
    </div>
    <?php
    
    include 'message-to-selected.php';?>
             
    


    </form>
      
  <div name='display_specs' id='display_specs'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->

<!--modal for invoice update-->

 <script type="text/javascript">
 
 $(document).ready(function(){
   //alert(3);
   $('#btnslide').hide();
     $('#sentInvo').show();
     $('#btnslide2').hide();
     $('#sentInvo2').show();

          
$("#allSelectedClient").submit(function(e){
            e.preventDefault();
   
           var formdata = new FormData(this);
           
    $('#btnslide').show();
    $('#sentInvo').hide();
     
       $('#btnslide2').show();
     $('#sentInvo2').hide();
            $.ajax({
                url: "sendbysomeSELECTED.php",
                type: "POST",
                data: formdata,
                mimeTypes:"multipart/form-data", 
                contentType: false,
                cache: false,
                processData: false,
                success: function(formData){
                
          
        if(formData==1){
              
    pop_yes(formData); }
    
    else if(formData==5){
        pop_empty(formData);
    }
    else {
       pop_no(formData); 
    }
    $('#btnslide').hide();
    $('#sentInvo').show(); 
    $('#btnslide2').hide();
    $('#sentInvo2').show(); 
    
     $('#IDModalselected').modal('toggle');
               
           
                },error: function(){
                    alert("okey");
                }
             });
          });
          
});

  
    function pop_yes(feedback) {
               jQuery(function validation(){
        swal("Well done! ", "SMS Sent", "success", {
        button: "ok",
          });
        });
    }
    
      function pop_no(feedback) {
               jQuery(function validation(){
        swal("Not Sent! ", "No client selected", "warning", {
        button: "ok",
          });
        });
    }
    
       function pop_empty(feedback) {
               jQuery(function validation(){
        swal("Not Sent! ", "Empty SMS content", "warning", {
        button: "ok",
          });
        });
    }
  
    

    
</script>