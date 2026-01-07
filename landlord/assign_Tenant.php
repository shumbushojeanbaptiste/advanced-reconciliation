<input type="hidden" name="" value="<?php echo $tenant_id ?>"/>
       <input type="hidden" name="" value="<?php echo $rooms_id ?>"/>
         
             
        <table class="table datatables" id="dataTable-12">
        		<thead>
        			<tr>
        			    <th>#</th>
        				<th>Ref No.</th>
        				<th>Names</th>
        				<th>Phone</th>
        				<th>Email</th>
        				<th>Address</th>
        				<th>Action</th>
        			</tr>
        		</thead>
        			<tbody>
        			<?php
        		   	    $i= 0 ;
                        $ten_stmt = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_staff='".$cpny_ID."'");
                        $ten_stmt->execute();
                        while($fetch_ten = $ten_stmt->fetch()) {
                            $t_id = $fetch_ten['ten_id'];
                            $ten_ID = $fetch_ten['ten_id'];
                            $i += 1;
                            
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
        				    <?php echo $fetch_ten['ten_ref_no']; ?>
        				</td>
        				<td class="center">
        				    <?php echo $bullet2." ".$fetch_ten['te_names']; ?>
        				</td>
        				<td class="center">
        				    <?php echo $fetch_ten['ten_phone']; ?>
        				</td>
        				<td class="center">
        				<?php if($fetch_ten['ten_email'] == ""){
        				     echo "No data";
        				    }else{
        				 echo $fetch_ten['ten_email']; 
        				 }?>
            			</td>
            			<td class="center">
        				  <?php
        				  if($fetch_ten['ten_address'] == ""){
        				  echo "No data";
        				    }else{
        				  echo $fetch_ten['ten_address'];
        				    }
        				  ?>
            			</td>
                        <td class="cell-icon text-center">
                           <a href="" data-toggle="modal" data-target="#assign<?php echo $fetch_ten['ten_id']?>" class="btn btn-info" style="font-size:7px;width:60px;"> Assign</a>
                             |
                            <a onclick="loadmodroomassigns('<?php echo $fetch_ten['ten_id'];?>');" class="btn btn-default" style="height:15px;font-size:9px;width:60px;">View Assigns</a>
                        </td>
        			</tr>
        		   
        			<!-- Form modal for tenant update -->
        <?php include'tenants_assign.php'; ?>
        <!-- /form modal -->
        
        			<?php
                        }
        			?>
        		 </tbody>
        	</table>
              	<script>
    function loadmodroomassigns(ten_id){
$('#tenantlist').hide();
/// Loading....
$("#loadingimg").show();
//alert(module_code+" - "+acad_cycle_id+" - "+campus);

$.post("load_modassignsroom.php", {ten_id:ten_id}, function(data){
$("#loadingimg").hide();
$('#modassignsrooms').html(data);
$('#modassignsrooms').show();
});

}
</script>