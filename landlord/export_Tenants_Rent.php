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
<h2>Tenants Rent Report</h2>
<h3>Date:  <?php  echo date("d/m/Y"); ?></h3>
 <div class="table-responsive">
   <div class="module-body table">
          <table class="table datatables" id="dataTable-13">
        		
        		<thead>
        			<tr>
        			    <th>#</th>
        				<th>Tenant Names</th>
        				<th>C_Rent_Amount</th>
       			</tr>
        		</thead>
        		<tbody>
        			<?php
        			$cpny_ID=$_REQUEST['comp'];
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
 </div>