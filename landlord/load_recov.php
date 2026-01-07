<?php
 require_once ("../include/initialize.php");

// get dates from form

$year_id=$_REQUEST['year_id'];
$date_from = $_REQUEST['date_from'];
$monthly =$year_id."-".$_REQUEST['monthly'];
$monthly33 =$_REQUEST['monthly'];
$mtn=$_REQUEST['monthly'];
//echo $monthly33;
?>
      <a href="export_MonthlyDetail_rpt.php?monthly=<?php echo $monthly; ?>&date_to=<?php echo $date_to; ?>&cpny=<?php echo $cmp_full; ?>&year_id=<?php echo $year_id  ?>">
    <button class="btn btn-success pull-left" style="margin:7px;">Export Excel</button>
    </a>
    <div class="table-responsive">
   <div class="module-body table">
		<table class="table table-hover" width="100%">
		<thead>
		     <tr>
		        <th colspan="8"><?php echo $cmp_full; ?> Monthly Report<br>
		       Month: <?php   $monthly22= date('M',strtotime($monthly)); echo $monthly22; ?><br>
		       
		        </th>
		    </tr>
			<tr> <th># </th>
                 <th>Shop No/Room No</th>
                 <th>Tenant name</th>
                 <th>Monthly fees</th>
                 <!--<th>Block</th>-->
                 
			</tr>
		</thead>
		<tbody>
   
		<?php
		
		//total
		$total =0;
		$gTotal=0;
		$block;
		$aa=1;
     // fetch 
    $sql = $db->prepare(" select *from tbl_block where company_id='".$company."'");
                //select *from tbl_invoice 
                // INNER JOIN tbl_tenants ON tbl_tenants.ten_ref_no=tbl_invoice.inv_ref_no
                // INNER JOIN tbl_rooms on tbl_rooms.room_id=tbl_invoice.inv_room_id 
                // where  company_id ='".$company."' and DATE_FORMAT(invoice_date,'%Y-%m' ) ='".$monthly33."'    order by r_floor asc
                $sql->execute();
                
                
                
                 
             while($Detail = $sql->fetch()) {
                  
                  $total =0;
                ?>
                <tr><td><?php echo $Detail['block_name'];?></td></tr>
                <?php
                //$aa++;
                $querryFloorRoom = $db->prepare("select *from tbl_rooms  
                inner join tbl_assign on tbl_assign.room_id =tbl_rooms.room_id
                inner join tbl_tenants on tbl_tenants.ten_id =tbl_assign.tent_id
                where tbl_rooms.company_id ='".$company."' and  tbl_rooms.r_floor ='".$Detail['Block_code']."' group by te_names order by te_names asc");
                $querryFloorRoom->execute();
                
                
                //looping inside
                
                while($QuerryRowRoom=$querryFloorRoom->fetch()){
                   $sql2 = $conn->prepare("select sum(invoice_fees) as totalinv from tbl_invoice  where
                   tbl_invoice.inv_ref_no ='".$QuerryRowRoom['ten_ref_no']."' and  tbl_invoice.cpny_ID ='".$company."' 
                   and inv_room_id ='".$QuerryRowRoom['room_id']."' and DATE_FORMAT(invoice_date,'%Y-%m' ) ='".$monthly33."'group by inv_ref_no");
                        $sql2->execute(array()); // execute the query with parameter $code...
                                $rowcount = $sql2->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $row1 = $sql2->fetch(PDO::FETCH_ASSOC);
                                $total = $total + $row1['totalinv'];
                                
                                //echo number_format($row1['totalinv']).".00";
                                 }
                                 else{
                                     //echo "0";
                                 }
                ?>   
              <tr class="odd gradeX">
               <td><?php echo $aa; ?></td>
               <td><?php echo $QuerryRowRoom['room_no'];?></td>
               <td><?php echo $QuerryRowRoom['te_names'];?></td>
               <td><?php echo $row1['totalinv'];?></td>
               <!--<td><?php //echo $Detail['Block_code'];?></td>-->
              </tr>
              <?php
              //$gTotal=$gTotal+$total;
              $aa++;
             }
             ?>
            <tr class="odd gradeX">  
              <td colspan="3">Total</td>
              <td align="left"><?php  echo number_format($total);?></td> 
              <td></td>
              <td></td>
              
            </tr>
            <?$gTotal=$gTotal+$total;
            }
            ?>
            <tr class="odd gradeX">  
              <td colspan="3">G.Total</td>
              <td align="left"><?php echo number_format($gTotal);?></td> 
              <td></td>
              <td></td>
              
            </tr>
		 </tbody>
		  <tfoot>
          </tfoot>
	    </table>
    </div>
 </div>