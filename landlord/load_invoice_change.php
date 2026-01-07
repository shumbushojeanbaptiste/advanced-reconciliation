<?php
 require_once ("../include/initialize.php");

// get dates from form

// $date_from = $_REQUEST['date_from'];
// $date_to = $_REQUEST['date_to'];
?>
<br>
<div class="col-md-12">
  <center>    
 <div class="col-md-12 col-lg-9">
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
                         WHERE tbl_payment.pay_user = '".$company."' AND ten_staff='".$company."' AND pay_due_date ='".$_REQUEST['date']."' AND tbl_payment.status != 0 ");
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
                             <th width="14%">Action</th>
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