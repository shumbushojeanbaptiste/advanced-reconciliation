 <div class="modal fade bs-example-modal-sm" id="modal-edit<?php echo $rows1['invoice_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-sm">
              <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Edit Invoice</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <div class="modal-body">
                  <form id="form_validation" method="POST" action="">
                      <input type="hidden" name="invoice_id" value="<?php echo $rows1['invoice_id']?>"/>
                      <input type="hidden" name="keyword" value="<?php echo $_REQUEST['keyword1']; ?>">
                     
                     <div class="form-group">
                         <label class="form-label"> Invoice No</label>
                         <div class="form-line">
                            <input type="text" class="form-control" disabled value="<?php echo $rows1['invoice']?>" name="invoice_no" required style="width: 100%;">
                         </div>
                     </div>
                     
                      <div class="form-group form-float">
                         <label class="form-label"> Ref no </label>
                         <div class="form-line">
                            <input type="text" class="form-control" disabled value="<?php echo $rows1['inv_ref_no']?>" name="invoice_ref_no" required style="width: 100%;">
                         </div>
                     </div>
                     
                     <div class="form-group form-float">
                         <label class="form-label"> Amount </label>
                         <div class="form-line">
                            <input type="text" class="form-control" value="<?php echo $rows1['invoice_fees']/$rows1['unit_value']?>" name="invoice_amount" required style="width: 100%;">
                         </div>
                     </div>
                     <?php  if($rows1['auto']=='no') {?>  
                     <div class="form-group form-float">
                         <label class="form-label"> Invoice Date </label>
                         <div class="form-line">
                            <input type="date" class="form-control" value="<?php echo $rows1['invoice_date']?>" name="invoice_date" required style="width: 100%;">
                         </div>
                     </div>
                     
                     <?php } else{ ?>
                     <input type="hidden" class="form-control" value="<?php echo $rows1['invoice_date']?>" name="invoice_date" required style="width: 100%;">
                     
                     <?php  } ?>
                     <div class="form-group form-float">
                         <label class="form-label"> Comment </label>
                         <div class="form-line">
                            <input type="text" class="form-control" value="<?php echo $rows1['inv_desc']?>" name="invoice_comment" required style="width: 100%;">
                         </div>
                     </div>
                 </div>
                       <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                     <button type="submit" name="update_invoice" class="btn btn-primary">Save Change</button>
                   </div>
                 </form>
   
              </div>
           </div>
         </div>
	