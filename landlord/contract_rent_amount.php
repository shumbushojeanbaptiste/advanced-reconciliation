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
        				<th>Tenant Names</th>
        				<th>C_Rent_Amount</th>
       			</tr>
        		</thead>
        		<tbody>
        			<?php
        			//echo "Company Id ".$cpny_ID;
        		   	    $i= 0 ;
        		   	    $totalRent=0;
                        $ten_stmt = $conn->prepare("SELECT * FROM tbl_ten_contracts where cpn_id='".$cpny_ID."'");
                        $ten_stmt->execute();
                        while($fetch_ten = $ten_stmt->fetch()) {
                            $i += 1;
                            $sqlass = $db->prepare("SELECT *FROM tbl_tenants where ten_id = '".$fetch_ten['tenant_id']."'");
                            $sqlass->execute();
                            while($tenantRent=$sqlass->fetch()){
                                $totalRent=$totalRent+$fetch_ten['c_rent_AMT'];
                                ?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $tenantRent['te_names'];?></td>
                                    <td><?php echo $fetch_ten['c_rent_AMT'];?></td>
                                </tr>
                                <?
                            }
        			
                        }
        			?>
        		<tr>
        		    <td colspan="2">Total</td>
        		    <td ><?php echo number_format($totalRent);?></td>
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
