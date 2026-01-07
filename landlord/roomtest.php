        <style>
        
            #a{text-decoration: none;}
        </style>
        
            <div class="col-12">
              <div class="row align-items-center mb-2">
                <div class="col">
                  <h2 class="h5 page-title">Tenants Rent Amount</h2>
                </div>
              </div>
            </div> <!-- .col-12 -->
           <div class="row my-12">
                <div class="col-md-12">
                  <div class="card shadow mb-12">
           <div class="card-body">
            <div class="col-12">
                <div class="">
                    <a href="export_Tenants_Rent.php?comp=<?php echo $cpny_ID; ?>">
                    <button class="btn btn-success pull-left" style="margin:7px;">Print Excel</button>
                    </a> <br>
                     <div class="table-responsive">
        <table class="table datatables table-hover" id="dataTable-1">
        		
        		<thead>
        			<tr>
        			    <th>#</th>
        				<th>room no</th>
        				<th>room price</th>
        				<th>assign price</th>
        				<th>comment</th>
       			</tr>
        		</thead>
        		<tbody>
        			<?php
        			//echo "Company Id ".$cpny_ID;
        		   	    $i= 0 ;
        		   	    $totalRent=0;
                        $ten_stmt = $conn->prepare("SELECT * FROM tbl_rooms where company_id='".$cpny_ID."' and rm_status=1 and availability=4");
                        $ten_stmt->execute();
                        
                        while($fetch_ten = $ten_stmt->fetch()) {
                            
                            $i++;
                            $sqlass = $db->prepare("SELECT * FROM tbl_assign where room_id = '".$fetch_ten['room_id']."'");
                            $sqlass->execute();
                            $fetch_ten1 = $sqlass->fetch();
                            $mon=$fetch_ten1['amount'];
                            
                            
                            
                            
                            
                           ?>
        		             
        		             <tbody>
        		                 <tr>
        		                     <td><?php echo $i ?></td>
        		                     <td><?php echo $fetch_ten['room_no']?></td>
        		                     <td><?php echo $fetch_ten['price']?></td>
        		                     <td><?php echo $mon?></td>
        		                     <td><?php 
        		                     
        		                     if($mon==$fetch_ten['price']){
        		                       echo 'the saame';
        		                       
        		                         
        		                     }
        		                     
        		                     else {
        		                         
        		                         echo 'difference';
        		                     }
        		                     
        		                     ?></td>
        		                     </tr>
        		                 </tbody>
        		             
        		             <?php         }
        			?>
        		<!--<tr>-->
        		<!--    <td colspan="2">Total</td>-->
        		<!--    <td ><?php //echo number_format($totalRent);?></td>-->
        		<!--</tr>-->
        		 </tbody>
        	</table>
        	</div>
        	</div><!--Responsive --></div> <!-- .col-12 -->
        	</div>
           
           
           
          </div> <!-- .row -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <input type="hidden" name="company" value="<?php echo $company; ?>" id="company">
