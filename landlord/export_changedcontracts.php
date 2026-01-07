<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename= ".$_REQUEST['cpny']."changed contracts.xls");
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
<h2>Changed contract report</h2>
<h3>Date:  <?php  echo date("d/m/Y"); ?></h3>
 <div class="table-responsive">
   <div class="module-body table">
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
 </div>