<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=".$_REQUEST['ctmr']." Payments.xls");
?>
<?php
 require_once ("../include/initialize.php");
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
<?php
// get dates from form

$ss = $_REQUEST['keyword1'];
$ctmr= $_REQUEST['ctmr'];
$stms = $db->prepare("SELECT * FROM  tbl_payment
             WHERE tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."' AND tbl_payment.status != 0 group by tbl_payment.pay_due_date 
			");
			$stms->execute();
			
			$row_count = $stms->rowCount();
	$stms2 = $db->prepare("SELECT * FROM  tbl_tenants
             WHERE ten_ref_no='".$_REQUEST['keyword1']."'
			");
			$stms2->execute();
			$row_count2 = $stms2->fetch();
?>
<h4>All Payments of <?php echo $_REQUEST['ctmr']; ?></h4>
  <div class="table-responsive">
        <table class="table table-bordered table-striped mb-none" id="" style="font-size:11px;">
            <thead>
                <tr>
                    <th>No.</th>
                     <th>Date</th>
                     <th>Slip No</th>
                     <th>Bank Name</th>
                     <th>Description</th>
                     <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
               $tot=0;
    			
    			while ($rows = $stms->fetch(PDO::FETCH_ASSOC)):
    			    $pay_id= $rows['pay_id'];
    			    $pay_due_date= $rows['pay_due_date'];
    			    $i++;
    			    
    			    $stms1 = $db->prepare("SELECT * FROM  tbl_payment
    	        INNER JOIN `tbl_bank` ON tbl_payment.bank_id=tbl_bank.bank_id 
                 INNER JOIN tbl_tenants ON tbl_payment.pay_ref_no=tbl_tenants.ten_ref_no
                 WHERE tbl_payment.pay_due_date='".$pay_due_date."' AND tbl_payment.status != 0 AND tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."' group by tbl_payment.pay_id
    			");
    			$stms1->execute();
    			$row_count1 = $stms1->rowCount();
                ?>
                    <tr>
                    <td rowspan="<?php echo $row_count1;?>"><?php echo $i;?></td>
                    <td rowspan="<?php echo $row_count1;?>"><?php echo $rows['pay_due_date'];?></td>
                     <td rowspan="<?php echo $row_count1;?>"><?php echo $rows['slip_no'];?></td>
                    <?php while ($rows1 = $stms1->fetch(PDO::FETCH_ASSOC)):  ?>
                    
                   
                    <td><?php echo $rows1['bank_name'];?></td>
                    <td><?php echo $rows1['pay_description'];?></td>
                    <td>
                    <?php 
					echo $rows1['pay_amount']/$rows1['c_value']; $tot =$tot + ($rows1['pay_amount']/$rows1['c_value']);
                    ?>
                    </td>
                    
    			    
                </tr>
                
            <?php  endwhile;endwhile;?>
            <tr><td><b>Total:</b></td><td colspan="4"></td><td><b><?php 
             if($currency_id==2){
                  
                    echo ($tot)." "." ";
                    
                }
                else{
                    echo number_format($tot)." ";
                }
            
            
            //echo number_format($tot); ?></b></td></tr>
        </tbody>
    </table>
        </div>