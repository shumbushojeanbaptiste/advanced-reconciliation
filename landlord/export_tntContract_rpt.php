<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename= ".$_REQUEST['cpny']." Tenats contract Report.xls");
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
<h2>Client Contract report</h2>
<h3>Date:  <?php  echo date("d/m/Y"); ?></h3>
 <div class="table-responsive">
   <div class="module-body table">
          <table class="table datatables" id="dataTable-13">
        		
        		<thead>
        			<tr>
        			    <th>#</th>
        				<th>Tenant</th>
        				<th>Phone</th>
        				<!--<th>Room No.</th>-->
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
                        WHERE ten_staff='".$cpny_ID."'  and c_amaunt!=0 and cntr_status=1 and ten_ref_no not in (select tenant_id from tbl_tenant_leave group by tenant_id) and ten_id in (select tent_id from tbl_assign  group by tent_id) order by ten_id asc ");
                        $ten_stmt->execute();
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
        				<!--<td class="center">-->
        				    <?php
        				    //  while($fetch_tenroom = $sqlass->fetch()){
        				    // echo $fetch_tenroom['room_no']." <br> ";
        				    //  }
        				    ?>
        				<!--</td>-->
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
        				
        				?><strong><?php echo number_format($fetch_ten['c_rent_AMT'])." "."Rwf" ?></strong></td>
        			</tr>
        			<?php
                        }
        			?>
        		 </tbody>
        	</table>
    </div>
 </div>