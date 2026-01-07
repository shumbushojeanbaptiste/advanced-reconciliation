       <style>
       
        .icon-rtl {
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
     border-radius: 20px;
     border: 1px solid #2d9fd9;
     color: #a0d18c;
     width: 300px;
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
    <?php
    
       if(ISSET($_POST['update_invoice'])){
         try{
             $invoice_id = $_POST['invoice_id'];
             $amount = $_POST['invoice_amount'];
             $invoice_date = $_POST['invoice_date'];
             $invoice_comment = $_POST['invoice_comment'];
             $keyword=$_POST['keyword'];
             
             
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             
             $sql = "UPDATE `tbl_invoice` SET `invoice_fees` = '$amount', `invoice_date` = '$invoice_date', `inv_desc` = '$invoice_comment' WHERE `invoice_id` = '$invoice_id'";
             $conn->exec($sql);
 
             
             $msg = "Updated Successfully";
             echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=change-invoice&keyword1='.$keyword.'">';
             
         }catch(PDOException $e){
             echo $e->getMessage();
         }
     }
     
     if(ISSET($_POST['cancel'])){
         try{
             $cancel = $_POST['cancel1'];
             $inv_id = $_POST['inv_id'];
             $slip= $_POST['invo_no'];
              $keyword=$_POST['keyword'];
             $amount = 0;
             $status = 0;
             $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             
            //  $sql = "UPDATE `tbl_payment` SET `pay_amount` = '$amount', `pay_description` = '$cancel', `status` = '$status' WHERE `pay_id` = '$pay_id'";
            //  $conn->exec($sql);
            	$sqld = $db->prepare("DELETE from `tbl_invoice` WHERE `invoice_id`='".$inv_id."' ");
		$sqld->execute();
   
             $msge = "$slip Invoice Successfully Cancelled";
             echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=change-invoice&keyword1='.$keyword.'">';
             
         }catch(PDOException $e){
             echo $e->getMessage();
         }
     }
     ?> 
<div class="row">
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
      <div id="resultedOK">
      <?php if(!empty($_REQUEST['keyword1'])){
          
    	$stms = $db->prepare("SELECT * FROM  tbl_payment
                 WHERE tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."'  
    			");
    			$stms->execute();
    			
    			$row_count = $stms->rowCount();
    	$stms2 = $db->prepare("SELECT * FROM  tbl_tenants
                 WHERE ten_ref_no='".$_REQUEST['keyword1']."'
    			");
    			$stms2->execute();
    			$row_count2 = $stms2->fetch();
    			$currency_id=$row_count2['currency_id'];
    			
    			  $li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
            $li_quIPaidNMoney->execute(); 
            $newDMoney=$li_quIPaidNMoney->fetch();
         $converted=$newDMoney['intoR'];
    			if($row_count2>0){
        ?>
        <div align="right">
            <?php  if($company==9){   ?>
        <a href="view-inv23?keyword1=<?php echo $_REQUEST['keyword1']; ?>&invdate=<?php echo $xxode; ?>" target="_blank"> 
        <?php } else if($company==6 || $company==7 || $company==8){  ?>
         
         <a href="view-inv2?keyword1=<?php echo $_REQUEST['keyword1']; ?>&invdate=<?php echo $xxode; ?>" target="_blank"> 
        <?php } ?>
            <!--<label class="btn btn-danger btn-sm text-white"><i class="fe fe-file"></i> Print invoice</label>-->
        </a>
        
             <?php  if($company==6 || $company==7 || $company==8){  ?>
         
          <a href="client_detail?keyword1=<?php echo $_REQUEST['keyword1']; ?>&invdate=<?php echo $xxode; ?>" target="_blank">
        
            <label class="btn btn-success btn-sm text-white"><i class="fe fe-file"></i> Invoice&Payment</label>
            <?php } ?>
        </a>
       
        </div>
        <div class="card-header">
          <strong class="card-title"><?php echo $row_count2['te_names'];?></strong>
          <a class="float-right small text-muted" href="#!"></a>
        </div>
        <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless" id="" style="font-size:11px;">
            <thead>
                <tr>
                    <th>No.</th>
                     <th>Date</th>
                     <th>Slip no</th>
                    <th>Bank name</th>
                     
                     
                     <th>Pay AMT</th>
                     <th>Description</th>
                     <!--<th>Paid AMT</th>-->
                     <!--<th>Bakance</th>-->
                     <th>Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                $ref=$_REQUEST['keyword1'];
                $sqlpay=$conn->prepare("SELECT * FROM tbl_payment WHERE status=1 and pay_ref_no='".$ref."'");
                $sqlpay->execute();
                
                while($row=$sqlpay->fetch()){
                    $a++;
                    ?>
                    
                    <tr>
                        <td><?php echo $a?></td>
                        <td><?php echo $row['pay_due_date']; ?></td>
                            <td><?php echo $row['slip_no']; ?></td>
                              <td><?php 
                              $qslbank=$conn->prepare("SELECT * FROM tbl_bank WHERE bank_id='".$row['bank_id']."'");
                                $qslbank->execute();
                                $rowbank=$qslbank->fetch();
                              
                              echo $rowbank['bank_name']; ?></td>
                                  <td><?php echo $row['pay_amount']; ?></td>
                                   
                                       <td><?php echo $row['pay_description']; ?></td>
                                       <td>
                                           
<a href="" data-toggle="modal" data-target="#update<?php echo $fetch_ten['ten_id']?>" class="edit_data"><i class="fe fe-edit"></i></a> &nbsp &nbsp &nbsp
<a href="?file=tenmgnt&delete_tenant=<?php echo $t_id;?>" onclick="if(!confirm('Do you really want to Delete This Tenant?'))return false;else return true;" title="Delete tenant"><i class="icon-trash"></i> Delete</a>

                                           
                                       </td>
                                        
                                             
                                             
                                  
                        </tr>
                    
                    <?php
                }
                
                ?>
                </tbody>
    </table>
        </div>
    <?php }else{?>
        <br />
		<center>
		<div class="alert alert-success" role="alert" style="width:50%;">
		<strong>
		    <!--<i class="fa fa-fw fa-check-square"></i> -->
		</strong> No Data .
		</div>
		</center><br />
		<?php } }?>
		</div>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
<script>
$(document).ready(function(){
    $("#search-box").keyup(function(){
        $.ajax({
        type: "POST",
        url: "load_slip_search_result22",
        data:'keyword1='+$(this).val(),
        beforeSend: function(){
        },
        success: function(data){
            $("#suggesstion-box").show();
            $("#suggesstion-box").html(data);
            $("#search-box").css("background","#FFF");
             $("#result-form-search").hide();
             $("#resultedOK").hide();
             
        }
        });
    });
      $("#train_save").click(function (){
         //$("#edit_education").html(data);
        // var v = $("#search-box").val(); 
         var edu = $("#edit_ed").val(); 
        window.location.href = "pay-tracker?edit_education=" + edu  + '&keyword1=' +edu;
        
        return false;
        $("#editaaa").hide();
    });
    
    $("#hrdSearch").click(function (){
        var v = $("#search-box").val(); 
        window.location.href = "pay-tracker?keyword1=" + v;
        return false;
       
    });
   
    
});

function selecttbl_admission2(val) {
    
    $("#search-box").val(val);
    var d= $("#search-box").val();
     window.location.href = "index?file=pay-tracker&keyword1=" + d;
    $("#suggesstion-box").hide(); 
    
}
</script>  