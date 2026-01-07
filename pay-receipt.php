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
    
 
		$this->Image('img/chic_logo.jpg',20,10,40);
		$this->SetFont('Arial','B',15);

		 /************************************************/
		 $this->SetX(70);
		 $this->SetFont('Arial','',13);
		 $this->SetTextColor(55,24,60);
// 		 $this->SetTextColor(44,144,60);
		 $this->Cell(120,5,'CHIC',0,0,'R');
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
		 $this->Cell(120,5,'Tel: +250 788 303 651',0,0,'R');
		 $this->Ln(5);
		 $this->SetX(70);
// 		 $this->Cell(120,5,'Tel: (+250) - 788306203',0,0,'R');
		  /************************************************/
		 $this->Ln(5);
		 $this->SetX(70);
		 $this->Cell(120,5,'TIN:102 456 160',0,0,'R');
		 $this->Ln(5);
		 $this->SetTextColor(53,75,136);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(120,5,'chic_ltd@ymail.com or camaradeo@gmail.com',0,0,'R');
		 $this->Ln(5);
		 
		 $this->SetTextColor(53,75,136);
		 $this->SetFont('Arial','',8);
		 $this->SetX(70);
		 $this->Cell(120,5,'www.chic.rw ',0,0,'R');
		 $this->Ln(5);
		 
	 /************************************************/
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial','BU',13);
		$this->Cell(200,20,'PAYMENT RECEIPT',0,0,'C');
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
	  
	  	
	$this->SetFont('Arial','I',9);	
	$this->Cell(57,5,'Recovery and Customer Relation Manager',0,1,'C');
	$this->SetFont('Arial','B',11);	
	$this->Cell(57,5,'Deus SEBABO ',0,1,'C');
	
	$this->SetY(70);
	
	$this->Image('img/SIGN.png',25,210,40);
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
	$this->Cell(0,5,"Dear customer you can use your code while paying in COGEBANK account no 0130-1057830-36  for more info call 0788301336",0,1,'C');
	


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
	$this->SetTextColor(0,0,0);
    $this->SetFont('Arial','I',8);
	//Position at 1.5 cm from bottom
// 	$this->Cell(0,10,"The above amount shall be paid at CHIC's account number 00040-0373659-48 at Bank of Kigali",0,0,'C');
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
$pdf->SetTitle('Receipt');


//database code
 require'include/initialize.php';

