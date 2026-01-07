<?php
ob_start(); 
require_once('fpdf16/fpdf.php');
require_once('include/initialize.php');
 
//  request vars
 $code =$_REQUEST['code'];
 $tenRef =substr($code,5,-3);
 $service =$_REQUEST['xode'];
 $serviceId =substr($_REQUEST['service'], 10, -1);
 $sumInvoice =0;

/* create a pdf object
and initialize a page
*/

$pdf=new FPDF();

$pdf->AddPage('L');
$pdf->AliasNbPages();
$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(20);

$pdf->SetAuthor('https://xode.rw');
$pdf->SetTitle('INVOICE');

// header section

$pdf->image('img/chic_logo.jpg',10,10,30,30);

$pdf->SetFont('Times','B',20);
$pdf->SetTextColor(50,60,100);
$pdf->Cell(0,10,'CHAMPIONS INVESTMENT CORPORATION Ltd',0,1,'C');

$pdf->SetFont('Times','B',10);
$pdf->SetTextColor(50,60,100);
$pdf->Cell(0,10,'TIN/TVA: 102 456 160; www.chic.rw; Email: chic_ltd@ymail.com or camaradeo@gmailo.com',0,1,'C');

$pdf->SetFont('Times','B',10);
$pdf->SetTextColor(50,60,100);
$pdf->Cell(0,10,'B.P: 1429 Kigali; Tel: +250 788 303 651 or +250 788 300 638',0,1,'C');

$pdf->SetLineWidth(0.5);
$pdf->Line(40, 38, 240, 38);

$pdf->setY(41);

$pdf->SetFont('Times','B',18);
$pdf->SetTextColor(50,60,100);
$pdf->Cell(0,10,'REQUEST OF PAYMENT',0,1,'C');


$invoice =$conn->prepare("SELECT *from tbl_bill where DATE_FORMAT(invoice_date,'%Y-%m') ='".$service."' and ten_id ='".$tenRef."' GROUP BY ten_id");
$invoice->execute();
$theInvoice =$invoice->fetch();
$originalDate2 = $theInvoice['inv_date'];
    $newDate2 = date("d/m/Y", strtotime($originalDate2));
$pdf->SetLineWidth(0.5);
$counterr =0;
$pdf->Line(109, 50, 188, 50);
$pdf->setY(54);
$pdf->SetFont('Arial','',12);
$pdf->Cell(120,8,'Invoice No: '.$theInvoice['invoice'],1);
$pdf->Cell(120,8,'Invoice Date: '.$newDate2,1);
$pdf->Ln();

$tenants =$conn->prepare("SELECT *from tbl_tenants where ten_ref_no ='".$tenRef."'");
$tenants->execute();
$theTenant =$tenants->fetch();

$room =$conn->prepare("SELECT *from tbl_rooms where room_id=(SELECT room_id from tbl_assign where tent_id ='".$theTenant['ten_id']."' GROUP BY tbl_assign.tent_id)");
$room->execute();
$theRoom =$room->fetch();

$pdf->SetFont('Arial','',12);
$pdf->Cell(120,8,'Shop/Office by: '.$theTenant['te_names'],1);
$pdf->Cell(120,8,'Phone: '.$theTenant['ten_phone'],1);
$pdf->Ln();
$pdf->SetFont('Arial','',12);
$pdf->Cell(120,8,'TIN: '.$theTenant['tin_no'],1);
$pdf->Cell(120,8,'Email: '.$theTenant['ten_email'],1);



$pdf->Ln(18);

