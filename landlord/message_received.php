<?php 

/**
 * 
 * Author @macintosh
 */

 require_once ("../include/initialize.php");
 ?>
<div class="row">
    <?php
    if(!empty($msg)) {
        ?>
    	<div class="alert alert-success">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Well done!</strong> <?php echo $msg; ?> 
    	</div>
        <?php
    }
    else if(!empty($msge)) {
        ?>
    	<div class="alert alert-danger" role="alert">
            <span class="fe fe-minus-circle fe-16 mr-2"></span> <?php echo $msge; ?> 
        </div>
            <?php
    }
    ?>
    <div class="col-md-12"><br><br>
        <div class="card shadow mb-4">
            <div class="card-body">
                <br><br>
                <div id="resultedOK">
                    <?php 
                
                    $monthly=$_REQUEST['date'];
                    $stms = $db->prepare("SELECT * FROM  tbl_invo_link   WHERE tbl_invo_link.ten_ref in(select tbl_tenants.ten_ref_no from tbl_tenants) and
                    tbl_invo_link.ten_ref in (select  tbl_invoice.inv_ref_no from 
                    tbl_invoice where DATE_FORMAT( tbl_invoice.invoice_date,'%Y-%m') ='".$monthly."')  and  	tbl_invo_link.co_id='".$company."'
                    group by tbl_invo_link.ten_ref ");
            		$stms->execute();
    		
                    // 		 WHERE 
                    //         tbl_invo_link.tenant_reference in (select tbl_bill.ten_id from tbl_bill where DATE_FORMAT(tbl_bill.invoice_date,'%Y-%m') ='".$monthly."') 
    			    $row_count = $stms->rowCount();
    	    
    			    if($row_count>0){
                        ?>
     
                        <div class="card-header">
                            <strong class="card-title"><?php echo $row_count2['te_names'];?></strong>
                            <a class="float-right small text-muted" href="#!"></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-borderless" id="" style="font-size:11px;">
                                <thead>
                                    <tr>
                                        <th>Serial</th>
                                         <th>Tenant Names</th>
                                         <th>Receiver Phone</th>
                                         <th>Message Date</th>
                                         <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tot=0;
                        			$i =0;
                        			while ($rows = $stms->fetch(PDO::FETCH_ASSOC)):
                        			    $i++;
                        			    
                        			    
                            	        $stms2 = $db->prepare("SELECT * FROM  tbl_tenants WHERE ten_ref_no='".$rows['ten_ref']."'");
                            			$stms2->execute();
                            			$row_count2 = $stms2->fetch();
                        			
                            			$currency_id=$row_count2['currency_id'];
                            			
                            		    $li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
                                        $li_quIPaidNMoney->execute(); 
                                        $newDMoney=$li_quIPaidNMoney->fetch();
                                        
                                        $converted=$newDMoney['intoR'];
                                    
                        			    $stms1 = $db->prepare("SELECT * FROM  tbl_tenants
                                        INNER JOIN tbl_invo_link ON tbl_invo_link.ten_ref=tbl_tenants.ten_ref_no
                                        WHERE  tbl_invo_link.ten_ref='".$rows['ten_ref']."' and 
                                         tbl_invo_link.ten_ref in (select  tbl_invoice.inv_ref_no from  tbl_invoice where DATE_FORMAT( tbl_invoice.invoice_date, '%Y-%m') ='".$monthly."') group by tbl_invo_link.li_date");
                            			$stms1->execute();
                            			$row_count1 = $stms1->rowCount();
                                        $linkId =$rows['link_id'];
                                        ?>
                                        <tr>
                                            <td ><?php echo $i;?></td>
                                            <td >
                                                <?php 
                                                    $orgDate = $rows['link_date']; $newDate = date("d F Y", strtotime($orgDate));
                                                   // echo $newDate;
                                                   echo $row_count2['te_names']; ?>
                                            </td>
                                            <td>
                                                <?php echo $rows['receiced_phone'];?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo $rows['recorded'];
                                                    
                                                ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary float-left" data-toggle="modal" data-target="#read-message" title="Read Message" id="div-modal" ><i class="fe fe-file"> Read</i></button>
                                            </td>
                                        </tr>
                                        <?php 
                                        include('readMeassage.php');
                                    endwhile;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                    else{
                        ?>
                        <br />
                    	<center>
                    		<div class="alert alert-success" role="alert" style="width:50%;">
                        		<strong>
                        		</strong> No Data . <?php echo $monthly."#".$row_count2; ?>
                    		</div>
                    	</center><br />
                    	<?php 
                    }
	                ?>
		        </div>
            </div> <!-- /.card-body -->
        </div> <!-- /.card -->
    </div> <!-- /.col -->
</div>
<script>
$(document).ready(function(){
    $("#search-box").keyup(function(){
        $.ajax({
        type: "POST",
        url: "load_slip_search_result2",
        data:'keyword1='+$(this).val(),
        beforeSend: function(){
        },
        success: function(data){
            $("#suggesstion-box").show();
            $("#suggesstion-box").html(data);
            $("#search-box").css("background","#FFF");
             $("#result-form-search").hide();
             $("#resultedOK").hide();
             
        }
        });
    });
      $("#train_save").click(function (){
         //$("#edit_education").html(data);
        // var v = $("#search-box").val(); 
         var edu = $("#edit_ed").val(); 
        window.location.href = "bill-link?edit_education=" + edu  + '&keyword1=' +edu;
        
        return false;
        $("#editaaa").hide();
    });
    
    $("#hrdSearch").click(function (){
        var v = $("#search-box").val(); 
        window.location.href = "bill-link?keyword1=" + v;
        return false;
       
    });
   
    
});

function fctSearchTenant(val) {
    
    $("#search-box").val(val);
    var dataToSend= $("#search-box").val();
     window.location.href = "index?file=bill_link&keyword1=" + dataToSend;
    $("#suggesstion-box").hide(); 
    
}
</script>  