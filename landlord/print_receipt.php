<?php

/**
 * 
 * Author @macintosh
 */
ob_start(); 
require_once('../fpdf16/fpdf.php');
require_once('../include/config.php');
 
//  request vars
 $payId =$_REQUEST['payId']; 

/* create a pdf object
and initialize a page
*/
class PDF extends FPDF
{
	function RoundedRect($x, $y, $w, $h, $r, $style = '')
	{
		$k = $this->k;
		$hp = $this->h;
		if($style=='F')
			$op='f';
		elseif($style=='FD' || $style=='DF')
			$op='B';
		else
			$op='S';
		$MyArc = 1 * (sqrt(2) - 1);
		$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
		$xc = $x+$w-$r ;
		$yc = $y+$r;
		$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

		$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
		$xc = $x+$w-$r ;
		$yc = $y+$h-$r;
		$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
		$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
		$xc = $x+$r ;
		$yc = $y+$h-$r;
		$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
		$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
		$xc = $x+$r ;
		$yc = $y+$r;
		$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
		$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
		$this->_out($op);
	}

	function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
	{
		$h = $this->h;
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
			$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
	}
	function Footer()
{
$this->SetY(-60);
	
	//Position at 1.5 cm from bottom
	 $this->SetTextColor(0,0,0);
	 $this->SetFont('Arial','B',10);	
	  
	  	
	$this->SetFont('Arial','B',9);	
	$this->Cell(57,5,'Recovery  Manager',0,1,'C');
	$this->SetFont('Arial','B',11);	
	$this->Cell(57,5,'Vestine UWINEZA',0,1,'C');
	
	$this->SetY(70);
	
// 	$this->Image('../img/667ITEC.png',25,210,40);  
    $this->SetFont('Arial','B',15);
	$this->Ln();

	
	//Position at 1.5 cm from bottom
	 $this->SetTextColor(0,0,0);
	 $this->SetFont('Arial','B',10);	
	  
// 	$this->SetFont('Arial','B',11);	
// 	$this->Cell(160,-5,'GITIFU',0,1,'C');
	
	$this->SetY(-40);
	$this->SetTextColor(0,0,0);
	$this->SetFont('Arial','I',8);	 
	$this->Cell(0,5,"Dear customer you can use your code while paying in BK account no 100080726265   for more info call 0787261616",0,1,'C');
	


    //$this->Image('fpdf16/regsign.gif',15,235,50);
    //$this->SetTextColor(44,144,60);
     $this->SetTextColor(0,0,0);
    $this->SetY(-38);
	$this->Cell(0,10,'_______________________________________________________________________________________________________',0,0,'C');
	$this->Ln(5);
    $this->SetFont('Arial','',10);
	//Position at 1.5 cm from bottom
// 	$this->Cell(0,10,'UTB BANK ACCOUNTS:',0,0,'C');
	$this->SetY(-25);
	$this->SetTextColor(186,53,55);
    $this->SetFont('Arial','BI',8);
	//Position at 1.5 cm from bottom
// 	$this->Cell(0,10,"Powered BY ITEC Ltd",0,0,'C');
	$this->Ln(4);
	$this->SetTextColor(0,0,0);
	$this->SetFont('Arial','I',8);
// 		$this->Cell(0,10,"+250 788 620 612",0,0,'C');
	$this->Ln(4);
	
	$this->SetY(-15);
	$this->SetTextColor(0,0,0);
    $this->SetFont('Arial','I',8);
    
	$this->Ln(4);
}
}
$pdf=new PDF();

$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(20);

$pdf->SetAuthor('https://xode.rw');
$pdf->SetTitle('INVOICE');

// header section

// $pdf->image('img/chic_logo.jpg',10,10,30,30);

$pdf->SetFont('Times','B',20);
$pdf->SetTextColor(50,60,100);
$pdf->Image('../img/OM.jpg', 10, 10, 20, 15);
$pdf->Cell(0,10,'OM Plaza',0,1,'R');

$pdf->SetFont('Times','B',10);
$pdf->SetTextColor(50,60,100);
$pdf->Cell(0,6,'P.O Box U372, Kigali - Rwanda',0,1,'R');

