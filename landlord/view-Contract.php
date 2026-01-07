 <?php
   if(isset($_POST['edit-contract'])){
   $date2=$_POST['date2'];
   $coution=$_POST['coution'];
   $tot_c_amount=$_POST['tot_c_amount'];
   $c_amount=$_POST['c_amount'];
   $tename=$_POST['tename'];
   $contract_id=$_POST['contract_id'];
   $tenant_id=$_POST['tenant_id'];
   $interval=$_POST['interval'];
   
   $sqledit=$conn->prepare("UPDATE tbl_ten_contracts SET c_rent_AMT='".$c_amount."',c_amaunt='".$tot_c_amount."',coution_amaunt='".$coution."',c_end_date='".$date2."' where contr_id='".$contract_id."'");
   $sqledit->execute();
   ?>
   
   <script>
               jQuery(function validation(){
        swal("Well done! ", "Contract Updated!. ", "success", {
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
<div class="col-md-12"><br><br>
  
    <div class="card-body">
        <br><br>
      <div class="form-row">
        <div class="form-group col-md-3">
        </div>
        <div class="form-group col-md-4">
          <!--<label for="simple-select2">Due Date</label>-->
          <input type="text" class="form-control icon-rtl" name="acad" id="search-box1" placeholder="Search by Tenant Name or Phone">
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <div class="form-row">
        <div class="form-group col-md-4">
        </div>
        <div class="form-group col-md-4" id="suggesstion-box1"></div>
      </div> <!-- form-row -->
      <?php if(!empty($_REQUEST['keywordV'])){
          
    	$stms = $db->prepare("SELECT * FROM  tbl_payment
                 WHERE tbl_payment.pay_ref_no='".$_REQUEST['keywordV']."' AND tbl_payment.status != 0 group by tbl_payment.pay_due_date 
    			");
    			$stms->execute();
    			
    			$row_count = $stms->rowCount();
    	$stms2 = $db->prepare("SELECT * FROM  tbl_tenants WHERE ten_ref_no='".$_REQUEST['keywordV']."'
    			");
    			$stms2->execute();
    			$row_count2 = $stms2->fetch();
    			$ten_id=$row_count2['ten_id'];
    			if($row_count2>0){
    			    
    			 $stms2Room = $db->prepare("SELECT * FROM  tbl_assign
                 WHERE tent_id='".$row_count2['ten_id']."'
    			");
    			$stms2Room->execute();
    			$dataC=$stms2Room->rowCount();
    			$tnames1=$row_count2['te_names'];
        ?>
        <!--<div align="right">-->
        <!--<a href="excel_single_slp?ss=<?php echo $_REQUEST['keyword1'];?>&ctmr=<?php echo $row_count2['te_names'];?>" target="_blank"> -->
        <!--    <label class="btn btn-success btn-sm text-white"><i class="fa fa-file-excel-o"></i> Export</label>-->
        <!--</a>-->
        <!--</div>-->
        <div class="card-header">
          <strong class="card-title" align="center"><?php echo $row_count2['te_names'] ?></strong>
          <a class="float-right small text-muted" href="#!"></a>
        </div>
    
     <div class="card-body">
                      <div class="toolbar">
                        <form class="form">
                          <div class="form-row">
                            <div class="form-group col-auto mr-auto">
                              <label class="my-1 mr-2 sr-only" for="inlineFormCustomSelectPref1">Show</label>
                              <select class="custom-select mr-sm-2" id="inlineFormCustomSelectPref1">
                                <option value="">...</option>
                                <option value="1">12</option>
                                <option value="2" selected>32</option>
                                <option value="3">64</option>
                                <option value="3">128</option>
                              </select>
                            </div>
                            <div class="form-group col-auto">
                              <label for="search" class="sr-only">Search</label>
                              <input type="text" class="form-control" id="search1" value="" placeholder="Search">
                            </div>
                          </div>
                        </form>
                      </div>
                      <!-- table -->
                      <div class="table-responsive">
                        <table class="table table-hover" id="" style="font-size:11px;">
                        <thead>
                          <tr>
                           <th>#</th>
                            <th style="width:200px;">Room <?php // echo $company;?></th>
                             <th class="w-15" style="width:200px;">Contract Start Date</th>
                            <th class="w-15" style="width:200px;">Contract End Date</th>
                            <th>Contract Period</th>
                            <th class="w-15"  style="width:200px;">Advance Month</th>
                            <th class="w-15" style="width:200px;">Coution</th>
                            <th class="w-15" style="width:200px;">Contract AMT</th>
                            <th class="w-15" style="width:200px;">Total Rent AMT</th>
                            <th class="w-15" style="width:200px;">Advance End Date</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <?php  	$stms2CC = $db->prepare("SELECT *FROM  tbl_ten_contracts WHERE tenant_id='".$ten_id."' and 	cntr_status=1");
            			$stms2CC->execute();
            			$m_row= $stms2CC->rowCount();
            			if($m_row>0){  ?>
            		
                                <tbody>
                                <?php   $i=0;	
                                while($row_count2CC = $stms2CC->fetch()){ 
                                
                                $i++; ?>
                                  <tr>
                                      <td><?php echo $i; ?></td>
                                      
                                      <?php
                                      /**
                                       * select a room name
                                       * 
                                       */
                                       $room =$conn->prepare("select *from tbl_assign where tent_id ='".$row_count2CC['tenant_id']."' and company_id='".$company."'");
                                       $room->execute();
                                       $theRoom =$room->fetch();
                                       
                                    //   room name
                                      
                                       $roomName =$conn->prepare("select *from tbl_rooms where room_id in (select room_id from tbl_c_room where contr_id ='".$row_count2CC['contr_id']."') and company_id='".$company."'");
                                       $roomName->execute();
                                       $theRoomName =$roomName->fetch();                                    
                                    
                                      ?>
                                   <td>
                                        <?php echo $theRoomName['room_no'];?>
                                    </td>
                           
                            <td>
                              <p class="mb-0 text-muted"><?php echo $row_count2CC['c_start_date'] ?></p>
                              <!--<small class="mb-0 text-muted">Ap #331-7123 Lobortis Avenue</small>-->
                            </td>
                            <td>
                              <p class="mb-0 text-muted"><a href="#" class="text-muted"><?php echo $row_count2CC['c_end_date'] ?></a></p>
                              <!--<small class="mb-0 text-muted">Nigeria</small>-->
                            </td>
                            <td> <p class="mb-0 text-muted"><a href="#" class="text-muted"><?php echo $row_count2CC['c_month'] ?></a></p></td>
                            <td><small class="text-muted"><?php echo $row_count2CC['adv_month'] ?></small></td>
                            <td class="text-muted"><strong> <?php echo $row_count2CC['coution_amaunt'] ?></strong></td>
                             <td>
                              <p class="mb-0 text-muted"><strong><?php echo number_format($row_count2CC['c_amaunt']) ?></strong></p>
                              <small class="mb-0 text-muted">Frw</small>
                            </td>
                           <td>
                              <p class="mb-0 text-muted"><strong><?php echo number_format($row_count2CC['c_rent_AMT']) ?></strong></p>
                              <small class="mb-0 text-muted">Frw</small>
                            </td>
                          
                            <td> <p class="mb-0 text-muted"><a href="#" class="text-muted"><?php echo $row_count2CC['adv_end_date'] ?></a></p></td>
                            <td><button class="btn btn-sm dropdown-toggle more-horizontal" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted sr-only">Action</span>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right">
                                  <a class="dropdown-item" href="#" data-toggle="modal" data-target=".modal-change<?php echo $row_count2CC['contr_id']; ?>" ><i class="fe fe-edit"></i> contract addendum</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target=".modal-full<?php echo $row_count2CC['contr_id']; ?>" style="color:#DC3545;"><i class="fe fe-trash"></i> Cancel Contract</a>
                                <a class="dropdown-item" href="docs/<?php echo $row_count2CC['doc']; ?>" target="blank"><i class="fe fe-eye"></i> View Document</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target=".modal-edit<?php echo $row_count2CC['contr_id']; ?>"><i class="fe fe-edit"></i> Edit Contract</a> 
                                <!--<a class="dropdown-item" href="#">Assign</a>-->
                              </div>
                            </td>
                          </tr>
                           <?php  include 'cancel-contract-modal.php';?>
                           <?php  include 'change-contract-modal.php';?>
                            <?php  include 'edit-contract-modal.php';?>
                             <script>
            $(document).ready(function(){
          $("#cancelling_contr<?php echo  $row_count2CC['contr_id'] ?>").submit(function(e){
            e.preventDefault();
    
           var formdata = new FormData(this);
           var contract_id=<?php echo $row_count2CC['contr_id'] ?>;
        //   alert(pay_id);
            
            $.ajax({
                url: "cancelling_contract.php?contract_id=" + contract_id,
                type: "POST",
                data: formdata,
                mimeTypes:"multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(formData){
               if(formData==1){
                   
                    pop_up_request22(formData);
                $('#btnClick').trigger('click');
                    setTimeout(function(){// wait for 5 secs(2)
                    
           location.reload();
           
           // then reload the page.(3)
     }, 4000);
     
      //alert(sect);
       
    //   $("#citizen_list").load("citizen_list.php?sec_id=" + sect);
    // location.reload();
               }
               
               else{
                   
                 pop_up_NOT_Insert(formData);  
               }
                },error: function(){
                    alert("okey");  
                }
             });
          });
        
    });
    
    function pop_up_request22(feedback) {
        jQuery(function validation(){
        swal("Done! ", "Contract Canceled!.", "warning", {
        button: "ok",
          });
        });
    }
                          </script>
                       <?php }  ?>
                        </tbody>
                        <?php  }  else{ ?>
                        <tr><td colspan="10" align="center">No Contract</td></tr>
                        <?php } ?>
                      </table></div>
                     
                    </div>
    
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
    $("#search-box1").keyup(function(){
        $.ajax({
        type: "POST",
        url: "load_slip_search_result",
        data:'keyword1='+$(this).val(),
        beforeSend: function(){
        },
        success: function(data){
            $("#suggesstion-box1").show();
            $("#suggesstion-box1").html(data);
            $("#search-box1").css("background","#FFF");
             $("#result-form-search1").hide();
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
     window.location.href = "index?file=new_contract&keywordV=" + d;
    $("#suggesstion-box").hide();
}
</script> 

