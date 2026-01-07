<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename= ".$_REQUEST['cpny']." Tenats Rooms Report.xls");
?>
<?php
 require_once ("../include/initialize.php");

// get dates from form

?>
<style type="text/css">
table{
    padding: 20px;
    width:100%;
 }

td
{
	padding-left:20px;
	padding-right:20px;
	padding-top:3px;
	padding-bottom:3px;
	border:1px solid #666;
	font-size:19px;
	/*text-align:center;*/
	min-width:120px;
	}
.header
	{
		padding-top:15px;
		padding-bottom:15px;
		color:#fff;
		background-color:#68A9BD;
		
	}
.title
	{
		border:0px;
		text-align:center;
		font-weight:600;
	}
.color_title
	{
		color:#68A9BD;
	}
</style>
 <div class="table-responsive">
   <div class="module-body table">
        <table class="table datatables" id="dataTable-13">
        		
        		<thead>
        		    <tr style="background-color:#ddd9c4;font-weight:bold;padding:10px;">
        		        <th colspan="6"><?php echo $_REQUEST['cpny']; ?> All Tenats Rooms Report
        		        </th>
        		    </tr>
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
    </div>
 </div>