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
                    <a href="export_changedcontracts.php?cpny=<?php echo $cmp_full; ?>">
                    <button class="btn btn-success pull-left" style="margin:7px;">Print Excel</button>
                    </a> <br>
                     <div class="table-responsive">
        <table class="table datatables" id="dataTable-13">
        		
        		<thead>
        			<tr>
        			    <th>#</th>
        				<th>Tenant</th>
        				<th>Contract Start Date.</th>
        				<th>Reason</th>
        				
        			
        			</tr>
        		</thead>
        		<tbody>
        			<?php
        		   	    $sql=$conn->prepare("SELECT * FROM tbl_ten_contracts WHERE changing_reasons IS NOT NULL");
        		   	    $sql->execute();
        		   	    $a=1;
        		   	    
        		   	    while($rowa=$sql->fetch()){
        		   	        
        		   	        ?>
        		   	        
        		   	        
        		   	  <tr>
        		   	      <td><?php echo $a++;?></td>
        		   	      <td><?php
        		   	       $rowa['tenant_id'];
        		   	       $sql1=$conn->prepare("SELECT * FROM tbl_tenants WHERE ten_id='".$rowa['tenant_id']."' ");
        		   	       $sql1->execute();
        		   	       while($rowa1=$sql1->fetch()){
        		   	           
        		   	           echo $rowa1['te_names'];
        		   	       }
        		   	      ?></td>   
        		   	       <td><?php echo $rowa['c_start_date']?></td>   
        		   	        <td><?php echo $rowa['changing_reasons']?></td>   
        		   	      </tr>
        		   	      <?php
        		   	    }
        		
        			?>
        		 </tbody>
        	</table>
        	</div>
        	</div><!--Responsive --></div> <!-- .col-12 -->
        	</div>
           
           
           
          </div> <!-- .row -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        <input type="hidden" name="company" value="<?php echo $company; ?>" id="company">
