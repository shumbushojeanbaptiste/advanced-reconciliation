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
  
    <div class="card-body">
        <br><br>
      <div class="form-row">
        <div class="form-group col-md-3">
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
      <?php if(!empty($_REQUEST['keyword1'])){
          
    	$stms = $db->prepare("SELECT * FROM  tbl_payment
                 WHERE tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."' AND tbl_payment.status != 0 group by tbl_payment.pay_due_date 
    			");
    			$stms->execute();
    			
    			$row_count = $stms->rowCount();
    	$stms2 = $db->prepare("SELECT * FROM  tbl_tenants
                 WHERE ten_ref_no='".$_REQUEST['keyword1']."'
    			");
    			$stms2->execute();
    			$row_count2 = $stms2->fetch();
    			$ten_id=$row_count2['ten_id'];
    			if($row_count2>0){
    			    
    			$stms2Room = $db->prepare("SELECT * FROM  tbl_assign WHERE tent_id='".$row_count2['ten_id']."' and tbl_assign.assgn_status =1");
    			$stms2Room->execute();
    			$dataC=$stms2Room->rowCount();
        ?>
        <!--<div align="right">-->
        <!--<a href="excel_single_slp?ss=<?php echo $_REQUEST['keyword1'];?>&ctmr=<?php echo $row_count2['te_names'];?>" target="_blank"> -->
        <!--    <label class="btn btn-success btn-sm text-white"><i class="fa fa-file-excel-o"></i> Export</label>-->
        <!--</a>-->
        <!--</div>-->
        <div class="card-header">
          <strong class="card-title"><?php echo $row_count2['te_names']."[ <small>ROOM: "; 
          if($dataC==0){
              echo "no Room ";
          }
          else{
          while($rooT=$stms2Room->fetch()){
              
             $stms2Roomss = $db->prepare("SELECT * FROM  tbl_rooms WHERE room_id='".$rooT['room_id']."'");
    		$stms2Roomss->execute();
    			$roomName=$stms2Roomss->fetch();
            echo $roomName['room_no'].",";
          
          }
          } ?>           <?php echo "</small>]"; ?></strong>
          <a class="float-right small text-muted" href="#!"></a>
        </div>
    <form class="form-horizontal row-fluid" id="add_tenant" method="post" autocomplete="off" action="" enctype="multipart/form-data">
               <div class="row">
                      <!-- <div class="col-md-3">-->
             	        <!--<label class="control-label" for="basicinput">Company Names </label>-->
             	        <!--</div>-->
             	      
                <div class="col-md-9">
                   
                    <div class="form-group mb-10">
                  <input type="hidden" name="tenant_id" id="tenant_id" placeholder="Names" value="<?php echo $ten_id; ?>">
					<input type="hidden" name="tename" id="tename" placeholder="Names" value="<?php echo $row_count2['te_names']; ?>" class="form-control" required readonly>
                     </div>
                </div>
                </div>
                  <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Contract Room <span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-4">
                   
                    <div class="form-group mb-10">
                    
                      <select  class="form-control select2-multi" id="multi-select2" name="c_room" data-placeholder="Choose Contracted..."  style="width:100%;" required>
                                     <!--<option value=""></option>-->
                                         <?php
                                             $stmt = $db->query('SELECT *FROM tbl_rooms
                                             INNER JOIN tbl_assign on tbl_assign.room_id=tbl_rooms.room_id
                                             WHERE tbl_assign.tent_id="'.$row_count2['ten_id'].'" and tbl_assign.assgn_status =1 ');  
                                            //   $stmt = $db->query('SELECT * FROM `tbl_rooms` WHERE rm_status = 1  AND company_id = "'.$company.'" ;');
                                             
                
                                                    try
                                                    {
                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                <option value="<?php echo $row['room_id']; ?>"><?php echo $row['room_no']; ?></option>
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
                </div>
                </div>
                 <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Starting Date <span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-4">
                   
                    <div class="form-group mb-10">
                    
                   <input type="date" name="from_date" id="from_date"  class="form-control" required >
                     </div>
                </div>
                </div>
                 <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Contract Period 	<span class="help-inline" style="color:red">*</span></label>
             	        </div>
                <div class="col-md-4">
                   
                    <div class="form-group mb-10">
                    
                   <input type="number" name="contract_period" id="contract_period" placeholder="Write number of month..." class="form-control" required>
                     </div>
                </div>
                </div>
                <div id="display_end_date"></div>
		          <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">ADVANCE Month </label>
             	        </div>
                <div class="col-md-4">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="advance_month" id="advance_month" placeholder="Write Advance Month..." class="form-control" >
                     </div>
                </div>
                </div>
		        <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">CAUTION money </label>
             	        </div>
                <div class="col-md-4">
                   
                    <div class="form-group mb-10">
                    
                   <input type="text" name="coution" id="phone" placeholder="Write Coution Money..." class="form-control" >
                     </div>
                </div>
                </div>
                    <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Payment Interval </label>
             	        </div>
                <div class="col-md-4">
                   
                    <div class="form-group mb-10">
                    
                   <!--<input type="text" name="interval" id="phone" placeholder="Write interval Month..." class="form-control" >-->
                    <select id="interval" class="form-control select2" name="interval" data-placeholder="Choose interval Month..." style="width:100%" required >
                             <option></option>
                             <option value="1">One Month</option>
                             <option value="2">Two Month</option>
                             <option value="3">Three Month</option>
                             <option value="4">Fourth Month</option>
                              <option value="5">Five Month</option>
                             <option value="6">Six Month</option>
                             <option value="7">Seven Month</option>
                            
                             <option value="12">Twelve Month</option>
                            
                              	
                            </select>
                     </div>
                </div>
                </div>
                    <div class="row">
                       <div class="col-md-3">
             	        	<label class="control-label" for="basicinput">Attach Document </label>
             	        </div>
                <div class="col-md-4">
                   
                    <div class="form-group mb-10">
                    
                   <input type="file" name="files1[]" id="files1" placeholder="attach document file..." class="form-control" >
                     </div>
                </div>
                </div>
                
                <div class="row">
                       <div class="col-md-4">
             	        	<label class="control-label" for="basicinput"></label>
             	        </div>
                <div class="col-md-4">
                   
                    <div class="form-group mb-10">
                    
                    <button type="submit" name="insert_contract" id="insert" class="btn btn-primary">Save contract</button>
                    </div>
                </div>
                </div>
                </form>
    
    <?php }else
    {?>
        <br />
		<center>
		<div class="alert alert-success" role="alert" style="width:50%;">
		<strong>
		    <!--<i class="fa fa-fw fa-check-square"></i> -->
		</strong> No contract.
		</div>
		</center><br />
		<?php } }?>
    </div> <!-- /.card-body -->
   <!-- /.card -->
</div> <!-- /.col -->

        </div>
<script>
$(document).ready(function(){
    $("#search-box").keyup(function(){
        $.ajax({
        type: "POST",
        url: "load_slip_search_result100",
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

function selecttbl_admission100(val) {
    $("#search-box").val(val);
    var d= $("#search-box").val();
     window.location.href = "index?file=new_contract&keyword1=" + d;
    $("#suggesstion-box").hide();
}
</script> 

