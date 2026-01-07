<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=".$_REQUEST['ctmr']." Statement ".date('Y-m-d').".xls");
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

  $stms = $db->prepare("SELECT *,SUM(pay_amount) as sm FROM  tbl_payment
                 WHERE tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."' AND tbl_payment.status != 0 group by tbl_payment.pay_due_date 
    			");
    			$stms->execute();
    			
    			$row_count = $stms->rowCount();
    // 			last payment
    	       $stms11 = $db->prepare("SELECT * FROM  tbl_payment
                 WHERE tbl_payment.pay_ref_no='".$_REQUEST['keyword1']."' AND tbl_payment.status != 0 order by tbl_payment.pay_id desc limit 1 
    			");
    			$stms11->execute();
    			
    			$row_count11 = $stms11->fetch();
    			
    	       $stms33 = $db->prepare("SELECT * FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$_REQUEST['keyword1']."'
    			");
    			$stms33->execute();
    			$invAMT=0;
    			while($row_count33 = $stms33->fetch()){
    			   $invAMT=$invAMT+($row_count33['invoice_fees']/$row_count33['unit_value']); 
    			}
    // 			special inv
    	       $stms33s = $db->prepare("SELECT *,SUM(invoice_fees) as sm FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$_REQUEST['keyword1']."' AND auto ='no'
    			");
    			$stms33s->execute();
    			
    			$row_count33s = $stms33s->fetch();
    // 			lst inv
                $stms33l = $db->prepare("SELECT * FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$_REQUEST['keyword1']."' order by invoice_id desc limit 1
    			");
    			$stms33l->execute();
    			
    			$row_count33l = $stms33l->fetch();
    			
    	        $stms2 = $db->prepare("SELECT * FROM  tbl_tenants
                 WHERE ten_ref_no='".$_REQUEST['keyword1']."'
    			");
    			$stms2->execute();
    			$row_count2 = $stms2->fetch();
    			
    	        $stms21 = $db->prepare("SELECT * FROM  tbl_tenants INNER JOIN tbl_assign ON tbl_assign.tent_id = tbl_tenants.ten_id
                 WHERE tbl_tenants.ten_ref_no='".$_REQUEST['keyword1']."'
    			");
    			$stms21->execute();
    			$row_count21 = $stms21->fetch();
    	
        ?>
       
        <div class="card-header">
          <strong class="card-title"><?php echo $row_count2['te_names'];?></strong>
          <a class="float-right small text-muted" href="#!"></a>
        </div>
        <div class="table-responsive">
        <table class="table table-bordered table-striped mb-none" id="" style="font-size:11px;">
            <thead>
                <tr>
                    <th>Rent Time</th>
                     <th>Monthly Invoice</th>
                     <th>Special Invoice</th>
                     <th>Last Invoice</th>
                     <th>Last Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php
               $tot=0;
    			
    			$rows = $stms->fetch(PDO::FETCH_ASSOC);
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
                    <td rowspan="<?php echo $row_count1;?>"><?php echo $row_count21['rent_date'];?></td>
                    <td rowspan="<?php echo $row_count1;?>"><?php echo number_format($invAMT);?></td>
                    <?php $rows1 = $stms1->fetch(PDO::FETCH_ASSOC);  ?>
                    
                    <td><?php echo number_format($row_count33s['sm']);?></td>
                    <td><?php echo number_format($row_count33l['invoice_fees']/$row_count33l['unit_value']);?><div class="card d-inline-flex mb-2"><br><div class="card-body bg-light small py-2 px-3"> Description: <?php echo $row_count33l['inv_desc'];?>. </div></div></td>
                    <td><?php echo number_format($row_count11['pay_amount']/$row_count11['c_value']);?><div class="card d-inline-flex mb-2"><br><div class="card-body bg-light small py-2 px-3"> Description: <?php echo $row_count11['pay_description'];?>. </div></div></td>
                    
    			    
                </tr>
        </tbody>
    </table>
        </div>