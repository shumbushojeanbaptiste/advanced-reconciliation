<?php



 if(isset($_POST['edit-next'])){
   $date2=$_POST['date2'];
   $pnext_id=$_POST['pnext_id'];
   
   
   
   
//   $sqledit=$conn->prepare("UPDATE tbl_ten_contracts SET c_rent_AMT='".$c_amount."',c_amaunt='".$tot_c_amount."',coution_amaunt='".$coution."',c_end_date='".$date2."' where contr_id='".$contract_id."'");
//   $sqledit->execute();

 $sqledit=$conn->prepare("UPDATE tbl_next_pay SET next_date='".$date2."' where pnext_id='".$pnext_id."'");
   $sqledit->execute();
   ?>
    
   <script>
               jQuery(function validation(){
        swal("Well done! ", "payment date Updated!. ", "success", {
        button: "ok",
          });
        });
        </script>
   <?php
      }
?>      
      
      
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
      <?php 
        if(!empty($_REQUEST['keyword1'])){
            $stms2 = $db->prepare("SELECT * FROM  tbl_tenants WHERE ten_ref_no='".$_REQUEST['keyword1']."'");
    			$stms2->execute();
    			
    			$row_count2 = $stms2->fetch();
    			$ten_id=$row_count2['ten_id'];
    			
            $stms = $db->prepare("SELECT * FROM   tbl_next_pay WHERE tbl_next_pay.tenant_id='".$ten_id."'  ");
    			$stms->execute();
    			$row_count = $stms->rowCount();
    	        
    	        
    			
    			$currency_id=$row_count2['currency_id'];
    		    $li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
                $li_quIPaidNMoney->execute(); 
                $newDMoney=$li_quIPaidNMoney->fetch();
                $converted=$newDMoney['intoR'];
    			if($row_count2>0){
                    ?>
     
        <div class="card-header">
          <strong class="card-title"><?php echo $row_count2['te_names'];?></strong>
          <a class="float-right small text-muted" href="#!"></a>
        </div>
        <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless" id="" style="font-size:11px;">
            <thead>
                <tr>
                    <th>No.</th>
                     <th>Contract Date</th>
                     <!--<th>Received Phone</th>-->
                     <!--<th>Invoice Type</th>-->
                     <th>Action</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
               $tot=0;
    			
    			while ($rows = $stms->fetch(PDO::FETCH_ASSOC)):
    			    $pay_id= $rows['invoice_id'];
    			    $pay_due_date= $rows['invoice_date'];
    			    $i++;
    			    
                    ?>
                    <tr>
                    <td ><?php echo $i;?></td>
                    <td ><?php 
                    
                    $orgDate = $rows['next_date']; 
                    $newDate = date("d F Y", strtotime($orgDate));
                    echo $newDate;?></td>
                
                    
                    
                 
                    <td>
                        <button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted sr-only">Action</span>
                              </button>
           <div class="dropdown-menu dropdown-menu-right">
                                  
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target=".modal-edit<?php echo $rows['pnext_id']; ?>"><i class="fe fe-edit"></i> Edit date</a> 
                                <!--<a class="dropdown-item" href="#">Assign</a>-->
                              </div>
         <?php  include 'edit-next-modal.php';?>
        <!-- <a href="index?file=bill-link&id=<?php echo $rows['link_id']?>">-->
        
        <!--    <label class="btn btn-success btn-sm text-white"><i class="fe fe-file"></i> Resend link</label>-->
            <?php //} ?>
        <!--</a>  -->
        <!--            </td>-->
                    
    			    
                </tr>
               
                
            <?php  endwhile;?>
            
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
        url: "load_slip_search_result2",
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
        window.location.href = "manage-review?edit_education=" + edu  + '&keyword1=' +edu;
        
        return false;
        $("#editaaa").hide();
    });
    
    $("#hrdSearch").click(function (){
        var v = $("#search-box").val(); 
        window.location.href = "manage-review?keyword1=" + v;
        return false;
       
    });
   
    
});

function selecttbl_admission2(val) {
    
    $("#search-box").val(val);
    var d= $("#search-box").val();
     window.location.href = "index?file=manage-review&keyword1=" + d;
    $("#suggesstion-box").hide(); 
    
}
</script>  