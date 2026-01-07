       <style>
       
        .icon-rtl {
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
     border-radius: 20px;
     border: 1px solid #2d9fd9;
     color: #a0d18c;
     width: 250px;
     height: 30px;
     padding-left: 10px;
     background: url("../images/search.png") no-repeat right;
     background-size: 27px;
    }
    
.icon-rtl:focus {
     outline: none;
     border: 1px solid #a0d18c;
     color: #2d9fd9;
     background: url("../images/search.png") no-repeat right;
     background-size: 27px;
}
    </style>
<div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
        <br><br>
      <div class="form-row">
        <div class="form-group col-md-4">
        </div>
        <div class="form-group col-md-4">
          <!--<label for="simple-select2">Due Date</label>-->
          <input type="text" class="form-control icon-rtl" name="acad" id="search-box" placeholder="Search by Tenant Name or Phone">
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div class="form-row">
        <div class="form-group col-md-4">
        </div>
        <div class="form-group col-md-4" id="suggesstion-box"></div>
      </div> <!-- form-row -->
      <?php if(!empty($_REQUEST['keyword1']))
      {
          
    	       $stms = $db->prepare("SELECT *,SUM(pay_amount) as sm FROM  tbl_payment
                 WHERE tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."' AND tbl_payment.status != 0 group by tbl_payment.pay_due_date 
    			");
    			$stms->execute();
    			
    			$row_count = $stms->rowCount();
    // 			last payment
    	       $stms11 = $db->prepare("SELECT * FROM  tbl_payment
                 WHERE tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."' AND tbl_payment.status != 0 order by tbl_payment.pay_id desc limit 1 
    			");
    			$stms11->execute();
    			
    			$row_count11 = $stms11->fetch();
    			
    	       $stms33 = $db->prepare("SELECT *,SUM(invoice_fees) as sm FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$_REQUEST['keyword1']."'
    			");
    			$stms33->execute();
    			$row_count33 = $stms33->fetch();
    // 			special inv
    	       $stms33s = $db->prepare("SELECT *,SUM(invoice_fees) as sm FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$_REQUEST['keyword1']."' AND auto ='no'
    			");
    			$stms33s->execute();
    			
    			$row_count33s = $stms33s->fetch();
    // 			lst inv
                $stms33l = $db->prepare("SELECT * FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$_REQUEST['keyword1']."' order by invoice_id desc limit 1
    			");
    			$stms33l->execute();
    			
    			$row_count33l = $stms33l->fetch();
    			
    	        $stms2 = $db->prepare("SELECT * FROM  tbl_tenants
                 WHERE ten_ref_no='".$_REQUEST['keyword1']."'
    			");
    			$stms2->execute();
    			$row_count2 = $stms2->fetch();
    			
    	        $stms21 = $db->prepare("SELECT * FROM  tbl_tenants INNER JOIN tbl_assign ON tbl_assign.tent_id = tbl_tenants.ten_id
                 WHERE tbl_tenants.ten_ref_no='".$_REQUEST['keyword1']."'
    			");
    			$stms21->execute();
    			$row_count21 = $stms21->fetch();
    			if($row_count2>0){
        ?>
        
        <div class="card-header">
          <strong class="card-title"><?php echo $row_count2['te_names'];?></strong>
          <a class="float-right small text-muted" href="#!"></a>
        </div>
        <br>
        <div class="row">
        <div class="col-md-12 col-lg-12">
      <div class="card shadow">
        <div class="card-header">
          <strong class="card-title">Statement</strong>
          <a class="float-right small text-muted" href="#!"></a>
        </div>
        <div class="card-body my-n2">
        <div class="table-responsive">
        <table class="table table-bordered table-striped mb-none" id="" style="font-size:11px;">
            <thead>
                <tr>
                    <th>Rent Time</th>
                     <th>Monthly Invoice</th>
                     <th>total invoiced</th>
                     <th>total payed</th>
                     <th>Balance</th>
                     <th>Special Invoice</th>
                     <th>Last Invoice</th>
                     <th>Last Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php
               $tot=0;
    			$i=0;
    			$rows = $stms->fetch(PDO::FETCH_ASSOC);
    			    $pay_id= $rows['pay_id'];
    			    $pay_due_date= $rows['pay_due_date'];
    			    $i++;
    			    
    			    $stms1 = $db->prepare("SELECT * FROM  tbl_payment
    	        INNER JOIN `tbl_bank` ON tbl_payment.bank_id=tbl_bank.bank_id 
                 INNER JOIN tbl_tenants ON tbl_payment.pay_ref_no=tbl_tenants.ten_ref_no
                 WHERE tbl_payment.pay_due_date='".$pay_due_date."' AND tbl_payment.status != 0 AND tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."' group by tbl_payment.pay_id
    			");
    			$stms1->execute();
    			$row_count1 = $stms1->rowCount();
                ?><?php $totaAMT=$row_count33['sm']-$rows['sm'] ?> 
                    <tr>
                    <td rowspan="<?php echo $row_count1;?>"><?php echo $row_count21['rent_date'];?></td>
                    <td rowspan="<?php echo $row_count1;?>"><?php echo number_format($row_count33['sm']);?></td>
                    <?php $rows1 = $stms1->fetch(PDO::FETCH_ASSOC);  ?>
                    
                    <td><?php 
                      $sqla=$conn->prepare("SELECT * FROM tbl_tenants WHERE ten_id='".$row_count2['ten_id']."'");
                    $sqla->execute();
                    $rowa=$sqla->fetch();
                    
                    $ref=$rowa['ten_ref_no'];
                   
                  
                   	 $sqla1=$conn->prepare(" SELECT SUM(invoice_fees) AS invoice_fees FROM tbl_invoice WHERE inv_ref_no='".$ref."' ");
                    $sqla1->execute();
                    $row = $sqla1->fetch(PDO::FETCH_ASSOC);
                    $sum = $row['invoice_fees'];
                   
                   if($sum==0){
                       
                       echo 'never invoiced';
                       
                   }
                   else{
                      echo $sum ; 
                       
                   }
                 
                    ?></td>
                    <td>
                        
                        <?php
                        
                        $sqla12=$conn->prepare(" SELECT SUM(pay_amount) AS pay_amount FROM tbl_payment WHERE pay_ref_no='".$ref."' ");
                    $sqla12->execute();
                    $row2 = $sqla12->fetch(PDO::FETCH_ASSOC);
                    $sum2 = $row2['pay_amount'];
                   
                   if($sum2==0){
                       
                       echo 'never payed';
                       
                   }
                   else{
                      echo $sum2 ; 
                       
                   }
                        ?>
                        
                    </td>
                    
                    <td>
                        
                        <?php 
                        if($sum>0 && $sum2>0){
                            
                            echo $sum-$sum2;
                        }
                        
                        else{
                            
                            echo 'not clear';
                            
                        }
                        
                        ?>
                        
                        
                    </td>
                    
                    <td><?php echo number_format($row_count33s['sm']);?></td>
                    <td><?php echo number_format($row_count33l['invoice_fees']);?><div class="card d-inline-flex mb-2"><br><div class="card-body bg-light small py-2 px-3"> Description: <?php echo $row_count33l['inv_desc'];?>. </div></div></td>
                    <td><?php echo number_format($row_count11['pay_amount']);?><div class="card d-inline-flex mb-2"><br><div class="card-body bg-light small py-2 px-3"> Description: <?php echo $row_count11['pay_description'];?>. </div></div></td>
                    
    			   
                </tr>
        </tbody>
    </table>
        </div> 
       </div>
       </div>
       </div>
       
       <div class="col-md-12 col-lg-4 mb-4">
                  <div class="card timeline shadow">
                    <div class="card-header">
                      <strong class="card-title">Messaging</strong>
                      <a class="float-right small text-muted" href="#!"></a>
                    </div>
                    <div class="card-body">
                      <div class="form-group mb-3">
                        <label for="example-textarea">Message</label>
                        <textarea class="form-control" name="sms"  id="sms" rows="4"><?php echo "Mukiriya ufite umwenda ungana ".number_format($totaAMT)." "?></textarea>
                        <input type="hidden"  name="tenant" id="tenant" value="<?php echo $row_count2['ten_phone']; ?>">
                        <input type="hidden"  name="sender" id="sender" value="<?php echo $cmp_MSG; ?>">
                      </div>
                      <button type="button" class="btn mb-2 btn-outline-info btn-sm" id="send">Send</button>
                    <div id="displ2"></div>
                    </div> <!-- /.card-body -->
                  </div> <!-- / .card -->
                </div>
        </div>
    <?php } }?>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
<script>
$(document).ready(function(){
    $("#search-box").keyup(function(){
        $.ajax({
        type: "POST",
        url: "load_slip_search_result2",
        data:'keyword1='+$(this).val(),
        beforeSend: function(){   
        },
        success: function(data){
            $("#suggesstion-box").show();
            $("#suggesstion-box").html(data);
            $("#search-box").css("background","#FFF");
             $("#result-form-search").hide();
             $("#edit_education").hide();
             
        }
        });
    });
      $("#train_save").click(function (){
         //$("#edit_education").html(data);
        // var v = $("#search-box").val(); 
         var edu = $("#edit_ed").val();
        window.location.href = "indv-msg?edit_education=" + edu  + '&keyword1=' +edu;
        
        return false;
        $("#editaaa").hide();
    });
    
    $("#hrdSearch").click(function (){
        var v = $("#search-box").val(); 
        window.location.href = "indv-msg?keyword1=" + v;
        return false;
       
    });
   
    
});

function selecttbl_admission2(val) {
    $("#search-box").val(val);
    var d= $("#search-box").val();
     window.location.href = "index?file=indv-msg&keyword1=" + d;
    $("#suggesstion-box").hide();
}


$(document).ready(function () {
        $("#send").click(function () {
			var sms = $('#sms').val();
			var tenant = $('#tenant').val();
			var sender = $('#sender').val();
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>'); 
                $.get('send-sms-indv.php?&sms=' + sms + '&tenant=' + tenant + '&sender=' + sender, function (data){
             $("#displ2").html(data);
             $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });
});
</script>   