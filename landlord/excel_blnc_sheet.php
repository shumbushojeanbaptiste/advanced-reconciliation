<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=".$_REQUEST['tn']." BlanceSheet.xls");
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
$ten = $_REQUEST['ss'];
$date_from = $_REQUEST['dtf'];
$date_to = $_REQUEST['dtt'];

?>
<h4>Balance Sheet of <?php echo $_REQUEST['tn']; ?></h4>
   <div class="table-responsive">
	<table class="table table-hover" width="100%">
        	<thead>
        	        <?php 
                 $ref = $fetch['inv_ref_no'];
                 $stmt1 = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_ref_no = $ten "); 
                 $stmt1->execute(); // execute the query with parameter $code...
                $rowcount2 = $stmt1->rowCount();
                if ($rowcount2 > 0)// if there is data for that payment number...
                {
                $row12 = $stmt1->fetch(PDO::FETCH_ASSOC);
                echo "</h1>".$row12['te_names']."</h1>";
                 }
                 else{
                     echo "No Tenant Available";
                 }
                 ?>
						<tr>
							<th>Date</th>
							<th>Invoice</th>
							<th>Payment</th>
							<th>Balance</th>
						</tr>
					</thead>
					<tbody>
                       <?php
                       $data = array();
                    //     $sql11 = $conn->prepare("SELECT tbl_invoice.invoice_date,tbl_payment.pay_due_date FROM tbl_invoice,tbl_payment WHERE (tbl_invoice.inv_ref_no = '".$ten."' AND tbl_invoice.invoice_date BETWEEN '$date_from' AND '$date_to') OR (tbl_payment.pay_ref_no = $ten AND tbl_payment.pay_due_date BETWEEN '".$date_from."' AND '".$date_to." group by tbl_invoice.invoice_date , tbl_payment.pay_due_date') 
                    //     ");
                    //     $sql11->execute();
                    //   $c =  $sql11->rowCount();
                    //   echo $c;
                        // while($fetch_in11 = $sql11->fetch()){ echo $fetch_in11['pay_due_date']." "; }
                        
                        // $sql22 = $conn->prepare("SELECT * FROM `tbl_invoice` INNER JOIN tbl_payment ON tbl_payment.pay_ref_no = tbl_invoice.inv_ref_no WHERE tbl_invoice.inv_ref_no = '".$ten."' AND (tbl_invoice.invoice_date BETWEEN '$date_from' AND '$date_to') AND (tbl_payment.pay_due_date BETWEEN '".$date_from."' AND '".$date_to."') group by tbl_invoice.invoice_date , tbl_payment.pay_due_date");
                        // $sql22->execute();
                        // $c22 =  $sql22->rowCount();
                        
                        
                        $sql33 = $conn->prepare("SELECT invoice_date as date FROM `tbl_invoice` WHERE inv_ref_no = '".$ten."' AND invoice_date BETWEEN '$date_from' AND '$date_to'
                        UNION
                        SELECT pay_due_date as date FROM `tbl_payment` WHERE pay_ref_no = $ten AND pay_due_date BETWEEN '".$date_from."' AND '".$date_to."'
                        ");
                        $sql33->execute();
                        $c33 =  $sql33->rowCount();
                        // echo $c33;
                        // while($fetch_in33 = $sql33->fetch()){echo $fetch_in33['date']." ";}
                        $sql = $conn->prepare("SELECT * FROM `tbl_invoice` WHERE inv_ref_no = '".$ten."' AND invoice_date BETWEEN '$date_from' AND '$date_to' ");
                        $sql->execute();
                        // echo $c22;
                        // while($fetch_in22 = $sql22->fetch()){}
                        // while($fetch_in = $sql->fetch()){
                        $tot1=0;$tot2=0;
                        while($fetch_in33 = $sql33->fetch()){
                            
                            $ref = $fetch['inv_ref_no'];
                                 $stmt33 = $conn->prepare("SELECT * FROM `tbl_invoice` WHERE inv_ref_no = '".$ten."' AND invoice_date = '".$fetch_in33['date']."' "); 
                                 $stmt33->execute(array()); // execute the query with parameter $code...
                                $rowcount33 = $stmt33->rowCount();
                                $invoiceAMT=0;
                               while($row133 = $stmt33->fetch(PDO::FETCH_ASSOC)){
                                
                                $invoiceAMT=$invoiceAMT+($row133['invoice_fees']/$row133['unit_value']);
                               }
                            $data[] =array($invoiceAMT); 
                            $invoice_fee = $invoiceAMT;
                    ?>
                   <tr>
                       <td><?php  echo $fetch_in33['date']; ?></td>
                        <td>
                            <?php
                            if ($invoiceAMT > 0)// if there is data for that payment number...
                                {
                           echo number_format($invoiceAMT).".00";
                                 }
                                 else{
                                     echo "-";
                                 }
                                 $tot1 = $tot1 + $invoiceAMT;
                    ?>
                    </td>
                    <td>
                        <?php 
                                 $ref = $fetch['inv_ref_no'];
                                 $stmt = $conn->prepare("SELECT * FROM `tbl_payment` WHERE pay_ref_no = $ten AND pay_due_date ='".$fetch_in33['date']."' "); 
                                 $stmt->execute(array()); // execute the query with parameter $code...
                                $rowcount = $stmt->rowCount();
                                $amaountPYT=0;
                                while($row1 = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    
                                  $amaountPYT=$amaountPYT+($row1['pay_amount']/$row1['c_value']);  
                                }
                                
                                if ($amaountPYT > 0)// if there is data for that payment number...
                                {
                                echo number_format($amaountPYT).".00";
                                 }
                                 else{
                                     echo "-";
                                 }
                                 $tot2 = $tot2 + $amaountPYT;
                                 ?>
                    </td>
                    <td><?php echo number_format($tot1-$tot2) ?></td>  
                    </tr>
                   
                    <?php
                        }
                        
                    ?>
                    <tr><td><b>TOTAL :</b></td><td><b><?php  echo number_format($tot1).".00"; ?></b></td><td><b><?php  echo number_format($tot2).".00"; ?></b></td><td><?php if($tot1 > $tot2){ echo number_format($tot1 - $tot2).".00"; }else{ ?> <span class="text-danger"><?php echo number_format($tot2 - $tot1);?></span><?php } ?></td></tr>
                   
                   </tbody>
                    </table>
    </div>