$pdf->SetFont('Times','B',10);
$pdf->SetTextColor(50,60,100);

$pdf->Cell(0,6,'Tel: +250 787 261 616',0,1,'R');
$pdf->Ln(6);
$pdf->Cell(0,6,'TIN:100 213 273',0,1,'R');

$pdf->SetTextColor(53,75,136);
$pdf->Cell(0,6,'uwolive11@gmail.com',0,1,'R');

// $pdf->SetLineWidth(0.5);
// $pdf->Line(40, 38, 240, 38);

// $pdf->setY(41);
$pdf->Ln();
$pdf->SetFont('Times','B',18);
$pdf->SetTextColor(50,60,100);
$pdf->Cell(0,6,'PAYMENT RECEIPT',0,1,'C');

$paymentAsQuery = $conn->prepare("SELECT * FROM tbl_payment WHERE pay_id = :payId");

$paymentAsQuery->bindParam(':payId', $_REQUEST['payId'], PDO::PARAM_INT);
$paymentAsQuery->execute();


if($paymentAsQuery->rowCount()){
    // echo 20;
    $thePayment =$paymentAsQuery->fetch();

    $pdf->Ln();
    $pdf->SetFont('Times','',10); 
    
    $pdf->Cell(80, 6, 'Payment No: '.$thePayment['pay_id'], 1, 0);
    $pdf->Cell(80, 6, 'Payment Date: '.$thePayment['pay_due_date'], 1, 0);



    $tenantDetails =$conn->prepare("select *from tbl_tenants where ten_ref_no = :ten_ref_no");
    
    $tenantDetails->bindParam(':ten_ref_no', $_REQUEST['reference'], PDO::PARAM_STR);
    $tenantDetails->execute();
    
    $total =0;
    if($tenantDetails->rowCount()){
        // echo 30;
        $theTenant =$tenantDetails->fetch();
        $pdf->Ln();
        $pdf->Cell(80, 6, 'Owner: '.$theTenant['te_names'], 1, 0);
        $pdf->Cell(80, 6, 'Phone: '.$theTenant['ten_phone'], 1, 0);
        $pdf->Ln();
        
        $pdf->Cell(80, 6, 'TIN: '.$theTenant['tin_no'], 1, 0);
        $pdf->Cell(80, 6, 'Code: '.$theTenant['ten_ref_no'], 1, 0);
        $pdf->Ln();
    

        $fromAssign =$conn->prepare("select *from tbl_rooms where room_id 
        IN(select room_id from tbl_assign where tent_id = :tent_id)");
        
        $fromAssign->bindParam(':tent_id', $theTenant['ten_id'], PDO::PARAM_STR);
        $fromAssign->execute();
        
        $assigned =$fromAssign->fetch();
    
        $pdf->Ln(8);
        $pdf->Cell(120, 6, 'Description', 1, 0);
        $pdf->Cell(40, 6, 'Amount ', 1, 0);
        
        $dateFromDB = $thePayment['pay_due_date'];  // Example date from the database

        $timestamp = strtotime($dateFromDB);
        $monthName = date('F', $timestamp);
        $total+=$thePayment['pay_amount'];
        
        // echo ;  

        
        $pdf->Ln();
        $pdf->Cell(40, 6, $assigned['room_no'], 1, 0);
        $pdf->Cell(80, 6, ' For: '.$monthName, 1, 0);
        $pdf->Cell(40, 6, number_format($thePayment['pay_amount'])." Rwf", 1, 0);
        
        $pdf->Ln();
        $pdf->Cell(80, 10, 'Total', 1, 0, 'L');
        $pdf->Cell(80, 10, number_format($total)." Rwf", 1, 0, 'R');
        
        $pdf->setX(80);
        $pdf->Ln(20);
        $pdf->Cell(80, 6, 'Done at Kigali '.date('Y-M-d'), 0, 0, 'C');
    }
}




$pdf->Output('Receipt.pdf','I');
// echo "hello Eric macintosh yayi";
exit;
ob_end_flush();
?>