if(!empty($role) || empty($role)){


$pdf->SetFont('Arial','B',7);
$pdf->Cell(20,8,'Room No',1);
$pdf->Cell(30,8,'DETAILS',1);
$pdf->Cell(25,8,'PREVIOUS (Kwh)',1);
$pdf->Cell(25,8,'CURRENT (Kwh)',1);
$pdf->Cell(40,8,'POWER CONSUMPTION (Kwh)',1);
$pdf->Cell(30,8,'UNIT PRICE (RWF/Kwh)',1);
$pdf->Cell(30,8,'Total Unpaid (RWF)',1);
$pdf->Cell(20,8,'Invoice (RWF)',1);
$pdf->Cell(20,8,'Balance (RWF)',1);

// service
$serveId = $db->prepare("SELECT *FROM tbl_service where serve_id ='".$serviceId."' and s_co ='".$theInvoice['cpny_ID']."'");
$serveId->execute();
$theServeId = $serveId->fetch(); 

// $pdf->Ln();

$nextMonth = date('Y-m', strtotime("$service-01 +2 month"));

      $sql2 = $conn->prepare("SELECT SUM(invoice_fees) AS totalinv FROM `tbl_bill` WHERE ten_id = '".$tenRef."'  ");
                        $sql2->execute(array()); // execute the query with parameter $code...
                        //and  DATE_FORMAT(invoice_date,'%Y-%m') <'".$service."' 
                        $rowcount1 = $sql2->rowCount();
                        $row1 = $sql2->fetch(PDO::FETCH_ASSOC);
                        
                        $sql4 = $conn->prepare("SELECT SUM(pay_amount) AS totalpay FROM `tbl_pay_bill` WHERE ten_id ='".$tenRef."'  ");
                        $sql4->execute(array()); // execute the query with parameter $code...
                        //and DATE_FORMAT(pay_due_date,'%Y-%m') <='".$nextMonth."' 
                        $rowcount2 = $sql4->rowCount();
                        $row3 = $sql4->fetch(PDO::FETCH_ASSOC);
                        $DIFF=$row1['totalinv']-$row3['totalpay'];
if($DIFF>1){
    
// $pdf->SetFont('Arial','B',8);
// $pdf->Cell(220,8,'TOTAL UNPAID AMOUNT',1);

// $pdf->SetFont('Arial','',10);
// $pdf->Cell(20,8,number_format($DIFF),1);

}

else{
  //$AllInvo=$sumInvoice;  
}
$pdf->Ln();
// $pdf->SetFont('Arial','B',8);
// $pdf->Cell(20,8,'1',1);
// $pdf->Cell(50,8,$theServeId['service_name'],1);
// $pdf->Cell(170,8,'',1);


// rooms
$roomToView =$conn->prepare("SELECT *from tbl_rooms where room_id in(SELECT room_id FROM tbl_assign where tent_id ='".$theTenant['ten_id']."') 
and room_id in(SELECT  room_id from tbl_consume WHERE qty>0 and  DATE_FORMAT(cons_month, '%Y-%m') ='".$service."' and ten_id='".$theTenant['ten_id']."')  ");
$roomToView->execute();
$rowCount =$roomToView->rowCount();
while($theTenRoom =$roomToView->fetch()){

    $consumeMonth = $db->prepare("SELECT *FROM tbl_consume where DATE_FORMAT(cons_month, '%Y-%m') ='".$service."' and room_id ='".$theTenRoom['room_id']."' ");
    $consumeMonth->execute();
    $theMonth = $consumeMonth->fetch();
    
    $counter++;
    $monthCons=0;
        
    $prevMonth = $db->prepare("SELECT *FROM tbl_consume where room_id ='".$theTenRoom['room_id']."' and cons_month <'".$theMonth['cons_month']."' order by cons_month desc ");
    $prevMonth->execute();
    $thePrevious =$prevMonth->fetch();
   
    $serviceName = $db->prepare("SELECT *FROM tbl_utility where serve_id ='".$theMonth['serve_id']."' and status =1 and u_co ='".$theMonth['co_id']."'");
    $serviceName->execute();
    $theService = $serviceName->fetch();
    
    // the invoice
    $internalInvoice =$conn->prepare("SELECT *from tbl_bill where DATE_FORMAT(invoice_date,'%Y-%m') ='".$service."' and ten_id ='".$tenRef."' and inv_room_id='".$theTenRoom['room_id']."'");
    $internalInvoice->execute();
    $theIntInvoice =$internalInvoice->fetch();  
    
    $cost =$theMonth['qty']*$theService['cost'];
    $originalDate = $theInvoice['invoice_date'];
    $newDate = date("F Y", strtotime($originalDate));
    
    
    
    $sql2INTERnal = $conn->prepare("SELECT SUM(invoice_fees) AS totalinv FROM `tbl_bill` WHERE ten_id = '".$tenRef."' and 
    DATE_FORMAT(invoice_date,'%Y-%m') <'".$service."' and inv_room_id='".$theTenRoom['room_id']."'  ");
                        $sql2INTERnal->execute(array()); // execute the query with parameter $code...
                        
                        $row1INTERnal = $sql2INTERnal->fetch(PDO::FETCH_ASSOC);
                        
                         $sql4INTERnal = $conn->prepare("SELECT SUM(pay_amount) AS totalpay FROM `tbl_pay_bill` WHERE ten_id ='".$tenRef."' 
                         
                         and room_id='".$theTenRoom['room_id']."' and DATE_FORMAT(pay_due_date,'%Y-%m') <='".$nextMonth."' ");
                        $sql4INTERnal->execute(array()); // execute the query with parameter $code...
                        
                        // and DATE_FORMAT(pay_due_date,'%Y-%m') >='".$service."'
                       
                         $row3INTERnal = $sql4INTERnal->fetch(PDO::FETCH_ASSOC);
                      $DIFFINTERnal=$row1INTERnal['totalinv']-$row3INTERnal['totalpay'];
                      
                      if($DIFFINTERnal>=0){
                         $unpaidRoom= number_format($DIFFINTERnal);
                      }
                      else {
                        //  $unpaidRoom="-"; 
                         // $unpaidRoom= number_format($DIFFINTERnal);
                         $unpaidRoom=0; 
                      }
                      $newball=$theIntInvoice['invoice_fees']+$DIFFINTERnal;
                       $newball1=$theIntInvoice['invoice_fees']+$DIFFINTERnal;
                      if($newball>=0){
                          $newball=$newball;
                      }
                      else{
                          $newball=0;
                      }
        
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(20,8,$theTenRoom['room_no'],1);
    $pdf->Cell(30,8,$newDate,1);
    $pdf->Cell(25,8,$thePrevious['index_number'],1);
    $pdf->Cell(25,8,$theMonth['index_number'],1);
    $pdf->Cell(40,8,$theMonth['qty'],1);
    $pdf->Cell(30,8,$theService['cost'],1);
    $pdf->Cell(30,8,$unpaidRoom,1);
    $pdf->Cell(20,8,number_format($theIntInvoice['invoice_fees']),1);
    $pdf->Cell(20,8,number_format($newball1+$DIFFINTERnal),1);
    $sumInvoice +=$theIntInvoice['invoice_fees'];
    
    $pdf->Ln();
    //  echo "one";   
}

 
if($DIFF>1){
    $AllInvo=$DIFF+$sumInvoice;

}

else if($DIFF<-1){
    $AllInvo=$DIFF+$sumInvoice;
    
//     $pdf->SetFont('Arial','B',8);
// $pdf->Cell(220,8,'TOTAL ADVANCE AMOUNT',1);

// $pdf->SetFont('Arial','',10);
// $pdf->Cell(20,8,number_format($DIFF*(-1)),1);
// // $pdf->setY(128);
// $pdf->Ln();
}
else{
  $AllInvo=$sumInvoice;  
}

   

$pdf->SetFont('Arial','B',10);
$pdf->Cell(210,8,'TOTAL AMOUNT VAT INCLUSIVE',1);

$pdf->SetFont('Arial','',10);
$pdf->Cell(30,8,number_format($AllInvo),1);
// $pdf->setY(128);
$pdf->Ln();

//  echo "one"; 
}

// $pdf->SetX(10);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,'Note: To be paid whithin 5 days after reception of this invoice',0,1);

// $pdf->setY(132);
// $pdf->Ln();

$pdf->SetFont('Times','',10);
$pdf->Cell(50,10,'The above amount shall be paid at CHIC Ltd\'s account',0);

$pdf->SetX(100);

$pdf->SetFont('Times','B',10);
$pdf->Cell(50,10,'4075200008567 at EQUITY',0);

// $pdf->setY(136);
$pdf->Ln();

$pdf->SetFont('Times','',10);
$pdf->Cell(0,10,'For any information please contact us at',0);

$pdf->SetX(78);

$pdf->SetFont('Times','B',10);
$pdf->Cell(0,10,'+250 788 301 336',0);

// $pdf->setY(147);
$pdf->Image('img/SIGN.png',120,160,30,30);
// $pdf->setY(179);

$pdf->Ln(10);
$pdf->SetX(103);

$pdf->SetFont('Times','B',10);
$pdf->Cell(60,5,'SEBABO Deus',0,1,'C');

$pdf->SetX(100);

$pdf->Cell(60,5,'Recovery and Customer Relation Manager',0,1,'C');
$n1=$theTenant['te_names'].'UTILITY INVOICE_'.date('Y-m-d');

$pdf->Output($n1.'.pdf','I');
// echo "hello Eric macintosh yayi";
exit;
ob_end_flush();
?>
