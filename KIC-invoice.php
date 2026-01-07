<?php
ob_start(); 
require('fpdf16/fpdf.php');
Header('Pragma: public');
 
//print watermark


class PDF_Rotate extends FPDF
{
var $angle=0;
function Rotate($angle,$x=-1,$y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));   
	}
}

function _endpage()
{
	if($this->angle!=0)
	{
		$this->angle=0;
		$this->_out('Q');
	}
	parent::_endpage();
}

}
//inherits watermark to pdf
class PDF extends PDF_Rotate
{
//barcode generation
function Code39($xpos, $ypos, $code, $baseline=0.5, $height=5){
	$wide = $baseline;
	$narrow = $baseline / 3 ; 
	$gap = $narrow;
	$barChar['0'] = 'nnnwwnwnn';
	$barChar['1'] = 'wnnwnnnnw';
	$barChar['2'] = 'nnwwnnnnw';
	$barChar['3'] = 'wnwwnnnnn';
	$barChar['4'] = 'nnnwwnnnw';
	$barChar['5'] = 'wnnwwnnnn';
	$barChar['6'] = 'nnwwwnnnn';
	$barChar['7'] = 'nnnwnnwnw';
	$barChar['8'] = 'wnnwnnwnn';
	$barChar['9'] = 'nnwwnnwnn';
	$barChar['A'] = 'wnnnnwnnw';
	$barChar['B'] = 'nnwnnwnnw';
	$barChar['C'] = 'wnwnnwnnn';
	$barChar['D'] = 'nnnnwwnnw';
	$barChar['E'] = 'wnnnwwnnn';
	$barChar['F'] = 'nnwnwwnnn';
	$barChar['G'] = 'nnnnnwwnw';
	$barChar['H'] = 'wnnnnwwnn';
	$barChar['I'] = 'nnwnnwwnn';
	$barChar['J'] = 'nnnnwwwnn';
	$barChar['K'] = 'wnnnnnnww';
	$barChar['L'] = 'nnwnnnnww';
	$barChar['M'] = 'wnwnnnnwn';
	$barChar['N'] = 'nnnnwnnww';
	$barChar['O'] = 'wnnnwnnwn'; 
	$barChar['P'] = 'nnwnwnnwn';
	$barChar['Q'] = 'nnnnnnwww';
	$barChar['R'] = 'wnnnnnwwn';
	$barChar['S'] = 'nnwnnnwwn';
	$barChar['T'] = 'nnnnwnwwn';
	$barChar['U'] = 'wwnnnnnnw';
	$barChar['V'] = 'nwwnnnnnw';
	$barChar['W'] = 'wwwnnnnnn';
	$barChar['X'] = 'nwnnwnnnw';
	$barChar['Y'] = 'wwnnwnnnn';
	$barChar['Z'] = 'nwwnwnnnn';
	$barChar['-'] = 'nwnnnnwnw';
	$barChar['.'] = 'wwnnnnwnn';
	$barChar[' '] = 'nwwnnnwnn';
	$barChar['*'] = 'nwnnwnwnn';
	$barChar['$'] = 'nwnwnwnnn';
	$barChar['/'] = 'nwnwnnnwn';
	$barChar['+'] = 'nwnnnwnwn';
	$barChar['%'] = 'nnnwnwnwn';
	$this->SetFont('Arial','',10);
	$this->Text($xpos, $ypos + $height + 4, $code);
	$this->SetFillColor(0);
	$code = '*'.strtoupper($code).'*';
	for($i=0; $i<strlen($code); $i++){
		$char = $code[$i];
		if(!isset($barChar[$char])){
			$this->Error('Invalid character in barcode: '.$char);
		}
		$seq = $barChar[$char];
		for($bar=0; $bar<9; $bar++){
			if($seq[$bar] == 'n'){
				$lineWidth = $narrow;
			}else{
				$lineWidth = $wide;
			}
			if($bar % 2 == 0){
				$this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
			}
			$xpos += $lineWidth;
		}
		$xpos += $gap;
	}
}
function RotatedText($x, $y, $txt, $angle)
{
	//Text rotated around its origin
	$this->Rotate($angle,$x,$y);
	$this->Text($x,$y,$txt);
	$this->Rotate(0);
}
//Page header
function Header()
{
    
 
		$this->Image('img/kicLogo.jpg',20,10,40);
		$this->SetFont('Arial','B',15);

		 /************************************************/
		 $this->SetX(70);
		 $this->SetFont('Arial','',13);
		 $this->SetTextColor(55,24,60);
// 		 $this->SetTextColor(44,144,60);
		 $this->Cell(120,5,'KIC',0,0,'R');
		 $this->Ln(6);
		 $this->SetX(70);
		 $this->Cell(120,5,'',0,0,'R');
		 $this->Ln(6);
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(120,5,'P.O Box 1429, Kigali - Rwanda',0,0,'R');
		 $this->Ln(5);
		 /************************************************/
		 $this->SetTextColor(0,0,0);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(120,5,'Tel: +250 788 357 903',0,0,'R');
		 $this->Ln(5);
		 $this->SetX(70);
// 		 $this->Cell(120,5,'Tel: (+250) - 788306203',0,0,'R');
		  /************************************************/
		 $this->Ln(5);
		 $this->SetX(70);
		 $this->Cell(120,5,'TIN:101 391 684',0,0,'R');
		 $this->Ln(5);
		 $this->SetTextColor(53,75,136);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(120,5,'gkayumba2001@yahoo.com',0,0,'R');
		 $this->Ln(5);
		 
		 $this->SetTextColor(53,75,136);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(120,5,'www.kic.rw ',0,0,'R');
		 $this->Ln(5);
		 
	 /************************************************/
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','BU',13);
		$this->Cell(200,20,'PAYMENT NOTICE',0,0,'C');  
		$this->Ln(15);
    //Line break
     //$this->Cell(60);
	//Put the watermark
	//$this->SetFont('Arial','B',35);
	//$this->SetTextColor(255,192,203);

	//$this->RotatedText(25,200,'UTB Management Information System',45);
	//$this->Ln(30);
	//Put the watermark
	//$this->SetFont('Arial','B',70);
	//$this->SetTextColor(255,192,203);

	//$this->RotatedText(60,200,'Duplicate',45);
	
	
}
//Page footer
function Footer()
{
$this->SetY(-60);
	
	//Position at 1.5 cm from bottom
	 $this->SetTextColor(0,0,0);
	 $this->SetFont('Arial','B',10);	
	  
	  	
	$this->SetFont('Arial','B',9);	
	$this->Cell(57,5,'Managing Director',0,1,'C');
	$this->SetFont('Arial','B',11);	
	$this->Cell(57,5,'KAYUMBA Godfrey',0,1,'C');
	
	$this->SetY(70);    
	
// 	$this->Image('img/SIGN.png',25,210,40);
    $this->SetFont('Arial','B',15);

	$this->SetX(70);
	
	//Position at 1.5 cm from bottom
	 $this->SetTextColor(0,0,0);
	 $this->SetFont('Arial','B',10);	
	  
// 	$this->SetFont('Arial','B',11);	
// 	$this->Cell(160,-5,'GITIFU',0,1,'C');
	
	$this->SetY(-40);
	$this->SetTextColor(0,0,0);
	$this->SetFont('Arial','I',8);	 
	$this->Cell(0,5,"The above amount shall be pay on KIC's account number 0004-000337212-73 at BK",0,1,'C');


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
	$this->Cell(0,10,"Powered BY ITEC Ltd",0,0,'C');
	$this->Ln(4);
	$this->SetTextColor(0,0,0);
	$this->SetFont('Arial','I',8);
		$this->Cell(0,10,"+250 788 620 612",0,0,'C');
	$this->Ln(4);
// 	$this->Cell(0,10,'ECOBANK:00100138008901-01 BPR:583-4103012101-11, GTBANK:220/199432/1/5118/0(FRW)',0,0,'C');

	$this->SetY(-15);
	$this->SetTextColor(0,0,0);
    $this->SetFont('Arial','I',8);
	//Position at 1.5 cm from bottom
// 	$this->Cell(0,10,'Gisenyi Campus: COGEBANK:002-01390142689-03(FRW), BK:00040-06933105-69(FRW), BPR:583-410301210252(FRW),',0,0,'C');
	$this->Ln(4);
// 	$this->Cell(0,10,'ECOBANK:0010133800890101(FRW), GTBANK:220/199432/1/5118/1(FRW)',0,0,'C');
}
var $B;
var $I;
var $U;
var $HREF;
function PDF($orientation='P',$unit='mm',$format='A4')
{
    //Call parent constructor
    $this->FPDF($orientation,$unit,$format);
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
}
function WriteHTML($html)
{
    //HTML parser
    $html=str_replace("\n",' ',$html);
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            //Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extract attributes
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}
function OpenTag($tag,$attr)
{
    //Opening tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF=$attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}
function CloseTag($tag)
{
    //Closing tag
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
}
function SetStyle($tag,$enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}
function PutLink($URL,$txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}


$pdf=new PDF();

$pdf->SetAuthor('https://xode.rw');
$pdf->SetTitle('INVOICE');


//database code
 require'include/initialize.php';

//PDF page content

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->SetFont('Arial','B',8);
$lr = substr($_REQUEST['code'],5);
// $xxode = substr($_REQUEST['code'],5);
	$stms88 = $db->prepare("SELECT SUM(invoice_fees) as invAMT,invoice_date  FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$lr."' and DATE_FORMAT(invoice_date, '%Y-%m') ='".$_REQUEST['xode']."'  group by tbl_invoice.invoice_date 
    			");
    			$stms88->execute();
    			$rows88 = $stms88->fetch();
    			 $invNo= $rows88['invoice'];
    			 $invoice_fees88= $rows88['invAMT'];
    			 $pay_due_date= $rows88['invoice_date'];
	$stms = $db->prepare("SELECT * FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$lr."'   group by tbl_invoice.invoice_date 
    			");
    			$stms->execute();
    			
    			$row_count = $stms->rowCount();
    	$stms2 = $db->prepare("SELECT * FROM  tbl_tenants
                 WHERE ten_ref_no='".$lr."'
    			");
    			$stms2->execute();
    			$row_count2 = $stms2->fetch();
    	$orgDateD =$pay_due_date."-3"; $newDateD = date("d F Y", strtotime($orgDateD));		
    			    
	 $pdf->Cell(12,6,'');
	 $pdf->Cell(82,6,'Invoice No: '.strtoupper($invNo),1); 
 	 $pdf->Cell(82,6,'Invoice Date: '.$newDateD,1,0); 
 	 $pdf->Ln();
     $pdf->Cell(12,6,''); 
	 $pdf->Cell(82,6,'Shop/Office by: '.strtoupper($row_count2['te_names']),1); 
	 if ($level=="2,3")$pdf->Cell(82,6,'Level: 2 & 3',1,0); 
	 else if ($level=="5,4")$pdf->Cell(82,6,'Level: 4 & 5',1,0); 
	 else $pdf->Cell(82,6,'Phone: '.$row_count2['ten_phone'],1,0); 	
	
	 $pdf->Ln();
	 $pdf->Cell(12,6,'');  
	 $pdf->Cell(82,6,'TIN NO: '.strtoupper($row_count2['tin_no']),1);
	 $pdf->Cell(82,6,'Code: '.$row_count2['ten_ref_no'],1,0); 
	 $pdf->Ln();
	 
     $pdf->Cell(12,6,''); 
	 $pdf->Cell(82,6,"Reperesented by: ".$row_count2['leader_name'],1);
     $pdf->Cell(82,6,'Monthly amount: '.number_format($invoice_fees88).' Frw',1,0); 
     $pdf->Ln();





//      $pdf->Cell(12,6,''); 
// 	 $pdf->Multicell(164,6,$Homeleadercon['categ_name'].": ".$Homeleadercat['categ_name']." , ".number_format($Homeleadercon['fees'])." Rfw",1);
	 
	 $pdf->Ln();

  
     $pdf->Ln();
	 $pdf->Cell(12,6,'');
	 $pdf->Cell(30,6,'Shop/room code',1,0);
	 $pdf->Cell(82,6,'Invoice description: ',1);
	 
	 $pdf->Cell(52,6,'Amount',1,0); 
     $pdf->Ln();
     if(empty($Total)){  $amount =0;}else{ $amount = number_format($Total); };
     if(empty($Total2)){  $amount2 =0;}else{ $amount2 = number_format($Total2); };
     if($Homeleaderinv ==0){
     $remain = $Total - $Total2 + $contotal;
     }else{
     $remain = $Total - $Total2;
         
     }
     if($row_count2>0){
    			    
    			    $tot=0;
    			    $remain77=0;	$remain770=0;$unBalnace=0;
    			    
    			    $stms88INV = $db->prepare("SELECT SUM(invoice_fees) as IinvAMT,invoice_date  FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$lr."'  and DATE_FORMAT(invoice_date, '%Y-%m') <'".$_REQUEST['xode']."' ");
    			$stms88INV->execute();
    			$rows88INV = $stms88INV->fetch();
    			
    			 $stms177PAY = $db->prepare("SELECT sum(pay_amount) as smPAY from tbl_payment where pay_ref_no='".$lr."'  and DATE_FORMAT(pay_due_date, '%Y-%m') <'".$_REQUEST['xode']."' ");
    			$stms177PAY->execute();
    			$rows177PAY = $stms177PAY->fetch();
    			
    			$amaden=$rows88INV['IinvAMT']-$rows177PAY['smPAY'];
    			
    			if($amaden>0){
    			  $unBalnace=$amaden;
    			    $pdf->Cell(12,6,'');
                  $pdf->Cell(30,6,''.$rowsr.' ',1,0);
            	 $pdf->Cell(82,6,'Total Unpaid Balance ',1);
            	 
            	 $pdf->Cell(52,6,''.number_format($amaden).' Frw',1,0);   
                 $pdf->Ln();
    			    
    			}
    			else{
    			    $unBalnace=0;  
    			} 
    			
    			
    	
    	
    			
    			
    			while ($rows = $stms->fetch(PDO::FETCH_ASSOC)){
    			    $pay_id= $rows['invoice_id'];
    			    $invNo= $rows['invoice'];
    			    $pay_due_date= $rows['invoice_date'];
    			    $i++;
    			   
    			   
    			
      $stms1 = $db->prepare("SELECT * FROM  tbl_invoice
    	       
                 INNER JOIN tbl_tenants ON tbl_invoice.inv_ref_no=tbl_tenants.ten_ref_no
                 WHERE  tbl_invoice.inv_ref_no='".$lr."' and  tbl_invoice.invoice_date='".$pay_due_date."' and auto='yes'
    			");
    			$stms1->execute();
    			$row_count1 = $stms1->rowCount(); 
    			$remain=0;
    			
    			
    			
    			
    		
    			while ($rows1 = $stms1->fetch(PDO::FETCH_ASSOC)){
    			    
    			 $stmsrommCode = $db->prepare("SELECT * from tbl_rooms where room_id='".$rows1['inv_room_id']."'
    			");
    			$stmsrommCode->execute();
    			$rowsrommCode = $stmsrommCode->fetch();    
    			     if($rows1['auto']=='yes'){
                            $invTyp= "Monthly";
                        }
                        else{
                            $invTyp= "Special";
                        }
                        
                     $tot =$tot + $rows1['invoice_fees'];  
                     $stms177 = $db->prepare("SELECT sum(pay_amount) as sm from tbl_payment where pay_ref_no='".$lr."' and rent_date='".$rows1['invoice_date']."'
    			");
    			$stms177->execute();
    			$rows177 = $stms177->fetch();
    			$date=date_create($rows1['invoice_date']);
    // 			$remain = $rows1['invoice_fees']-$rows177['sm'];
    			$remain = $rows1['invoice_fees'];
    			if($remain!=0){
                 $pdf->Cell(12,6,'');
                  $pdf->Cell(30,6,''.$rowsrommCode['room_no'].' ',1,0);
            	 $pdf->Cell(82,6,'Rent for '.date_format($date,'F , Y'),1);
            	 
            	 $pdf->Cell(52,6,''.number_format($remain).' Frw',1,0); 
                 $pdf->Ln();
    			}
                 $remain770 = $remain770 + $remain;       
    			}
    			$remain77 = $remain77 + $remain;
    			}
    			    
    			}
	 
     
// 	 $pdf->Cell(12,6,'');
// 	 $pdf->Cell(82,6,'Rent for Dec ',1);
// 	 $pdf->Cell(82,6,''.$amount2.' Rfw',1,0); 
//      $pdf->Ln();
	 $pdf->Cell(12,6,'');
// 	 $pdf->Cell(82,10,'Tax ',1);
	 $pdf->Cell(164,10,'Including Tax(18%)',1,0,'C'); 
     $pdf->Ln();
     $pdf->SetFont('Arial','B',10);
	 $pdf->Cell(12,6,'');
	 $pdf->Cell(82,20,'Total ',1);
	 $pdf->Cell(82,20,''.number_format($remain770+$unBalnace).' Frw',1,0,'R');   
	 $pdf->Ln();
	 $pdf->Ln();
	
	
	
	//$pdf->Cell(0,5,'Issued at KIGALI on: '.$dat,0,1,'C');
	$pdf->Cell(0,5,'Issued at '.$sect1['namesector'].' Kigali on: '.date("d-M-Y",time()),0,1,'C');
    $name1 = $row_count2['te_names'];
	 $n1=$name1.'_Invoice_'.date('Y-m-d');
	 $pdf->Output($n1.'.pdf','I');

	exit;
ob_end_flush();
?>
