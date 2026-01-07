<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=report.xls");
?>

<?php
 require_once ("../include/initialize.php");

?>
<?php
$ten = $_REQUEST['ten'];
$date_from = $_REQUEST['date_from'];
$date_to = $_REQUEST['date_to'];
?>
<div class="module-body table">
	<table cellpadding="0" cellspacing="0" border="2" class="datatable-1 table table-bordered table-striped	display" width="100%">
        	<thead>
        	    <tr>
        	        <th colspan="2" style="background-color:#ddd9c4;font-weight:bold;padding:10px;">
        	        <?php 
                 $ref = $fetch['inv_ref_no'];
                 $stmt1 = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_ref_no = $ten "); 
                 $stmt1->execute(); // execute the query with parameter $code...
                $rowcount2 = $stmt1->rowCount();
                if ($rowcount2 > 0)// if there is data for that payment number...
                {
                $row12 = $stmt1->fetch(PDO::FETCH_ASSOC);
                echo $row12['te_names']."<br>";
                 }
                 else{
                     echo "No Tenant Available";
                 }
                 ?>
                 Date From: <?php echo $date_from; ?><br>
                 Date To: <?php echo $date_to; ?>
                 </th>
        	    </tr>
						<tr>
							<th>Invoice</th>
							<th>Payment</th>
						</tr>
					</thead>
					<tbody>
                       <?php
                        $sql = $conn->prepare("SELECT SUM(DISTINCT invoice_fees) AS total FROM `tbl_invoice` WHERE inv_ref_no = '".$ten."' AND invoice_date BETWEEN '$date_from' AND '$date_to' ");
                        $sql->execute();
                        while($fetch_in = $sql->fetch()){
                            
                            $invoice_fee = $fetch_in['total'];
                    ?>
                   <tr>
                        <td>
                            <?php
                            echo number_format($invoice_fee).".00";
                    ?>
                    </td>
                    <td>
                        <?php 
                                 $ref = $fetch['inv_ref_no'];
                                 $stmt = $conn->prepare("SELECT SUM(DISTINCT pay_amount) AS totalPay FROM `tbl_payment` WHERE pay_ref_no = $ten AND pay_due_date BETWEEN '".$date_from."' AND '".$date_to."' "); 
                                 $stmt->execute(array()); // execute the query with parameter $code...
                                $rowcount = $stmt->rowCount();
                                if ($rowcount > 0)// if there is data for that payment number...
                                {
                                $row1 = $stmt->fetch(PDO::FETCH_ASSOC);
                                echo number_format($row1['totalPay']).".00";
                                 }
                                 else{
                                     echo "0";
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