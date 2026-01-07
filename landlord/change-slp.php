<style>
    .modal { display:none; }
    .modal-title {color: #111;}
    td {
        font-size: 12px;
    }
    .title{
        margin:10px;
        font-size:15px;
    }
    .form-group{
        display: inline-flex;
    }
</style>
<?php
    //  if(ISSET($_POST['cancel'])){
    //      try{
    //          $cancel = $_POST['cancel1'];
    //          $pay_id = $_POST['pay_id'];
    //          $slip= $_POST['slip'];
    //          $amount = 0;
    //          $status = 0;
    //          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             
    //         //  $sql = "UPDATE `tbl_payment` SET `pay_amount` = '$amount', `pay_description` = '$cancel', `status` = '$status' WHERE `pay_id` = '$pay_id'";
    //          $sql = "DELETE FROM tbl_payment WHERE `pay_id` = '$pay_id'";
    //          $conn->exec($sql);
   
    //          $msge = "$slip Slip Successfully Cancelled";
    //          echo'<meta http-equiv="refresh"'.'content="2;URL=index?file=change-slp">';
             
    //      }catch(PDOException $e){
    //          echo $e->getMessage();
    //      }
    //  }
     ?>   
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
    	<div class="alert alert-danger" role="alert">
        <span class="fe fe-minus-circle fe-16 mr-2"></span> <?php echo $msge; ?> </div>
        <?php
           }
        ?>
 <div class="module">
 <ul class="nav nav-tabs">
    <li class="active"><a href="#" data-toggle="tab">Change Slip</a></li>
</ul>
<br>
</div><!--/.module-->
</div><!--/.content-->
</div><!--/.span9-->
<div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
        <br><br>
      <div class="form-row">
        <div class="form-group col-md-4">
        </div>
        <div class="form-group col-md-4">
          <label for="simple-select2">Due Date</label>
          <input type="date" class="form-control" name="date" id="date" aria-label="" aria-describedby="">
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      <!--<form method="POST" action="">-->
        <div name='display_specs' id='display_specs'>
            
            <?php   
            if(!empty($_REQUEST['date'])){
                
                ?>
                <div class="col-md-12">
  <center>    
 <div class="col-md-12 col-lg-12">
                  <div class="card shadow">
                    <div class="card-header">
                      <strong class="float-left card-title"><?php echo $_REQUEST['date']; ?></strong>
                      <a class="float-right small text-muted" href="#!">View all</a>
                    </div>
                    <div class="card-body my-n2">
                        <?php 
                         $slip_No =0;
                         $sql = $conn->prepare("SELECT * FROM `tbl_payment` 
                         INNER JOIN `tbl_bank` ON tbl_payment.bank_id=tbl_bank.bank_id 
                         INNER JOIN tbl_tenants ON tbl_payment.pay_ref_no=tbl_tenants.ten_ref_no 
                         WHERE ten_staff='".$company."' AND pay_due_date ='".$_REQUEST['date']."' AND tbl_payment.status != 0 ");
                         $sql->execute();
                         if($sql->rowCount()>0){
                         ?>
                      <table class="table table-striped table-hover table-borderless">
                        <thead>
                          <tr>
                            <th>#PayNo</th>
                             <th>Ref No</th>
                             <th>Tenant</th>
                             <th>Bank Name</th>
                             <th>Slip No</th>
                             <th>Description</th>
                             <th>Amount</th>
                             <th >Action</th>
                          </tr>
                        </thead>
                        <tbody>
                       <?php
            			    
                             while($fetch = $sql->fetch()){
                             $slip_No++;
                             
                             $tot_Pay = $tot_Pay+$fetch['pay_amount'];
                         ?> 
                          <tr>
                           <td><?php echo $fetch['trans_code']; ?></td>
                             <td><?php echo $fetch['pay_ref_no']; ?></td>
                             <td><?php echo $fetch['te_names']; ?></td>
                             <td><?php echo $fetch['bank_name']; ?></td>
                             <td><?php echo $fetch['slip_no']; ?></td>
                             <td><?php echo $fetch['pay_description']; ?></td>
                             <td><?php echo number_format($fetch['pay_amount']).".00"; ?></td>
                            <td>
                              <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle more-vertical" type="button" id="dr1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <span class="text-muted sr-only">Action</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dr1">
                                  <a href="#" data-toggle="modal" data-target=".modal-full<?php echo $fetch['pay_id']; ?>" style="color:#DC3545;">Cancel</a>
                                </div>
                              </div>
                            </td>
                          </tr>
                          
                          <?php  include 'cancel-order-modal.php';?>
                          
                          
                          <script>
            $(document).ready(function(){
          $("#cancelling_pay<?php echo $fetch['pay_id'] ?>").submit(function(e){
            e.preventDefault();
    
           var formdata = new FormData(this);
           var pay_id=<?php echo $fetch['pay_id'] ?>;
        //   alert(pay_id);
            
            $.ajax({
                url: "cancelling_pay.php?pay_id=" + pay_id,
                type: "POST",
                data: formdata,
                mimeTypes:"multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(formData){
               if(formData==1){
                   
                    pop_up_request(formData);
                $('#close_click').trigger('click');
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
    
    function pop_up_request(feedback) {
        jQuery(function validation(){
        swal("Done! ", "Payement Canceled!.", "warning", {
        button: "ok",
          });
        });
    }
                          </script>
                          <?php } ?>
                        </tbody>
                      </table>
                      <?php }else{ ?>
                      
                      <?php echo 'No Slip Today'; } ?>
                    </div>
                  </div>
                </div> <!-- Striped rows -->
 </center> 
</div>
                <?php
            }
            
            ?>
      <!--</form>-->
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->
<script type="text/javascript">
    $(document).ready(function () {
        $("#date").change(function () {
            var date_from = $('#date_from').val();
            var date200=$(this).val();
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            // $.get('load_slip_change?date=' + $(this).val(), function (data) {
            //  $("#display_specs").html(data);
            //     $('#loader').slideUp(910, function () {
            //         $(this).remove();
            //     });
            // });
             window.location.href = "?file=change-slp&date=" + date200;
        });

    });
</script>


