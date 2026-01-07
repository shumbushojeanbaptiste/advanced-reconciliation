 <div class="modal fade bs-example-modal-sm" id="modal-currency<?php echo $rows['money_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-sm">
              <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel"> <i class="fe fe-edit"></i> Edit <b><u><?php echo $rows['m_name'] ?></u></b>   Value</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>
               <div class="modal-body">
                  <form id="form_validation" method="POST" action="">
                      <input type="hidden" name="money_id" value="<?php echo $rows['money_id']?>"/>
                     
                     
                     <div class="form-group">
                         <label class="form-label"> Value in (Rwf)</label>
                         <div class="form-line">
                            <input type="text" class="form-control"  value="<?php echo $rows['intoR']?>" name="rwf_value"  style="width: 100%;">
                         </div>
                     </div>
                     
                     
                   
                       <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                     <button type="submit" name="update_currency" class="btn btn-primary">Save Change</button>
                   </div>
                 </form>
   
              </div>
           </div>
         </div>
	