//PDF page content

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->SetFont('Arial','B',8);
$lr = substr($_REQUEST['code'],5);
$date=$_REQUEST['dat'];
// $xxode = substr($_REQUEST['code'],5);
	
	//finfing the tennant descriptions
	
	$sqlten=$db->prepare("SELECT * FROM tbl_tenants inner join tbl_payment on tbl_payment.pay_ref_no=tbl_tenants.ten_ref_no where ten_ref_no='".$lr."' and
	DATE_FORMAT(pay_due_date ,'%Y-%m-%d') ='".$date."' 
	");
	$sqlten->execute();
	
	$fetchdata=$sqlten->fetch();
	
	
	//calculating the tennant umwenda 
	
	//total invoice
	 $stmt1 = $db->prepare("SELECT SUM(invoice_fees) AS invoice_fees FROM  tbl_invoice where inv_ref_no='".$lr."' and DATE_FORMAT(invoice_date ,'%Y-%m-%d')<'".$date."'   ");
                $stmt1->execute();
                
                $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                $sumtot1 = $row1['invoice_fees'];
                
                //total payment 
              $stmt = $db->prepare("SELECT SUM(pay_amount) AS pay_amount FROM  tbl_payment where pay_ref_no='".$lr."' and DATE_FORMAT(pay_due_date ,'%Y-%m-%d') <'".$date."'  ");
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $sumtot = $row['pay_amount'];
                
                $balance=$sumtot1-$sumtot;
                
                
	
	
	
	
    			    
	 $pdf->Cell(12,6,'');
	 $pdf->Cell(82,6,'Pay Ref No: '.strtoupper($fetchdata['slip_no']),1); 
 	 $pdf->Cell(82,6,'Payment Date: '.$date,1,0); 
 	 $pdf->Ln();
     $pdf->Cell(12,6,''); 
	 $pdf->Cell(82,6,'Shop/Office by: '.strtoupper($fetchdata['te_names']),1); 
	 if ($level=="2,3")$pdf->Cell(82,6,'Level: 2 & 3',1,0); 
	 else if ($level=="5,4")$pdf->Cell(82,6,'Level: 4 & 5',1,0); 
	 else $pdf->Cell(82,6,'Phone: '.$fetchdata['ten_phone'],1,0); 	
	
	 $pdf->Ln();
	 $pdf->Cell(12,6,'');  
	 $pdf->Cell(82,6,'TIN NO: '.strtoupper($fetchdata['tin_no']),1);
	 $pdf->Cell(82,6,'Tennant ref No: '.$fetchdata['ten_ref_no'],1,0); 
	 $pdf->Ln();
	 
     $pdf->Cell(12,6,''); 
	 $pdf->Cell(82,6,"Reperesented by: ",1);
     $pdf->Cell(82,6,''.$fetchdata['leader_name'].'',1,0); 
     $pdf->Ln();





//      $pdf->Cell(12,6,''); 
// 	 $pdf->Multicell(164,6,$Homeleadercon['categ_name'].": ".$Homeleadercat['categ_name']." , ".number_format($Homeleadercon['fees'])." Rfw",1);
	 
	 $pdf->Ln();

  
     $pdf->Ln();
	 $pdf->Cell(12,6,'');
	 //$pdf->Cell(30,6,'Shop/room code',1,0);
// 	 $pdf->Cell(112,6,'Payment description: ',1);
	 
// 	 $pdf->Cell(52,6,'Amount',1,0); 
    //  $pdf->Ln();
    
    //querrys==============================================
	
	 $stmt1 = $db->prepare("SELECT * FROM  tbl_payment where pay_ref_no='".$lr."' and DATE_FORMAT(pay_due_date ,'%Y-%m-%d')='".$date."'");
     $stmt1->execute();
     
     $fetro=$stmt1->fetch();
     
                
               
                
	 
     
// 	 $pdf->Cell(12,6,'');
// 	 $pdf->Cell(82,6,'Rent for Dec ',1);
// 	 $pdf->Cell(82,6,''.$amount2.' Rfw',1,0); 
//      $pdf->Ln();
	 $pdf->Cell(12,6,'');
// 	 $pdf->Cell(82,10,'Tax ',1);
// 	 $pdf->Cell(164,10,'Including Tax(18%)',1,0,'C'); 
     $pdf->Ln();
     $pdf->SetFont('Arial','B',10);
	 $pdf->Cell(12,6,'');
	$pdf->Cell(82,20,'Total unpaid before paying    '.number_format($balance).' Frw',1,0,'L'); 
// 	  $pdf->Cell(82,20,"Paid fees: ".$fetro['pay_amount'],1);
	 $pdf->Cell(82,20,'Payed money              '.number_format($fetro['pay_amount']).' Frw',1,0,'L');   
	 
	 $grandbal=$balance-$fetro['pay_amount'];
	 
	 $pdf->Ln();
	 $pdf->Cell(12,6,'');
	 $pdf->Cell(82,20,'Balance    '.number_format($grandbal).' Frw',1,0,'L'); 
// 	  $pdf->Cell(82,20,"Paid fees: ".$fetro['pay_amount'],1);
// 	 $pdf->Cell(82,20,'Payed money              '.number_format($fetro['pay_amount']).' Frw',1,0,'R');   
	 
	 
	 $pdf->Ln();
	 $pdf->Ln();
	 
// 	$pdf->Cell(0,5,'Issued at '.$sect1['namesector'].' Kigali on: '.date("d-M-Y",time()),0,1,'C');
	 
	
	
	//$pdf->Cell(0,5,'Issued at KIGALI on: '.$dat,0,1,'C');
	$pdf->Cell(0,5,'Issued at '.$sect1['namesector'].' Kigali on: '.date("d-M-Y",time()),0,1,'C');
    $name1 = $row_count2['te_names'];
	 $n1=$name1.'_Invoice_'.date('Y-m-d');
	 $pdf->Output($n1.'.pdf','I');

	exit;
ob_end_flush();
?>
