        <style>
        
            #a{text-decoration: none;}
        </style>
        
            <div class="col-12">
              <div class="row align-items-center mb-2">
                <div class="col">
                  <h2 class="h5 page-title">Tenants Contract Report</h2>
                </div>
              </div>
            </div> <!-- .col-12 -->
           <div class="row my-12">
                <div class="col-md-12">
                  <div class="card shadow mb-12">
           <div class="card-body">
            <div class="col-12">
                <div class="">
                    <a href="export_tntContract_rpt.php?cpny=<?php echo $cmp_full; ?>">
                    <button class="btn btn-success pull-left" style="margin:7px;">Print Excel</button>
                    </a> <br>
                     <div class="table-responsive">
        <table class="table datatables" id="dataTable-13">
        		
        		<thead>
        			<tr>
        			    <th>#</th>
        				<th>Tenant</th>
        				<th>Phone</th>
        				<th>Room No.</th>
        				<th>Contract Start Date.</th>
        				<th>Contract Period</th>
        				<th>Contract End Date</th>
        				<th>Coution</th>
        				<th>Advance Month</th>
        				<th>Pay Next</th>
        				<!--<th>Contract AMT</th> -->
        				<th>Contract Rent AMT</th> 
        				<!--<th>Advance End Date</th>       -->
        			</tr>
        		</thead>
        		<tbody>
        			<?php
        		   	    $i= 0 ;
                        $ten_stmt = $conn->prepare("SELECT * FROM tbl_tenants
                        inner join tbl_ten_contracts on tbl_ten_contracts.tenant_id=tbl_tenants.ten_id
                        WHERE ten_staff='".$cpny_ID."' and cntr_status=1 and ten_ref_no not in (select tenant_id from tbl_tenant_leave group by tenant_id) and ten_id in (select tent_id from tbl_assign  group by tent_id) order by ten_id asc ");
                        $ten_stmt->execute();
                        $tot=0;
                        while($fetch_ten = $ten_stmt->fetch()) {
                            $t_id = $fetch_ten['ten_id'];
                            $ten_ID = $fetch_ten['ten_id'];
                            $i += 1;
                            $sqlass = $db->prepare("SELECT * FROM tbl_assign inner join tbl_rooms on tbl_rooms.room_id = tbl_assign.room_id 
                            
                            WHERE tbl_assign.company_id='".$company."'  and tbl_assign.tent_id='".$t_id."' ");
                            $sqlass->execute();
                           
                             $notify = $db->prepare("SELECT DISTINCT(tbl_assign.tent_id) FROM  `tbl_assign` WHERE  tent_id='".$ten_ID."'");
                             $notify->execute(array());
                             $notf_Count = $notify->rowCount();
                                
                                // check if row not exist
                                if ($notf_Count == 0)  {
                                    $bullet2 = '<label class="text-warning fa fa-circle"></label>';
                                    
                                } else{
                                     $bullet2 = '<label class="text-success fa fa-circle"></label>';  
                                }
                                $tdy=date("Y-m-d");
                                $earlier = new DateTime();   
$later = new DateTime($fetch_ten['c_end_date']);

$abs_diff = $later->diff($earlier)->format("%a"); //3
                     ?>
        			<tr class="gradeA" <?php if($abs_diff<3){ ?> style="background-color:#f4f1e0" title="Become expired in few days"  <?php  } ?>>
        			    <td class="center">
        				    <?php echo $i; ?>
        				</td>
        				<td class="center">
        				    <?php echo $bullet2." ".$fetch_ten['te_names']; ?>
        				</td>
        				<td class="center">
        				    <?php echo $fetch_ten['ten_phone']; ?>
        				</td>
        				<td class="center">
        				    <?php
        				     while($fetch_tenroom = $sqlass->fetch()){
        				    echo $fetch_tenroom['room_no']." <br> ";
        				     }
        				    ?>
        				</td>
        				<td><?php $originalDate = $fetch_ten['c_start_date'];
$newDate = date("d/m/Y", strtotime($originalDate));
echo $newDate  ?></td>
        				<td><?php echo $fetch_ten['c_month']  ?></td>
        				<td><?php  $originalDate2 = $fetch_ten['c_end_date'];
$newDate2 = date("d/m/Y", strtotime($originalDate2)); echo $newDate2  ?></td>
        				<td><?php echo $fetch_ten['coution_amaunt']  ?></td>
        				<td><?php echo $fetch_ten['adv_month']  ?></td>
        				<td><?php  echo  $fetch_ten['m_interval']  ?></td>
        				<!--<td><?php echo $fetch_ten['c_amaunt']  ?></td>-->
        				
        				<td><?php  
        				
        				
//         				$originalDate3 = $fetch_ten['adv_end_date'];
// $newDate3 = date("d/m/Y", strtotime($originalDate3));
//         				echo $newDate3  
        				
        				
                        if($fetch_ten['adv_month']==0 or $fetch_ten['adv_month']=="" ){
                            
                            echo $fetch_ten['c_rent_AMT']." "."Rwf";
                            $tot=$tot+$fetch_ten['c_rent_AMT'];
                        }
                        
                        else{
                        
                        $c_rent_amt=$fetch_ten['c_rent_AMT']*$fetch_ten['adv_month']+$fetch_ten['coution_amaunt']; 
                        
                     
        				
        				?><strong><?php echo number_format($c_rent_amt)." "."Rwf" ;
        				$tot=$tot+$c_rent_amt;
                        }
        				?></strong></td>
        			</tr>
        		
        			<?php
                        }
        			?>
        				<tr>
        			    
        			   <td colspan="10"><b>total<b></td> 
        			   <td><b><?php echo number_format($tot)." "."Rwf";
        			    
        			   ?></b></td> 
        			    
        			</tr>
        		 </tbody>
        	</table>
        	</div>
        	</div><!--Responsive --></div> <!-- .col-12 -->
        	</div>
           
           
           
          </div> <!-- .row -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <input type="hidden" name="company" value="<?php echo $company; ?>" id="company">
