<?php
require_once ("../include/initialize.php"); 
$action=$_POST['action'];
$fomDate1=$_POST['fromDate'];
    $toDate1=$_POST['toDate'];
    $payMode1=$_POST['payMode'];
    
if($action=="getDaily"){
    $fomDate=$_POST['fromDate'];
    $toDate=$_POST['toDate'];
    $payMode=$_POST['payMode'];
    if($payMode!="all"){
    $stby = $db->prepare('SELECT * FROM  tbl_accounts where acc_id="'.$payMode.'"');
    $stby->execute();
    $amouby=$stby->fetch();
    $modename=$amouby['acc_mode'];
    }
    else {
       $modename="All Payment Mode"; 
    }
    
    $stParking = $db->prepare('SELECT * FROM  tbl_parking where parking_id="'.$in_park.'"');
    $stParking->execute();
    $amouParking=$stParking->fetch();
    $parkName=$amouParking['parksht_name'];
    
    $parkName=$amouParking['parksht_name'];
    $parkAddress=$amouParking['address'];
    $parkPhone=$amouParking['phone'];
    ?>
    <style>
        .clearfix {
    display: flex;
    justify-content: space-between;
    align-items: left;
    flex-wrap: nowrap;
}

.invoice-sub, .invoice-rate, .invoice-hours, .invoice-subtotal {
    padding: 5px;
    flex: 1;
    text-align: left;
}

.invoice-subtotal span, .invoice-rate span {
    font-weight: 600;
}

    </style>
     <div class="padding-bottom-30 card-box">
					<div class="card-body">
						
						<h4 class="text-center mb-30 weight-600">Total Income Overview</h4>
						<div class="row pb-30">
							<div class="col-md-6">
								<h5 class="mb-15">Starting</h5>
								<p class="font-14 mb-5">From: <strong class="weight-600"><?php echo $fomDate ?></strong></p>
								<p class="font-14 mb-5">To: <strong class="weight-600"><?php echo $toDate ?></strong></p> 
							</div>
							
							<div class="col-md-6">
								<div class="text-right">
									<p class="font-14 mb-5"><?php echo  $parkName;?> </strong></p>
									<p class="font-14 mb-5"><?php echo  $parkAddress;?></p>
									<p class="font-14 mb-5"><?php echo  $parkPhone;?></p>
									<p class="font-14 mb-5">
									    <button class="btn btn-success" type="submit" name="exceldownload" id="exceldownload"><i class='fa fa-file-excel-o'></i>&nbsp;&nbsp;&nbsp;Export </button>	
							
									</p>
								</div>
							</div>
						</div>
						<div class="invoice-desc pb-30">
     <li class="clearfix" style="display: flex; justify-content: space-between; align-items: center;">
    <div class="invoice-sub" style="flex: 1;">Day(s)</div>
    <div class="invoice-rate" style="flex: 1;">Cash</div>
    <div class="invoice-hours" style="flex: 1;">MoMo</div>
    <!--<div class="invoice-hours" style="flex: 1;">Subscriptions</div>-->
    <div class="invoice-subtotal" style="flex: 1;">Total (cash & MoMo)</div>
</li>


							<div class="invoice-desc-body">
								<ul>
								    
								    <?php 
								    
$query=$conn->prepare("select park_in.entre_time from park_in where DATE_FORMAT(entre_time,'%Y-%m-%d')>='".$fomDate."' and DATE_FORMAT(entre_time,'%Y-%m-%d')<='".$toDate."' and in_park='".$in_park."'  group by DATE_FORMAT(entre_time,'%Y-%m-%d')");
$query->execute();
     $i=0;
    $Gtotal=0;
    $GtotaIN=0;
    $GtotaIN2=0;
    $GtotalCash=0;
    $GtotaalMomo=0;
    // $GtotaalSub = 0;
	 	 while($ent_row=$query->fetch()){
	 	     
   $i++;
                          $user=$ent_row['user'];
                          $p_in_id=$ent_row['p_in_id'];
                          $dayOrg=$ent_row['entre_time'];
                          $dayOrgNew=$ent_row['entre_time'];
                          $dayOrg50= date('Y-m-d', strtotime($ent_row['entre_time']));;
                          $dayOrg2= date('d/m/Y', strtotime($ent_row['entre_time']));
                          
                             $sql3="SELECT* FROM tbl_users WHERE account_id ='$user'";
                                        $user=$conn->prepare($sql3);
                                        $user->execute();
                                        $us=$user->fetch();
                  if($in_park== 35){               
                    $selectINCash=$conn->prepare(" select sum(total_paid) as TotalMoneyCash from  park_out where DATE_FORMAT(exit_time,'%Y-%m-%d')='".$dayOrg50."'
                    and out_park='".$in_park."' and total_paid IS NOT NULL and payment_mode=1 and p_in_id IN (select park_in.p_in_id  from park_in where  in_park='".$in_park."') ");
                    $selectINCash->execute(); 
                    $data_rowINCash=$selectINCash->fetch();
                    
                     $selectINmomo=$conn->prepare(" select sum(total_paid) as TotalMoneymomo from  park_out where DATE_FORMAT(exit_time,'%Y-%m-%d')='".$dayOrg50."'
                    and out_park='".$in_park."' and total_paid IS NOT NULL and payment_mode=2 and p_in_id IN (select park_in.p_in_id  from park_in where in_park='".$in_park."') ");
                    $selectINmomo->execute(); 
                    $data_rowINmomo=$selectINmomo->fetch();
         }
         else {

               $selectINCash=$conn->prepare(" select sum(total_paid) as TotalMoneyCash from  park_out where DATE_FORMAT(exit_time,'%Y-%m-%d')='".$dayOrg50."'
                    and out_park='".$in_park."' and total_paid IS NOT NULL and payment_mode=1 and p_in_id IN (select park_in.p_in_id  from park_in where DATE_FORMAT(entre_time,'%Y-%m-%d')='".$dayOrg50."' and in_park='".$in_park."') ");
                    $selectINCash->execute(); 
                    $data_rowINCash=$selectINCash->fetch();
                    
                     $selectINmomo=$conn->prepare(" select sum(total_paid) as TotalMoneymomo from  park_out where DATE_FORMAT(exit_time,'%Y-%m-%d')='".$dayOrg50."'
                    and out_park='".$in_park."' and total_paid IS NOT NULL and payment_mode=2 and p_in_id IN (select park_in.p_in_id  from park_in where DATE_FORMAT(entre_time,'%Y-%m-%d')='".$dayOrg50."' and in_park='".$in_park."') ");
                    $selectINmomo->execute(); 
                    $data_rowINmomo=$selectINmomo->fetch();

         }
                   
                
                    
                    $GtotaIN=$GtotaIN+($data_rowINmomo['TotalMoneymomo']+$data_rowINCash['TotalMoneyCash']);
                    $GtotaIN2=$GtotaIN2+$data_rowIN2;
                    
                    $GtotalCash=$GtotalCash+$data_rowINCash['TotalMoneyCash'];
                    $GtotaalMomo=$GtotaalMomo+$data_rowINmomo['TotalMoneymomo'];
                    // $GtotaalSub=$GtotaalSub+$data_rowINsub['TotalMoneysub'];
                    
                    $momoT=$data_rowINmomo['TotalMoneymomo'];
 ?>

<li class="clearfix" style="display: flex; justify-content: space-between; align-items: left;">
    <div class="invoice-sub" style="flex: 1;"><?php echo $i."."." ".$dayOrg2."#".$datain; ?></div>
    <div class="invoice-rate" style="flex: 1; text-align: left;"><span class="weight-600"><?php echo number_format($data_rowINCash['TotalMoneyCash']); ?></span></div>
    <div class="invoice-hours" style="flex: 1; text-align: left;"><?php echo number_format($data_rowINmomo['TotalMoneymomo']); ?></div>
    <div class="invoice-subtotal" style="flex: 1; text-align: left;"><span class="weight-600"><?php echo number_format($data_rowINmomo['TotalMoneymomo']+$data_rowINCash['TotalMoneyCash']); ?></span></div>
</li>

									<?php   }  ?>
									<li class="clearfix">
    <div class="invoice-sub">G.Total</div>
    <div class="invoice-rate">
        <span class="weight-600"><?php echo number_format($GtotalCash); ?></span>
    </div>
    <div class="invoice-hours">
        <?php echo number_format($GtotaalMomo); ?>
    </div>
    
    <div class="invoice-subtotal">
        <span class="weight-600"><?php echo number_format($GtotalCash + $GtotaalMomo); ?></span>
    </div>
</li>
								</ul>
							</div>
							<div class="invoice-desc-footer">
								<div class="invoice-desc-head clearfix">
									<div class="invoice-sub">Total day: <?php  echo $i ?></div>
									<div class="invoice-rate">Viewed On <?php echo date("d F Y"); ?></div>
									<div class="invoice-subtotal">All Total Income </div> 
								</div>
								<div class="invoice-desc-body">
									<ul>
										<li class="clearfix">
											<div class="invoice-sub">
											</div>
											<div class="invoice-rate font-20 weight-600"> </div>
											<div class="invoice-subtotal"><span class="weight-600 font-24 text-danger"><?php echo number_format($GtotaIN) ?></span></div>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<h4 class="text-center pb-20">Thank You!!</h4>
					</div>
				</div>
				
    <?php
    
}  ?>
<input type="hidden" name="from" id="from" value="<?php echo $fomDate1 ?>">
<input type="hidden" name="to" id="to" value="<?php echo $toDate1 ?>">
<input type="hidden" name="mode" id="mode" value="<?php echo $payMode1 ?>">
<script>
 $("#exceldownload").click(function() {
  var from = $("#from").val();
  var to = $("#to").val();
  var mode = $("#mode").val();
  var load="load";
  window.open("Excell/dailincomeExcell.php?from=" + from + "&to=" + to + "&mode=" + mode +"&action="+load, "_blank");
});

</script>

 

			