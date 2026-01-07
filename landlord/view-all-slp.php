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
      <?php 
      if(!empty($_REQUEST['keyword1'])){
          
    	$stms = $db->prepare("SELECT * FROM  tbl_payment
        WHERE tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."' AND tbl_payment.status != 0 and pay_user='".$company."' group by tbl_payment.pay_due_date ");
    	$stms->execute();
    			
    	$row_count = $stms->rowCount();
    	
    	$stms2 = $db->prepare("SELECT * FROM  tbl_tenants WHERE ten_ref_no='".$_REQUEST['keyword1']."'");
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
        <!--<a href="view_receipt?ss=<?php echo $_REQUEST['keyword1'];?>;?>" target="_blank"> -->
        <!--    <label class="btn btn-primary btn-sm text-white"><i class="fa fa-file-excel-o"></i> Receipt</label>-->
        <!--</a>-->
        <a href="excel_single_slp?keyword1=<?php echo $_REQUEST['keyword1'];?>&ctmr=<?php echo $row_count2['te_names'];?>" target="_blank"> 
            <label class="btn btn-success btn-sm text-white"><i class="fa fa-file-excel-o"></i> Export</label>
        </a>
        </div>
        <div class="card-header">
          <strong class="card-title"><?php echo $row_count2['te_names'];?></strong>
          <a class="float-right small text-muted" href="#!"></a>
        </div>
        <div class="table-responsive">
        <table class="table table-bordered table-striped mb-none" id="" style="font-size:11px;">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Ref</th>
                     <th>Date</th>
                     <th>Slip No</th>
                     <th>Bank Name</th>
                     <th>Description</th>
                     <th>Amount</th>
                     
                     <?php
                     if($company ==13){
                        ?>
                        <th>Actions</th>
                        <?php
                     }
                     ?>
                     
                </tr>
            </thead>
            <tbody>
                <?php
               $tot=0;
    			
    			while ($rows = $stms->fetch(PDO::FETCH_ASSOC)):
    			    $pay_id= $rows['pay_id'];
    			    $pay_due_date= $rows['pay_due_date'];
    			    $i++;
    			    
    			    $stms1 = $db->prepare("SELECT * FROM  tbl_payment
    	            INNER JOIN `tbl_bank` ON tbl_payment.bank_id=tbl_bank.bank_id 
                    INNER JOIN tbl_tenants ON tbl_payment.pay_ref_no=tbl_tenants.ten_ref_no
                    WHERE tbl_payment.pay_due_date='".$pay_due_date."' AND tbl_payment.status != 0 AND tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."' group by tbl_payment.pay_id");
        			$stms1->execute();
        			$row_count1 = $stms1->rowCount();
                ?>
                    <tr>
                        <td rowspan="<?php echo $row_count1;?>"><?php echo $rows['pay_ref_no'];?></td>
                    <td rowspan="<?php echo $row_count1;?>"><?php echo $i;?></td>
                    <td rowspan="<?php echo $row_count1;?>"><?php echo $rows['pay_due_date'];?></td>
                    
                     
                    <?php 
                    while ($rows1 = $stms1->fetch(PDO::FETCH_ASSOC)):  ?>
                    
                    <td ><?php echo $rows1['slip_no'];?></td>
                    <td><?php echo $rows1['bank_name'];?></td>
                    <td><?php echo $rows1['pay_description'];?></td>
                    <td>
                    <?php 
					echo $rows1['pay_amount']/$rows1['c_value']; $tot =$tot + ($rows1['pay_amount']/$rows1['c_value']);
                    ?>
                    </td>
                    <?php
                    if($company ==13){
                        ?>
                        <td>
                            <a href="print_receipt?payId=<?php echo $rows['pay_id'];?>&reference=<?php echo $rows['pay_ref_no'];?>" class="btn btn-success btn-sm text-white" target="_blank" title="Download Receipt"><i class="fe fe-file"> Receipt</i></a>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                
            <?php  
            endwhile;
            endwhile;?>
            <tr><td><b>Total:</b></td><td colspan="5"></td><td><b><?php 
             if($currency_id==2){
                  
                    echo ($tot)." "." "."usd";
                    
                }
                else{
                    echo number_format($tot)." "."<b>Rwf</b>";
                }
            
            
            //echo number_format($tot); ?></b></td></tr>
        </tbody>
    </table>
        </div>
    <?php }else{?>
        <br />
		<center>
		<div class="alert alert-success" role="alert" style="width:50%;">
		<strong>
		    <!--<i class="fa fa-fw fa-check-square"></i> -->
		</strong> No Data for this Car.
		</div>
		</center><br />
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
        url: "load_slip_search_result",
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
        window.location.href = "view_payments?edit_education=" + edu  + '&keyword1=' +edu;
        
        return false;
        $("#editaaa").hide();
    });
    
    $("#hrdSearch").click(function (){
        var v = $("#search-box").val(); 
        window.location.href = "view_payments?keyword1=" + v;
        return false;
       
    });
   
    
});

function selecttbl_admission(val) {
    $("#search-box").val(val);
    var d= $("#search-box").val();
     window.location.href = "index?file=tenant-payments&keyword1=" + d;
    $("#suggesstion-box").hide();
}
</script>  