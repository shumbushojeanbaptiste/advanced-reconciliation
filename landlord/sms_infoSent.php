<?php 

/**
 * 
 * Author @macintosh
 */

 require_once ("../include/initialize.php");
 ?>
<div class="row">
   
    <div class="col-md-12"><br><br>
        <div class="card shadow mb-4">
            <div class="card-body">
                <br><br>
                <div id="resultedOK">
                    <?php 
                
                    $monthly=$_REQUEST['date'];
                    $stms = $db->prepare("SELECT * FROM  tbl_informingSMS   WHERE tbl_informingSMS.received_no in(select tbl_tenants.ten_phone from tbl_tenants
                     where ten_staff='".$company."') and
                    DATE_FORMAT(tbl_informingSMS.done_at,'%Y-%m')='".$monthly."' and  tbl_informingSMS.comp_id='".$company."' 
                      order by inf_id desc ");
            		$stms->execute();
    		
                    // 		 WHERE 
                    //         tbl_invo_link.tenant_reference in (select tbl_bill.ten_id from tbl_bill where DATE_FORMAT(tbl_bill.invoice_date,'%Y-%m') ='".$monthly."') 
    			    $row_count = $stms->rowCount();
    	    
    			    if($row_count>0){
                        ?>
     
                        <div class="card-header">
                          
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
                                         <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tot=0;
                        			$i =0;
                        			while ($rows = $stms->fetch(PDO::FETCH_ASSOC)):
                        			    $i++;
                        			    
                        			    
                            	        $stms2 = $db->prepare("SELECT * FROM  tbl_tenants WHERE ten_phone='".$rows['received_no']."'");
                            			$stms2->execute();
                            			$row_count2 = $stms2->fetch();
                        			
                            			$currency_id=$row_count2['currency_id'];
                            			
                            		    $li_quIPaidNMoney = $db->prepare("SELECT  * FROM   tbl_money where money_id ='".$currency_id."' ");
                                        $li_quIPaidNMoney->execute(); 
                                        $newDMoney=$li_quIPaidNMoney->fetch();
                                        
                                        $converted=$newDMoney['intoR'];
                                    
                        			 
                                        ?>
                                        <tr>
                                            <td ><?php echo $i;?></td>
                                            <td >
                                                <?php 
                                                  
                                                   // echo $newDate;
                                                   echo $row_count2['te_names']; ?>
                                            </td>
                                            <td>
                                                <?php echo $rows['received_no'];?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo $rows['done_at'];
                                                    
                                                ?>
                                            </td>
                                            <td>
                                               <a href="" data-toggle="modal" data-target="#viewsms<?php echo $rows['inf_id']?>" class="edit_data"><i class="fe fe-eye"></i> view sms</a>  
                                            </td>
                                        </tr>
                                        <?php  
                                        include'view_informing.php';
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
                        		</strong> No Data . <?php echo $monthly."#"; ?>
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