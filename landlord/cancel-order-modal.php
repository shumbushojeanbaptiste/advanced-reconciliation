 <form  action="cancelling_pay.php" id="cancelling_pay<?php  echo $fetch['pay_id'] ?>">
<div class="modal fade modal-full<?php echo $fetch['pay_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
<button aria-label="" type="button" class="close px-2" data-dismiss="modal" aria-hidden="true">
  <span aria-hidden="true">×</span>
</button>
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
      
    <div class="modal-body text-center">
      <p> Canceling Slip <?php echo $fetch['slip_no']; ?> for <?php echo $fetch['te_names']; ?>. </p>
      
          <div class="form-group col-md-6">
              <input type="hidden" name="pay_id" id="pay_id<?php echo $fetch['pay_id']; ?>" value="<?php echo $fetch['pay_id']; ?>">
              <input type="hidden" name="slip" value="<?php echo $fetch['slip_no']; ?>">
        <textarea class="form-control form-control-lg mr-sm-2" name="cancel1"  id="cancel1<?php echo $fetch['pay_id']; ?>" type="search" placeholder="Why" aria-label=""></textarea>
        </div>
        <button type="submit" class="btn btn-danger btn-sm mb-2 my-2 my-sm-0" name="cancel" >Cancel</button>
     
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!--<button type="submit" name="saveDiscount" id="saveDiscount" class="btn btn-primary">Save</button>-->
        
      </div>
    </div>
  
  </div> 
  
</div>
</div> <!-- small modal -->
 </form>
                      