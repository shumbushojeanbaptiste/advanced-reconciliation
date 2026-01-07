<?php

$monthly1=$_REQUEST['monthly1'];
$monthly2 = $_REQUEST['monthly2'];
?>


<style>
    .modal { display:none; }
    .modal-title {color: #111;}
    td {
        font-size: 12px;
    }
    .title{
        margin:10px;
        font-size:15px;
    }
    .form-group{
        display: inline-flex;
    }
</style>
<div class="span9">
  <div class="content">

 <div class="module">
<div class="col">
  <h2 class="h5 page-title">Expectation Comparison Report</h2>
</div>
<br>
</div><!--/.module-->
</div><!--/.content-->
</div><!--/.span9-->
<div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
        <br><br>
      <div class="form-row">
        <div class="form-group col-md-1">
        </div>
             
  <div name='display_specs' id='display_specs'>
      
      <?php
 require_once ("../include/initialize.php");

// get dates from form
?>
    <!--  <a href="export_MonthlyDetail_rpt.php?monthly=<?php echo $monthly; ?>&date_to=<?php echo $date_to; ?>&cpny=<?php echo $cmp_full; ?>&year_id=<?php echo $year_id  ?>">-->
    <!--<button class="btn btn-success pull-left" style="margin:7px;">Export Excel</button>-->
    <!--</a>-->
    <div class="table-responsive">
   <div class="module-body table">
	<table class="table datatables table-hover" id="dataTable-1" width="100%">
		<thead>
		    
			<tr>
			     <th># </th>
                 <!--<th>Tennant</td>-->
                 <th>Shop No/Room No</th>
                
                 <th><?php 
                 
                 $sqlmo=$db->prepare("SELECT * FROM tbl_month WHERE mon_code='".$monthly1."'");
                 $sqlmo->execute();
                 
                 $rowmo=$sqlmo->fetch();
                 
                 echo $rowmo['month_name'];
                 
                 
                 
                 ?></th>
                   <th><?php 
                 
                 $sqlmo=$db->prepare("SELECT * FROM tbl_month WHERE mon_code='".$monthly2."'");
                 $sqlmo->execute();
                 
                 $rowmo=$sqlmo->fetch();
                 
                 echo $rowmo['month_name'];
                 
                 
                 
                 ?></th>
                 <th>Difference</th>
                 <!--<th></th>-->

			</tr>
			<!--end tr-->
			
		</thead>
		<tbody>
		   <?php
		  
		  // $sql1=$db->prepare("SELECT * from tbl_tenants INNER JOIN tbl_assign ON tbl_tenants.ten_id=tbl_assign.tent_id 
		  // INNER JOIN tbl_rooms ON tbl_assign.room_id=tbl_rooms.room_id INNER JOIN tbl_invoice ON tbl_tenants.ten_ref_no=tbl_invoice.inv_ref_no WHERE tbl_tenants.ten_staff=10  GROUP  BY tbl_tenants.te_names  ");
		  // $sql1->execute();
		   
		      
		  //       while($row2=$sql1->fetch()){
		       
		       
    $sqlrooms=$db->prepare("SELECT * FROM tbl_rooms INNER JOIN tbl_assign ON tbl_rooms.room_id=tbl_assign.room_id inner join tbl_tenants on tbl_tenants.ten_id= tbl_assign.tent_id inner join
    tbl_invoice on tbl_invoice.inv_ref_no=tbl_tenants.ten_ref_no  where tbl_rooms.company_id=10  group by room_no ");
    $sqlrooms->execute();
    
    $m1=0;
    $m2=0;
    $b=0;
    while($rowrooms=$sqlrooms->fetch()){
    $a++;
		       
		    ?>
		       
		  	 
		      <tr class="odd gradeX">
		          
		    <td><?php echo $a?></td>
		   
		     <td><?php
		      
		    
		          
		          
		          echo "[".$rowrooms['room_no']."]";
		      
		     
		     ?></td>
		     
		         <td>
		         
		       <?php 
		       
		       $sqlfeb=$db->prepare("SELECT invoice_fees  FROM tbl_invoice where inv_room_id='".$rowrooms['room_id']."'  AND DATE_FORMAT(invoice_date,'%m')='".$monthly1."' ");
		       $sqlfeb->execute();
		       
		       $rowfeb=$sqlfeb->fetch();
		     
		       echo number_format($rowfeb['invoice_fees']);
		       $m1=$m1+$rowfeb['invoice_fees'];
		       
		       ?> 
		         
		     </td>
		      <td>
		         
		       <?php 
		       
		       $sqlfeb1=$db->prepare("SELECT invoice_fees  FROM tbl_invoice where inv_room_id='".$rowrooms['room_id']."' AND DATE_FORMAT(invoice_date,'%m')='".$monthly2."' ");
		       $sqlfeb1->execute();
		       
		       $rowfeb1=$sqlfeb1->fetch();
		     
		           
		           echo number_format($rowfeb1['invoice_fees']);
		     $m2=$m2+$rowfeb1['invoice_fees'];
		       ?> 
		         
		     </td>
		   
		   <td>
		       <?php
		      // if($rowfeb['invoice_fees']==""){
		           
		      //     $rowfeb['invoice_fees']=0;
		      // }
		      // elseif($rowfeb1['invoice_fees']==""){
		      //  $rowfeb1['invoice_fees']=0;   
		           
		      // }
		       $difffK=$rowfeb['invoice_fees']-$rowfeb1['invoice_fees'];
		       if($difffK<0){
		         $ddd=$difffK*(-1);  
		       }
		      else{
		        $ddd=$difffK*(1);     
		      }
		       echo number_format($ddd);
		      
		       ?>
		       </td>
		    
		 
            </tr>
            
 <?php
		        
		    
    }
		        
		   ?>
		   
		      
		       
		       <tr>
		           
		        <td colspan="2"><b>Total</b></td>  <td><b><?php   echo number_format($m1); ?></b></td> <td><b><?php  echo number_format($m2); ?></b></td> 
		        <td><b><?php  echo number_format($TLast); ?></b></td>
		      </tr>
		
		 </tbody>
		 
	    </table>
    </div>
 </div>
      
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->


