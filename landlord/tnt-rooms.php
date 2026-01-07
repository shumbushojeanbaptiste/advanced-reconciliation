        <style>
        
            #a{text-decoration: none;}
        </style>
        
            <div class="col-12">
              <div class="row align-items-center mb-2">
                <div class="col">
                  <h2 class="h5 page-title">Tenats assigned rooms Report</h2>
                </div>
              </div>
            </div> <!-- .col-12 -->
           <div class="row my-12">
                <div class="col-md-12">
                  <div class="card shadow mb-12">
           <div class="card-body">
            <div class="col-12">
                <div class="">
                    <a href="export_tntroom_rpt.php?cpny=<?php echo $cmp_full; ?>">
                    <button class="btn btn-success pull-left" style="margin:7px;">Print Excel</button>
                    </a> <br>
        <table class="table datatables" id="dataTable-13">
        		
        		<thead>
        			<tr>
        			    <th>#</th>
        				<th>Tenat</th>
        				<th>Phone</th>
        				<th>Email</th>
        				<th>Address</th>
        				<th>Room No.</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php
        		   	    $i= 0 ;
                        $ten_stmt = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_staff='".$cpny_ID."'and ten_ref_no not in (select tenant_id from tbl_tenant_leave group by tenant_id) and ten_id in (select tent_id from tbl_assign group by tent_id) order by ten_id asc ");
                        $ten_stmt->execute();
                        while($fetch_ten = $ten_stmt->fetch()) {
                            $t_id = $fetch_ten['ten_id'];
                            $ten_ID = $fetch_ten['ten_id'];
                            $i += 1;
                            $sqlass = $db->prepare("SELECT * FROM tbl_assign inner join tbl_rooms on tbl_rooms.room_id = tbl_assign.room_id  WHERE tbl_assign.company_id='".$company."'  and tbl_assign.tent_id='".$t_id."' ");
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
                     ?>
        			<tr class="gradeA">
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
        				<?php if($fetch_ten['ten_email'] == ""){
        				     echo "-";
        				    }else{
        				 echo $fetch_ten['ten_email']; 
        				 }?>
            			</td>
            			<td class="center">
        				  <?php
        				  if($fetch_ten['ten_address'] == ""){
        				  echo "-";
        				    }else{
        				  echo $fetch_ten['ten_address'];
        				    }
        				  ?>
            			</td>
            			
        				<td class="center">
        				    <?php
        				     while($fetch_tenroom = $sqlass->fetch()){
        				    echo $fetch_tenroom['room_no']." <br> ";
        				     }
        				    ?>
        				</td>
        			</tr>
        			<?php
                        }
        			?>
        		 </tbody>
        	</table>
        	</div><!--Responsive --></div> <!-- .col-12 -->
        	</div>
           
           
           
          </div> <!-- .row -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <input type="hidden" name="company" value="<?php echo $company; ?>" id="company">
