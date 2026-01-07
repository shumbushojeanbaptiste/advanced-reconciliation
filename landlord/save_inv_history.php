<?php
$stms88 = $db->prepare("SELECT * FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$fetch['ten_ref_no']."' and invoice_date='".$paid_dt."'  group by tbl_invoice.invoice_date 
    			");
    			$stms88->execute();
    			$rows88 = $stms88->fetch();
    			 $invNo= $rows88['invoice'];
    			 $pay_due_date= $rows88['invoice_date'];
	$stms = $db->prepare("SELECT * FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$fetch['ten_ref_no']."'   group by tbl_invoice.invoice_date 
    			");
    			$stms->execute();
    			
    			$row_count = $stms->rowCount();
    	$stms2 = $db->prepare("SELECT * FROM  tbl_tenants
                 WHERE ten_ref_no='".$fetch['ten_ref_no']."'
    			");
    			$stms2->execute();
    			$row_count2 = $stms2->fetch();
    
if($row_count2>0){
    			    
    			    $tot=0;
    			    $remain77=0;
    			
    			while ($rows = $stms->fetch(PDO::FETCH_ASSOC)){
    			    $pay_id= $rows['invoice_id'];
    			    $invNo= $rows['invoice'];
    			    $pay_due_date= $rows['invoice_date'];
    			    $i++;
    			   
    			   
    			
      $stms1 = $db->prepare("SELECT * FROM  tbl_invoice
    	       
                 INNER JOIN tbl_tenants ON tbl_invoice.inv_ref_no=tbl_tenants.ten_ref_no
                 WHERE  tbl_invoice.inv_ref_no='".$fetch['ten_ref_no']."' and  tbl_invoice.invoice_date='".$pay_due_date."'
    			");
    			$stms1->execute();
    			$row_count1 = $stms1->rowCount(); 
    			$remain=0;
    			while ($rows1 = $stms1->fetch(PDO::FETCH_ASSOC)){
    			     if($rows1['auto']=='yes'){
                            $invTyp= "Monthly";
                        }
                        else{
                            $invTyp= "Special";
                        }
                        
                     $tot =$tot + $rows1['invoice_fees'];  
                     $stms177 = $db->prepare("SELECT sum(pay_amount) as sm from tbl_payment where pay_ref_no='".$fetch['ten_ref_no']."' and rent_date='".$rows1['invoice_date']."'
    			");
    			$stms177->execute();
    			$rows177 = $stms177->fetch();
    		
                 $remain = $rows1['invoice_fees']-$rows177['sm'];
                      
                      $inv_insert_qu77 = $db->prepare("INSERT into tbl_invice_history(`link`, `date`, `amount`,inv_ref_no,inv_date)
                        VALUES ('".$id."', '".$rows1['invoice_date']."', '".$remain."', '".$fetch['ten_ref_no']."', '".$paid_dt."')");
                        $inv_insert_qu77->execute();   
    			}
    			$remain77 = $remain77 + $remain;
    			}
    			    
    			}
?>
