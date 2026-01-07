<?php  
 require_once ("include/initialize.php");
//echo $_POST['paycode']."#".$_POST['payPassword'];

 $stms2 = $db->prepare("SELECT * FROM  tbl_tenants WHERE ten_ref_no='".$_POST['paycode']."'");
    			$stms2->execute();
    			$row_count2 = $stms2->fetch();
    			$data= $stms2->rowCount();
    			$names=$row_count2['te_names'];
    			$phone=$row_count2['ten_phone'];
    			$leader_name=$row_count2['leader_name'];
    			$ten_email=$row_count2['ten_email'];
    			$ten_staff=$row_count2['ten_staff'];
    			
    			 $CPN = $db->prepare("SELECT * FROM  tbl_company WHERE cpny_ID='".$ten_staff."'");
    			$CPN->execute();
    			$row_cCPN = $CPN->fetch();
    			$cpn_name=$row_cCPN['full_name'];
    			$phone=$row_cCPN['phone'];
    			
    			
?>



                <?php   if($data>0){ 
                
                
                ?>  
                  <div class="col-md-12 mb-4">
                  <div class="card profile shadow">
                    <div class="card-body my-4">
                      <div class="row align-items-center">
                      
                        <div class="col">
                          <div class="row align-items-center">
                            <div class="col-md-7">
                              <h4 class="mb-1"><?php echo $names; ?></h4>
                              <!--<p class="small mb-3"><span class="badge badge-dark">New York, USA</span></p>-->
                            </div>
                            <div class="col">
                            </div>
                          </div>
                          <div class="row mb-4">
                            <div class="col-md-7">
                              
                                 <div class="col">
                              <p class="small mb-0 text-muted"><?php echo $leader_name; ?></p>
                              <p class="small mb-0 text-muted"><?php echo $ten_email ?></p>
                              <p class="small mb-0 text-muted"><?php  echo $phone; ?></p>
                            </div>
                            </div>
                            <div class="col">
                                
                              <p class="small mb-0 text-muted"><b><?php echo $cpn_name ?> </b></p>
                              <!--<p class="small mb-0 text-muted">P.O. Box 464, 5975 Eget Avenue</p>-->
                              <p class="small mb-0 text-muted"><b><?php  echo $phone ?></b></p>
                              
                            </div>
                          </div>
                          <?php if($ten_staff==9){ ?>
                          <div class="row align-items-center">
                            <div class="col-md-7 mb-2">
                              <span class="small text-muted mb-0">Making payment now</span>
                            </div>
                            <div class="col mb-2">
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paynow">Next <i class="fe fe-arrow-right fe-16 ml-2"></i></button>
                              <!--<button type="button" class="btn btn-secondary"></button>-->
                            </div>
                            
                          </div>
                          <div id="spenn_info"></div>
                          <?php }  else {?>
                          <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Soory!</strong> activate your code. <button type="submit" onclick="myClosing()" class="close"  aria-label="Close" >
                      <span aria-hidden="true" id="close">×</span>
                    </button>
                  </div>
                          <?php } ?>
                        </div>
                      </div> <!-- / .row- -->
                    </div> <!-- / .card-body - -->
                  </div> <!-- / .card- -->
                </div> <!-- / .col- -->
                
                <?php } else{ ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Wrong!</strong> incorect pin or code. <button type="submit" onclick="myClosing()" class="close"  aria-label="Close"  >
                      <span aria-hidden="true" id="close">×</span>
                    </button>
                  </div>
                <?php } ?>
                
  <div class="modal fade" id="paynow" tabindex="-1" role="dialog" aria-labelledby="verticalModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="verticalModalTitle">Pay Rent</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                           <div class="col-md-12 mb-4">
                  
                      <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><img src="./assets/products/smlspn.jpg" alt="..." class="img-fluid rounded"> SPENN</a>
                        </li>
                        <!--<li class="nav-item">-->
                        <!--  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><img src="./assets/products/cogeb.jpg" alt="..." class="img-fluid rounded"> COGEBANK</a>-->
                        <!--</li>-->
                        
                         <li class="nav-item">
                          <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><img src="./assets/products/momopay.jpg" alt="..." class="img-fluid rounded"> MoMo</a>
                        </li>
                        <li class="nav-item">
                       <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false"><img src="./assets/products/cardpay.jpg" alt="..." class="img-fluid rounded"> CARD</a>
                        </li>
                      </ul>
                      <div class="tab-content mb-5" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"> 
                     <br>  
                       
                       <?php include'pay/spenn.php';  ?>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            
                            <!--Dear Customer, Now this service will be available on Cogebange Branches for Deposit rent through your code!-->
                            <?php  include'pay/momo.php'; ?>
                            </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                            
                             <?php include'pay/card.php'; ?>
                            </div>
                      </div>
                  
                </div>
                            
                          </div>
                        </div>
                      </div>
                      </div>
                     