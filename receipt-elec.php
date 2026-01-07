<?php
ob_start(); 
require('fpdf16/fpdf.php');
// require('converter/image_converter.php');
// Header('Pragma: public');

$year=date('Y');
// It will be called downloaded.pdf

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
	//$this->SetFillColor(0);
	
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
//Page header
  function Header()
{
    //$this->SetFillColor(230,230,230);
    //  	$this->SetRecttColor(253, 242, 242);
    // 	$this->SetFillColor(80, 200, 120);
    	 // $this->SetDrawColor(80, 200, 120);
        //  $yellow='img/Capture4004.JPG';
        //   $this->Image($yellow,0,0,800);
        //  $this->Rect(12, 20, 188, 245, 'RT');
   
   

}

var $B;
var $I;
var $U;
var $HREF;
function PDF($orientation='L',$unit='mm')
{
    //Call parent constructor
    $this->FPDF($orientation,$unit,array(84.375,86.29166666666666));
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
//Instanciation of inherited class
$pdf=new PDF('P','mm',array(84.375,86.29166666666666));
// $pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->SetAuthor('http://www.itec.rw');
$pdf->SetTitle('Payment Receipt');
  
   require'include/initialize.php';
//echo $_REQUEST['data'];
$lr = substr($_REQUEST['code'],5);
   $dt =  date('Y-m-d'); 
   $nmonth =  date('Y-m'); 
   
  $stms88 = $db->prepare("SELECT sum(pay_amount) as sm FROM  tbl_payment
                 WHERE pay_ref_no='".$lr."' and DATE_FORMAT(pay_trm, '%Y-%m') ='".$nmonth."' 
    			");
   
   	// $stms88 = $db->prepare("SELECT sum(pay_amount) as sm FROM  tbl_pay_bill
    //              WHERE ten_id='".$lr."' and DATE_FORMAT(pay_trm, '%Y-%m') ='".$nmonth."' 
    // 			");
    			$stms88->execute();
    			
    			$rows88 = $stms88->fetch();
    			 $invNo= $rows88['invoice'];
    			 $invoice_fees88= $rows88['invoice_fees'];
    			 $pay_due_date= $rows88['invoice_date'];
    			 	$stms2 = $db->prepare("SELECT * FROM  tbl_tenants
                 WHERE ten_ref_no='".$lr."'
    			");
    			$stms2->execute();
    			$row_count2 = $stms2->fetch();
    			
    			$li_quIPaidNoo = $db->prepare("SELECT SUM(amount) as rent_AMT  FROM  tbl_assign where tent_id ='".$row_count2['ten_id']."' ");
            $li_quIPaidNoo->execute(); 
            $newDoo=$li_quIPaidNoo->fetch();
            $AMTrent=$newDoo['rent_AMT'];
    			 
            	$stms = $db->prepare("SELECT sum(invoice_fees) as sm FROM  tbl_invoice
                 WHERE tbl_invoice.inv_ref_no='".$lr."' and DATE_FORMAT(inv_date, '%Y-%m') ='".$nmonth."' 
    			");
    			$stms->execute();
    			
    			$row_count = $stms->rowCount();
                $newstms=$stms->fetch();    
            $rmain = $newstms['sm']-$rows88['sm'];
            if($rows88['sm']==0){
                $pmt = " - ";
            }
     $pdf->Image('img/Chic_invoiceM.jpg',$x+0,$y-1,87);
      $pdf->SetFont('','',6);
     $pdf->Text($x+61,$y+35.0,'' .$dt.'');
     $pdf->SetFont('','',8);
     $pdf->Text($x+60,$y+41.8,'' .$row_count2['ten_phone'].'');
     $pdf->Text($x+23,$y+41.8,'' .$row_count2['te_names'].'');
     $pdf->Text($x+23.5,$y+47.3,'' .number_format($AMTrent.''));
     $pdf->Text($x+23,$y+52.9,''.$pmt .number_format($rows88['sm'].''));
      if($rmain<0){
                $bal = " + ";
            
     $pdf->Text($x+23,$y+57.3,''.$bal .number_format(($rmain*-1).''));
      }else{
          $pdf->Text($x+23,$y+57.3,'' .number_format(($rmain).''));
      }
     $pdf->SetFont('','',8);
     $pdf->Text($x+35,$y+121.9,''.$collector);
     
     
       $pdf->Image('img/Chic_invoiceM.jpg',$x+0,$y-1,87);
      $pdf->SetFont('','',6);
     $pdf->Text($x+61,$y+35.0,'' .$dt.'');
     $pdf->SetFont('','',8);
     $pdf->Text($x+60,$y+41.8,'' .$row_count2['ten_phone'].'');
     $pdf->Text($x+23,$y+41.8,'' .$row_count2['te_names'].'');
     $pdf->Text($x+23.5,$y+47.3,'' .number_format($AMTrent.''));
     $pdf->Text($x+23,$y+52.9,''.$pmt .number_format($rows88['sm'].''));
      if($rmain<0){
                $bal = " + ";
            
     $pdf->Text($x+23,$y+57.3,''.$bal .number_format(($rmain*-1).''));
      }else{
          $pdf->Text($x+23,$y+57.3,'' .number_format(($rmain).''));
      }
     $pdf->SetFont('','',8);
     $pdf->Text($x+35,$y+121.9,''.$collector);
    
    
    $n1=$Homeleader['l_name'].'_payment_receipt';
     $pdf->Output($n1.'.pdf','I');
    // $pdf->Output();


exit;
ob_end_flush();
?>