        <style>
        
            #a{text-decoration: none;}
        </style>
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row align-items-center mb-2">
                <div class="col">
                  <h2 class="h5 page-title">Welcome 2! <?php  //echo $smspwd?></h2>
                </div>
              </div>
              <div class="row my-4">
                <div class="col-md-3">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                         <?php
                            $sql_rm1 = $db->prepare("SELECT count(*) as total_rooms FROM tbl_rooms  WHERE tbl_rooms.company_id = '".$company."' and side=1 and rm_status=1");
                            $sql_rm1->execute();
                            $fetch_rm1 = $sql_rm1->fetch();
                            $toral100=$fetch_rm1['total_rooms'];
                            ?>
                      <div class="row align-items-center">
                        <div class="col">
                           <a href="?file=all-rooms" id="a"> 
                          <small class="text-muted mb-1">Total Rooms</small>
                          <h3 class="card-title mb-0"><?php echo $fetch_rm1['total_rooms']; ?></h3></a>
                          <p class="small text-muted mb-0"><span class="fe fe-arrow-right fe-24 text-info"></span><span></span></p>
                        </div>
                        <!--<div class="col-4 text-right">-->
                        <!--  <span class="sparkline inlineline"></span>-->
                        <!--</div>-->
                      </div> <!-- /. row -->
                      
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                <div class="col-md-3">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                             <?php
                            // number of blocks
                            $sql_rm2 = $db->prepare("SELECT count(*) as rooms_avlble FROM tbl_rooms  WHERE tbl_rooms.company_id = '".$company."' AND availability=3 and side=1 and rm_status=1");
                            $sql_rm2->execute();
                            $fetch_rm2 = $sql_rm2->fetch();
                        ?>
                          <a href="?file=Arooms" id="a" > <small class="text-muted mb-1">Available Rooms</small>
                          <h3 class="card-title mb-0"><?php echo $fetch_rm2['rooms_avlble']; if(!empty($toral100)){$per2=$fetch_rm2['rooms_avlble']*100/$toral100;} ?></h3></a>
                          <p class="small text-muted mb-0"><span class="fe fe-arrow-up fe-24 text-success"></span><span><?php  echo number_format($per2)."%"; ?></span></p>
                        </div>
                        <!--<div class="col-4 text-right">-->
                        <!--  <span class="sparkline inlinepie"></span>-->
                        <!--</div>-->
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                <div class="col-md-3">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                           <?php
                            // number of blocks
                            
                            $sql_rm3 = $db->prepare("SELECT count(*) as rooms_occp FROM tbl_rooms  WHERE tbl_rooms.company_id = '".$company."' 
                            and room_id  in(SELECT room_id FROM tbl_assign where assgn_status=1) and availability =4  and side=1 order by room_no asc");
                            $sql_rm3->execute();
                            $fetch_rm3 = $sql_rm3->fetch();
                        ?>
                      <div class="row align-items-center">
                        <div class="col">
                           <a href="?file=rooms" id="a" ><small class="text-muted mb-1">Occupied Rooms</small>
                          <h3 class="card-title mb-0"><?php echo number_format(($fetch_rm1['total_rooms'])-($fetch_rm2['rooms_avlble'])); if(!empty($toral100)){$per3=$fetch_rm3['rooms_occp']*100/$toral100;} ?></h3></a>
                          <p class="small text-muted mb-0"><span class="fe fe-arrow-down fe-24 text-danger"></span><span><?php  echo number_format($per3)."%"; ?></span></p>
                        </div>
                        <!--<div class="col-4 text-right">-->
                        <!--  <span class="sparkline inlinebar"></span>-->
                        <!--</div>-->
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                <div class="col-md-3">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                           <?php
                            // number of blocks
                            
                            $sql_rm = $db->prepare("SELECT * FROM tbl_company  WHERE tbl_company.cpny_ID = '".$company."' ");
                            $sql_rm->execute();
                            $fetch_rm = $sql_rm->fetch();
                            
                            
                            $sql_rmR00 = $db->prepare("SELECT SUM(room_area) as aream FROM tbl_rooms  WHERE tbl_rooms.company_id = '".$company."' and availability=4");
                            $sql_rmR00->execute();
                            $fetch_rmR00 = $sql_rmR00->fetch();
                            $occpied=$fetch_rmR00['aream'];
                            
                        ?>
                      <div class="row align-items-center">
                        <div class="col">
                           <a href="?file=occup" id="a" ><small class="text-muted mb-1">Total Occupied Space</small>
                          <h3 class="card-title mb-0"><?php 
                        //   number_format($fetch_rm['space']-$occpied)
                          echo number_format($occpied)." "; ?>m<sup>2</sup></h3></a>
                          <p class="small text-muted mb-0"><span class="fe fe-arrow-down fe-24 text-danger"></span></p>
                        </div>
                        <!--<div class="col-4 text-right">-->
                        <!--  <span class="sparkline inlinebar"></span>-->
                        <!--</div>-->
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> 
              </div><!-- .row -->
            </div> <!-- .col-12 -->
           
           
            <div class="col-12">
              <?php
                
    			$date_year = date('Y-m');
                $sql_us = $db->prepare("SELECT SUM(invoice_fees) as sm FROM `tbl_invoice` WHERE tbl_invoice.cpny_ID=$company AND DATE_FORMAT(invoice_date, '%Y-%m') ='".$date_year."'");
                $sql_us->execute();
                $fetch_us = $sql_us->fetch();
                
                $sql_us1 = $db->prepare("SELECT SUM(pay_amount) as sm FROM `tbl_payment` WHERE pay_user='".$company."' AND DATE_FORMAT(pay_due_date, '%Y-%m') ='".$date_year."'");
                
                $sql_us1->execute();
                $fetch_us1 = $sql_us1->fetch();
                // in (select inv_ref_no from tbl_invoice where tbl_invoice.cpny_ID=$company) 
                
                $sql12 = $conn->prepare("SELECT SUM(invoice_fees) as sm FROM `tbl_invoice` WHERE tbl_invoice.cpny_ID=$company and DATE_FORMAT(invoice_date, '%Y-%m') <='".$date_year."'");
                $sql12->execute();
                $sum = $sql12->fetch();
                
                 $sql122 = $conn->prepare("SELECT SUM(pay_amount) as sm FROM `tbl_payment` WHERE pay_user='".$company."' ");
                $sql122->execute();
                $sum2 = $sql122->fetch();
                
                $stmt = $db->prepare("SELECT SUM(pay_amount) AS pay_amount4 FROM  tbl_payment where pay_user='".$company."' and status=1  and  DATE_FORMAT(pay_due_date, '%Y-%m') ='".$date_year."' ");
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $sumtot = $row['pay_amount4'];
                
                
                $stmt1 = $db->prepare("SELECT SUM(invoice_fees) AS invoice_fees FROM  tbl_invoice where cpny_ID='".$company."'  and  DATE_FORMAT(invoice_date, '%Y-%m') ='".$date_year."' ");
                $stmt1->execute();
                
                $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                $sumtot1 = $row1['invoice_fees'];
                
                
                
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
    					$date_year = date('Y');
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
                            <td><?php echo number_format($sumtot1); ?></td>
                            <td><span class="dot dot-lg bg-warning mr-2"></span></td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <th scope="col">Payment</th>
                            <td><?php echo number_format($sumtot); ?></td>
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
                      
                      <?php 
                      $compdate="2025-05";
                      $sql_rm1CheckTEN1 = $db->prepare("SELECT * FROM  tbl_tenants  WHERE ten_staff = 9 and ten_ref_no not in (select ten_ref from tbl_invo_link where co_id=$company
                        and date_format(li_date,'%Y-%m')='".$compdate."'   ) ");
                      $sql_rm1CheckTEN1->execute();
                      $abc=0;
                      while($fetch_rm1TEN1 = $sql_rm1CheckTEN1->fetch()){
                           
                          $phone222a=$fetch_rm1TEN1['ten_phone']; 
                          $abc++;  
                          $rnd="909".date("d"); 
                          $xxodee="2025-05";
                          $dayt="2025-05-08";
                         echo  $abc."# ".$fetch_rm1TEN1['ten_ref_no']." ".$phone222a."<br>";  
                         $id="https://chic.xode.rw/xode-invoice?code=$rnd".$fetch_rm1TEN1['ten_ref_no']."&xode=".$xxodee;
                         $msg="hard inserting bill link";
            // $inv_insert_qu20= $db->prepare("INSERT into tbl_invo_link(`li_date`, `link_text`, `ten_ref`, `receiced_phone`, `invo_type`,`co_id`,`sms`)
            // VALUES ('".$dayt."', '".$id."', '".$fetch_rm1TEN1['ten_ref_no']."', '$phone222a', '1','".$company."','".$msg."')");
            // $inv_insert_qu20->execute();
                           
                      }
                     
                            
                            if($company==1){
                   
$sql_rm1Main = $db->prepare("SELECT * FROM  tbl_payment_11_04_2025 order by pay_due_date asc, pay_ref_no asc");
$sql_rm1Main->execute();

$abc = 0;

$insertStmt = $conn->prepare('
    INSERT INTO tbl_payment (
        trans_code, pay_ref_no, bank_id, slip_no, pay_due_date, pay_amount,
        pay_description, pay_trm, pay_user, rent_date, c_value, rec_by,
        currency, serve_id, room_id, pay_room_id, api_bank, invoice_date
    ) VALUES (
        :trans_code, :pay_ref_no, :bank_id, :slip_no, :pay_due_date, :pay_amount,
        :pay_description, :pay_trm, :pay_user, :rent_date, :c_value, :rec_by,
        :currency, :serve_id, :room_id, :pay_room_id, :api_bank, :invoice_date
    )
');
$totalP=0;
while ($fetch_paid = $sql_rm1Main->fetch(PDO::FETCH_ASSOC)) {
    $abc++;
    $new_pay_id = date("YmdHis") . $abc;
    $paidAMT = $fetch_paid['pay_amount'];
$totalP=$totalP+$paidAMT;
    $sql_rm1CheckTEN = $db->prepare("SELECT * FROM tbl_invoice WHERE inv_ref_no = '" . $fetch_paid['pay_ref_no'] . "' AND paid = 3 AND invoice_fees > 0 
    order by invoice_date asc");
    $sql_rm1CheckTEN->execute();

while ($fetch_rm1TEN = $sql_rm1CheckTEN->fetch()) {
        $inv_room_id = $fetch_rm1TEN['inv_room_id'];
        $invoice_date = $fetch_rm1TEN['invoice_date'];
        $invoice_fees = $fetch_rm1TEN['invoice_fees'];
        $inv_ref_no= $fetch_rm1TEN['inv_ref_no'];
        
$sql = $db->prepare("
    SELECT SUM(pay_amount) AS paidHalf 
    FROM tbl_payment 
    WHERE invoice_date = :invoice_date AND pay_room_id = :pay_room_id AND pay_ref_no =:pay_ref_no
");
$sql->execute([
    ':invoice_date' => $invoice_date,
    ':pay_room_id' => $inv_room_id,
    ':pay_ref_no' => $inv_ref_no,
]);

$amountHalf = $sql->fetchColumn();
$remaionInvo=$invoice_fees-$amountHalf;
$diff = $paidAMT - ($invoice_fees-$amountHalf);

        if ($diff == 0) {
            $amount = $paidAMT;
             $paidAMT=$diff;
            $insertStmt->execute([
                ':trans_code'      => $new_pay_id,
                ':pay_ref_no'      => $fetch_paid['pay_ref_no'],
                ':bank_id'         => $fetch_paid['bank_id'],
                ':slip_no'         => $fetch_paid['slip_no'],
                ':pay_due_date'    => $fetch_paid['pay_due_date'],
                ':pay_amount'      => $amount,
                ':pay_description' => $fetch_paid['pay_description'],
                ':pay_trm'         => $fetch_paid['pay_trm'],
                ':pay_user'        => $fetch_paid['pay_user'],
                ':rent_date'       => $fetch_paid['rent_date'],
                ':c_value'         => $fetch_paid['c_value'],
                ':rec_by'          => $fetch_paid['rec_by'],
                ':currency'        => $fetch_paid['currency'],
                ':serve_id'        => $fetch_paid['serve_id'],
                ':room_id'         => $inv_room_id,
                ':pay_room_id'     => $inv_room_id,
                ':api_bank'        => $fetch_paid['api_bank'],
                ':invoice_date'    => $invoice_date
            ]);

            $stmt = $db->prepare("UPDATE tbl_invoice SET paid = '1' WHERE invoice_id = '" . $fetch_rm1TEN['invoice_id'] . "'");
            $stmt->execute();

            break;
         
        } 
        else if ($diff > 0) {
            $amount = $remaionInvo;
            $paidAMT = $diff;

            $insertStmt->execute([
                ':trans_code'      => $new_pay_id,
                ':pay_ref_no'      => $fetch_paid['pay_ref_no'],
                ':bank_id'         => $fetch_paid['bank_id'],
                ':slip_no'         => $fetch_paid['slip_no'],
                ':pay_due_date'    => $fetch_paid['pay_due_date'],
                ':pay_amount'      => $amount,
                ':pay_description' => $fetch_paid['pay_description'],
                ':pay_trm'         => $fetch_paid['pay_trm'],
                ':pay_user'        => $fetch_paid['pay_user'],
                ':rent_date'       => $fetch_paid['rent_date'],
                ':c_value'         => $fetch_paid['c_value'],
                ':rec_by'          => $fetch_paid['rec_by'],
                ':currency'        => $fetch_paid['currency'],
                ':serve_id'        => $fetch_paid['serve_id'],
                ':room_id'         => $inv_room_id,
                ':pay_room_id'     => $inv_room_id,
                ':api_bank'        => $fetch_paid['api_bank'],
                ':invoice_date'    => $invoice_date
            ]);

            $stmt = $db->prepare("UPDATE tbl_invoice SET paid = '1' WHERE invoice_id = '" . $fetch_rm1TEN['invoice_id'] . "'");
            $stmt->execute();

        } 
        else {
            $amount = $paidAMT;
            $paidAMT=$diff;
            $insertStmt->execute([
                ':trans_code'      => $new_pay_id,
                ':pay_ref_no'      => $fetch_paid['pay_ref_no'],
                ':bank_id'         => $fetch_paid['bank_id'],
                ':slip_no'         => $fetch_paid['slip_no'],
                ':pay_due_date'    => $fetch_paid['pay_due_date'],
                ':pay_amount'      => $amount,
                ':pay_description' => $fetch_paid['pay_description'],
                ':pay_trm'         => $fetch_paid['pay_trm'],
                ':pay_user'        => $fetch_paid['pay_user'],
                ':rent_date'       => $fetch_paid['rent_date'],
                ':c_value'         => $fetch_paid['c_value'],
                ':rec_by'          => $fetch_paid['rec_by'],
                ':currency'        => $fetch_paid['currency'],
                ':serve_id'        => $fetch_paid['serve_id'],
                ':room_id'         => $inv_room_id,
                ':pay_room_id'     => $inv_room_id,
                ':api_bank'        => $fetch_paid['api_bank'],
                ':invoice_date'    => $invoice_date
            ]);

            // $stmt = $db->prepare("UPDATE tbl_invoice SET paid = '1' WHERE invoice_id = '" .$fetch_rm1TEN['invoice_id'] ."'");
            // $stmt->execute();

            break;
        }
    }
    
    $remain=$paidAMT;

    if ($remain > 0) {   
        $amount = $remain;
        $invoice_date =$fetch_paid['pay_due_date'];
        $inv_room_id = $fetch_paid['pay_room_id'];
        $insertStmt->execute([
            ':trans_code'      => $new_pay_id,
            ':pay_ref_no'      => $fetch_paid['pay_ref_no'],
            ':bank_id'         => $fetch_paid['bank_id'],
            ':slip_no'         => $fetch_paid['slip_no'],
            ':pay_due_date'    => $fetch_paid['pay_due_date'],
            ':pay_amount'      => $amount,
            ':pay_description' => $fetch_paid['pay_description'],
            ':pay_trm'         => $fetch_paid['pay_trm'],
            ':pay_user'        => $fetch_paid['pay_user'],
            ':rent_date'       => $fetch_paid['rent_date'],
            ':c_value'         => $fetch_paid['c_value'],
            ':rec_by'          => $fetch_paid['rec_by'],
            ':currency'        => $fetch_paid['currency'],
            ':serve_id'        => $fetch_paid['serve_id'],
            ':room_id'         => $inv_room_id,
            ':pay_room_id'     => $inv_room_id,
            ':api_bank'        => $fetch_paid['api_bank'],
            ':invoice_date'    => $invoice_date
        ]); 
        
         
    }
    
    // $advance=$remain;
    // $ten_ref=$fetch_paid['pay_ref_no'];
    //  if ($advance > 0) {
    //      $bank=$fetch_paid['api_bank'];
    //      $slip_no=$fetch_paid['slip_no'];
    //      $dateooo=$fetch_paid['pay_due_date'];
    //      $payment_desc=$fetch_paid['pay_description'];
    //      $todaysdate=$fetch_paid['pay_trm'];
    //                 // Select tenant
    //                 $sqlten1 = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_ref_no = :ten_ref");
    //                 $sqlten1->bindParam(':ten_ref', $ten_ref, PDO::PARAM_STR);
    //                 $sqlten1->execute();
    //                 $rowten1 = $sqlten1->fetch();
    //                 $tenid = $rowten1['ten_id'];

    //                 // Select assigned rooms
    //                 $sqlten1room = $conn->prepare("SELECT * FROM tbl_assign WHERE tent_id = :tenid and assgn_status=1");
    //                 $sqlten1room->bindParam(':tenid', $tenid);
    //                 $sqlten1room->execute();

    //                 $rooms = [];
    //                 while ($rowten1room = $sqlten1room->fetch()) {
    //                     $amountroom = $rowten1room['amount'] - $rowten1room['discount'];
    //                     $rooms[] = [
    //                         'id' => $rowten1room['room_id'],
    //                         'amount' => $amountroom
    //                     ];
    //                 }

    //                 // Get the last invoice
    //                 $sqltrnas1 = $conn->prepare("SELECT * FROM tbl_invoice 
    //                     WHERE inv_ref_no = :inv_ref_no  ORDER BY invoice_date DESC LIMIT 1");
    //                 $sqltrnas1->bindParam(':inv_ref_no', $ten_ref, PDO::PARAM_STR);
    //                 $sqltrnas1->execute();
    //                 $lastinvoice = $sqltrnas1->fetch();
    //                 $lastmonth = $lastinvoice['invoice_date'] ?? date("Y-m-d");
                    
    //                 $date = new DateTime($lastmonth);
    //                 $date->modify('+1 month');

    //                 $month = $date->format('m');
    //                 $year = $date->format('Y');
    //                 $payments = [];

    //                 do {
    //                     $initialAdvance = $advance;
    //                     foreach ($rooms as $room) {
    //                         $roomId = $room['id'];
    //                         $price = $room['amount'];
                            
    //                         if ($advance >= $price) {
    //                             $payments[] = [
    //                                 'room' => $roomId,
    //                                 'amount' => $price,
    //                                 'month' => $month,
    //                                 'year' => $year
    //                             ];
    //                             $advance -= $price;
    //                         } elseif ($advance > 0) {
    //                             $payments[] = [
    //                                 'room' => $roomId,
    //                                 'amount' => $advance,
    //                                 'month' => $month,
    //                                 'year' => $year
    //                             ];
    //                             $advance = 0;
    //                         }
    //                     }

    //                     if ($advance === $initialAdvance) {
    //                         break;
    //                     }

    //                     $date->modify('+1 month');
    //                     $month = $date->format('m');
    //                     $year = $date->format('Y');
    //                 } while ($advance > 0);

    //                 // Save payments to the database
    //                 foreach ($payments as $payment) {
    //                     $roomid1 = $payment['room'];
    //                     $serveid1 = 4;
    //                     $roomAmount = $payment['amount'];
    //                     $dateinvo = $payment['year'] . '-' . $payment['month'] . '-01 00:00:00';
    //                     $dateinvo1 = $payment['year'] . '-' . $payment['month'] . '-01';

    //                     // Generate invoice code
    //                     $year1 = date('Y');
    //                     $year = substr($year1, -2);
    //                     $li_qu = $db->prepare("SELECT * FROM tbl_invoice WHERE inv_ref_no=:ten_ref ORDER BY invoice_id DESC LIMIT 1");
    //                     $li_qu->execute([':ten_ref' => $ten_ref]);
    //                     $fli = $li_qu->fetch();
    //                     $li = str_pad($fli['invoice_id'] ?? 0, 6, "0", STR_PAD_LEFT);
    //                     $invoice = $year . '-' . $li;
                          
    //                     if ($roomAmount > 0) {
    //                         $cmp_ID=0;
    //                         $sql1 = $conn->prepare("INSERT INTO tbl_payment 
    //                             (trans_code, pay_ref_no, bank_id, slip_no, pay_due_date, pay_amount, pay_description, 
    //                             pay_trm, pay_user, rent_date, c_value, rec_by, pay_room_id, serve_id, adv_sts, invoice_date, status)
    //                             VALUES 
    //                             (:invoice, :ten_ref, :bank, :slip_no, :dateooo, :roomAmount, :payment_desc, 
    //                             :todaysdate, :cmp_ID, :dateinvo, :converted, :user_id, :roomid1, :serveid1, 1, :dateinvo1, 1)");
                            
    //                         $sql1->execute([
    //                             ':invoice' => $invoice,
    //                             ':ten_ref' => $ten_ref,
    //                             ':bank' => $bank,
    //                             ':slip_no' => $slip_no,
    //                             ':dateooo' => $dateooo,
    //                             ':roomAmount' => $roomAmount,
    //                             ':payment_desc' => $payment_desc,
    //                             ':todaysdate' => $todaysdate,
    //                             ':cmp_ID' => $cmp_ID,
    //                             ':dateinvo' => $dateinvo,
    //                             ':converted' => 1,
    //                             ':user_id' => $_SESSION['USER_ID'],
    //                             ':roomid1' => $roomid1,
    //                             ':serveid1' => $serveid1,
    //                             ':dateinvo1' => $dateinvo1
    //                         ]);
                            
    //                         if ($sql1->rowCount() > 0) {
    //                             $checkadv = 1;
    //                         }
    //                     }
    //                 }
    //             }

    
    $lastId = $conn->lastInsertId();
    echo $abc . " # " . number_format($fetch_paid['pay_amount'])." |".number_format($totalP) . " #<br>";
}

}

?>
      
                            
                            
                    </div> <!-- .card-body -->
                    <div id="loadd" ></div>
                  </div> <!-- .card -->
                </div> <!-- / .col-md-8 -->
                <!-- Recent Activity -->
                <div class="col-md-4">
                  <div class="card shadow eq-card timeline">
                    <div class="card-header">
                      <strong class="card-title">Clients Don't Pay</strong>
                      <a class="float-right small text-muted" href="#!"><?php echo "details"; ?></a>
                    </div>
                    <div class="card-body" data-simplebar style="height: 360px; overflow-y: auto; overflow-x: hidden;">
                         <?php
                
                			$date_year = date('Y');
                             $sql_us11 = $db->prepare("SELECT *,SUM(pay_amount) as sm FROM `tbl_payment`
                             INNER JOIN tbl_tenants ON tbl_tenants.ten_ref_no =tbl_payment.pay_ref_no WHERE tbl_tenants.ten_staff = $company AND tbl_payment.status!=0 group by pay_ref_no order by sm desc limit 10");
                            $sql_us11->execute();
                            // $count = $sql_us11->rowCount();
                            if($sql_us11->rowCount() > 0){
                            while($fetch_us11 = $sql_us11->fetch()){
                                $sql_us134 = $db->prepare("SELECT SUM(invoice_fees) as sm FROM `tbl_invoice` WHERE inv_ref_no= '".$fetch_us11['pay_ref_no']."' AND DATE_FORMAT(invoice_date, '%Y') ='".$date_year."'");
                                $sql_us134->execute();
                                $fetch_us134 = $sql_us134->fetch();
                            $a++;
                        ?>
                      <div class="pb-3 timeline-item">
                          <span class="circle circle-sm bg-success h3 mb-0"><?php echo $a; ?></span>
                        <div class="pl-5">
                          
                          
                          <div class="mb-1 small"><strong><?php echo $fetch_us11['te_names']; ?></strong><span class="text-muted mx-2">
                            
                              <p class="small text-muted">Invoiced: <span class="badge badge-light"><?php echo number_format($fetch_us134['sm']); ?></span>
                          </p>
                             Payment: </span><strong><?php echo number_format($fetch_us11['sm']); ?></strong></div>
                          
                           <p class="small text-muted">Balance: <span class="badge badge-light"><?php echo number_format($fetch_us134['sm']); ?></span>
                          </p>
                        </div>
                      </div>
                      <?php }}else{ ?>
                      <div class="alert alert-dark" role="alert">
                        <span class="fe fe-meh fe-16 mr-2"></span> No unpaid Client! </div>
                      <?php } ?>
                      
                    </div> <!-- / .card-body -->
                  </div> <!-- / .card -->
                </div> <!-- / .col-md-3 -->
              </div> <!-- end section -->
            </div> <!-- .col-12 -->
           
           
           
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <input type="hidden" name="company" value="<?php echo $company; ?>" id="company">
   
<script>
function mylang12(data12){
		//alert(data);
		const ajaxreq =new XMLHttpRequest();
		ajaxreq.open('GET','checkajax?monthvalue='+data12,'TRUE');
		ajaxreq.send();
		ajaxreq.onreadystatechange= function(){

			if (ajaxreq.readyState== 4 && ajaxreq.status == 200) {

				document.getElementById('month_Id').
				innerHTML = ajaxreq.responseText;


			}
		}

	}

    
    $(document).ready(function () {
    $("#month_Id").change(function () {
        var year_id = $('#year_id').val();
        var company = $('#company').val();
		$(this).after('<div id="loader1" class="col-sm-1" id="loading"><img src="../images/ajax_loader.gif" width="30" height="30"></div>'); 
            $.get('load-inv_pay-mnthly.php?month_Id=' +$(this).val() + '&year_id=' + year_id + '&company=' + company, function (data){
               //	alert(2);
         $("#loadd").html(data);
         $('#disp1').hide();
         $('#loader1').hide();
         
           
        });
    });
});
</script>
<script>
//     var paymenyAmount  = {
//   "url": "https://xode.rw/api/clients/build/fee",
//   "method": "POST",
//   "timeout": 0,
//   "headers": {
//     "Content-Type": "application/json"
//   },
//   "data": JSON.stringify({
//     "pay_ref_no": "205106479",
//     "slip_no": "2345678",
//     "pay_amount": "49000",
//     "trans_code": "40577890"
//   }),
// };

// $.ajax(paymenyAmount).done(function (response) { 
//   console.log(response);
// });

</script>

