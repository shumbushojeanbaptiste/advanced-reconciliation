<table class="table datatables" id="dataTable-1">
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
                    $i += 1;
                    
                 $notify = $db->prepare("SELECT DISTINCT(tbl_assign.tent_id) FROM  `tbl_assign` WHERE  tent_id='".$t_id."'");
                 $notify->execute(array());
                 $notf_Count = $notify->rowCount();
                    
                    // check if row not exist
                    if ($notf_Count == 0)  {
                        $bullet = '<label class="text-warning fa fa-circle"></label>';
                        $hidden= 0;
                        
                    } else{
                         $bullet = '<label class="text-success fa fa-circle"></label>';
                         $hidden= 1;
                    }
                    
                    
             ?>
			<tr >
			    <td class="center">
				    <?php echo $i; ?>
				</td>
				<td class="center">
				    <?php echo $fetch_ten['ten_ref_no']; ?>
				</td>
				<td class="center">
				    <?php echo $bullet." ".$fetch_ten['te_names']; ?>
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
                   <a href="" data-toggle="modal" data-target="#update<?php echo $fetch_ten['ten_id']?>" class="edit_data"><i class="icon-edit"></i> Edit</a>
                     |
                     
                     <?php 
                     if($hidden =="0"){
                     ?>
                    <a href="?file=tenmgnt&delete_tenant=<?php echo $t_id;?>" onclick="if(!confirm('Do you really want to Delete This Tenant?'))return false;else return true;" title="Delete tenant"><span style="color: #2e2e2e;"><i class="icon-trash"></i> Delete</span></a>
                   <?php 
                     }elseif($hidden =="1"){
                        echo "Assigned";
                     }
                   ?>
                </td>
			</tr>
			
			
			<!-- Form modal for tenant update -->
		<?php include'ten_modal.php'; ?>
<!-- /form modal -->

			<?php
                }
			?>
		 </tbody>
	</table>
