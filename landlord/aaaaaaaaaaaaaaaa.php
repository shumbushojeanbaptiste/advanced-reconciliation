 <div class="col-12">
              <?php
                
    			$date_year = date('Y-m');
                $sql_us = $db->prepare("SELECT SUM(invoice_fees) as sm FROM `tbl_invoice` WHERE DATE_FORMAT(invoice_date, '%Y-%m') ='".$date_year."'");
                $sql_us->execute();
                $fetch_us = $sql_us->fetch();
                
                $sql_us1 = $db->prepare("SELECT SUM(pay_amount) as sm FROM `tbl_payment` WHERE DATE_FORMAT(pay_due_date, '%Y-%m') ='".$date_year."'");
                $sql_us1->execute();
                $fetch_us1 = $sql_us1->fetch();
                
                 $sql12 = $conn->prepare("SELECT SUM(invoice_fees) as sm FROM `tbl_invoice`");
                $sql12->execute();
                $sum = $sql12->fetch();
                
                 $sql122 = $conn->prepare("SELECT SUM(pay_amount) as sm FROM `tbl_payment`");
                $sql122->execute();
                $sum2 = $sql122->fetch();
                ?>
              <div class="row">
                <div class="col-md-6 col-xl-4 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-chevrons-up text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="small text-muted mb-0">All Invoices</p>
                          <span class="h3 mb-0"><?php echo number_format($sum['sm']); ?></span>
                          <!--<span class="small text-success">+16.5%</span>-->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-4 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-chevrons-down text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="small text-muted mb-0">All Payments</p>
                          <span class="h3 mb-0"><?php echo number_format($sum2['sm']); ?></span>
                          <!--<span class="small text-success">+16.5%</span>-->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-4 mb-4">
                  <div class="card shadow bg-primary text-white border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary-light">
                            <i class="fe fe-16 fe-filter text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="small text-muted mb-0">Balance</p>
                          <span class="h3 mb-0 text-white"><?php echo number_format($sum['sm']-$sum2['sm']); ?></span>
                          <!--<span class="small text-muted">+5.5%</span>-->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> <!-- end section -->
              <div class="row">
                <!-- Recent orders -->
                <div class="col-md-8">
                  <div class="card shadow eq-card">
                    <div class="card-header">
                      <strong class="card-title">Invoices & Payments</strong>
                      <div class="col-sm-3 float-right">
				      <!--<select name="year_id"  id="year_id" class="form-control select2" tabindex="-1" required onchange="mylang12(this.value)" style="width:100%;" placeholder="Choose ...">-->
				      <select name="year_id" id="year_id" class="form-control select2" id="simple-select2" required style="width:100%;" onchange="mylang12(this.value)">
    				<option></option>
    				<?php
    					$year = 2020;
    					$date_month = date('m');
    					$c = $date_year;
    						while ( $year <=  $c)
    					{
    					
    					if ($date_year==$year)
    						{
    						?>
    						<option value="<?php echo $date_year;?>" selected><?php echo $year;?></option>
    						<?php
    						}
    						else
    						{
    						?>
    						<option value="<?php echo $year;?>"><?php echo $year;?></option>
    						
    						<?php
    						}
    					    $year++;
    						
    					}
    					?>
    			</select>
				
				      <!--<select name="year_id"  id="year_id" class="form-control select2" tabindex="-1" required onchange="mylang12(this.value)" style="width:100%;" placeholder="Choose ...">-->
				    <select name="month_Id"  id="month_Id" class="form-control select2" tabindex="-1" required style="width:100%;" placeholder="Choose ...">
				        <?php $i = 1;
    						$month = 12;
    						$date_month = date('m');
    						$date_year = date('Y');
    						//$date = date('Y-m-d');
    						?>
    					   <option></option>
    						<?php
    						
    							while ($i <= $month)
    						{
    						$i = str_pad($i, 2, 0, STR_PAD_LEFT);
    							if($date_month==$i)
    								{
    								?>
    								<option value="<?php echo $date_month;?>" selected><?php echo date('F', mktime(0, 0, 0, $i, 1))?></option>
    								
    								<?php
    								break;
    								}
    								else
    								
    								{
    								?>
    								<option value="<?php echo $i;?>"><?php echo date('F', mktime(0, 0, 0, $i, 1))?></option>
    								
    								<?php
    								
    								}
    							$i++;				
    						}
			    
			    
			
			
			?>
				   </select>
	           	</div>
                    </div>
                    <div class="card-body" id="disp1">
                      <table class="table table-hover table-borderless table-striped mt-n3 mb-n1">
                        <thead>
                          <tr>
                            <th>NO</th>
                            <th>Name</th>
                            <th>Balance</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <th scope="col">Invoice</th>
                            <td><?php echo number_format($fetch_us['sm']); ?></td>
                            <td><span class="dot dot-lg bg-warning mr-2"></span></td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <th scope="col">Payment</th>
                            <td><?php echo number_format($fetch_us1['sm']); ?></td>
                            <td><span class="dot dot-lg bg-success mr-2"></span></td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <th scope="col">Balance</th>
                            <td><?php if($fetch_us['sm']>$fetch_us1['sm']){ echo number_format($fetch_us['sm']-$fetch_us1['sm']); ?></td>
                            <td><span class="dot dot-lg bg-primary mr-2"></span></td> <?php }else{  ?> <span class="text-danger"><?php echo number_format($fetch_us1['sm']-$fetch_us['sm']);?></span> 
                            <td><span class="dot dot-lg bg-danger mr-2"></span></td> <?php } ?>
                          </tr>
                        </tbody>
                      </table>
                    </div> <!-- .card-body -->
                    <div id="loadd" ></div>
                  </div> <!-- .card -->
                </div> <!-- / .col-md-8 -->
                <!-- Recent Activity -->
                <div class="col-md-4">
                  <div class="card shadow eq-card timeline">
                    <div class="card-header">
                      <strong class="card-title">Best 10 Tenant Pay well</strong>
                      <a class="float-right small text-muted" href="#!"><?php echo $date_year; ?></a>
                    </div>
                    <div class="card-body" data-simplebar style="height: 360px; overflow-y: auto; overflow-x: hidden;">
                         <?php
                
                			$date_year = date('Y');
                             $sql_us11 = $db->prepare("SELECT *,SUM(pay_amount) as sm FROM `tbl_payment`
                             INNER JOIN tbl_tenants ON tbl_tenants.ten_ref_no =tbl_payment.pay_ref_no group by pay_ref_no order by sm desc limit 10");
                            $sql_us11->execute();
                            while($fetch_us11 = $sql_us11->fetch()){
                                $sql_us134 = $db->prepare("SELECT SUM(invoice_fees) as sm FROM `tbl_invoice` WHERE inv_ref_no= '".$fetch_us11['pay_ref_no']."' AND DATE_FORMAT(invoice_date, '%Y') ='".$date_year."'");
                                $sql_us134->execute();
                                $fetch_us134 = $sql_us134->fetch();
                            $a++;
                        ?>
                      <div class="pb-3 timeline-item">
                          <span class="circle circle-sm bg-success h3 mb-0"><?php echo $a; ?></span>
                        <div class="pl-5">
                          <div class="mb-1 small"><strong><?php echo $fetch_us11['te_names']; ?></strong><span class="text-muted mx-2"><br>Payment: </span><strong><?php echo number_format($fetch_us11['sm']); ?></strong></div>
                          <p class="small text-muted">Invoiced: <span class="badge badge-light"><?php echo number_format($fetch_us134['sm']); ?></span>
                          </p>
                        </div>
                      </div>
                      <?php } ?>
                      
                    </div> <!-- / .card-body -->
                  </div> <!-- / .card -->
                </div> <!-- / .col-md-3 -->
              </div> <!-- end section -->
            </div> <!-- .col-12 -->