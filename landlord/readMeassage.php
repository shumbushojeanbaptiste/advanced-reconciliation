    <?php
    /**
     * 
     * Author @macintosh
     */
    ?>
    <div class="modal fade" id="read-message" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Message Body</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!--<form id="swapp-hours"action="table_info" method="POST" enctype="multipart/form-data"  method="POST" id="ModalForm">-->
                        <input type="hidden" id="editId" value="">
                        
                        <div class="form-group">
                            <!--<label for="edit-level">Meassage <?php echo $rows['link_id'];?></label>-->
                            <textarea class="form-control" id="edit-level" name="edit-level" readonly>
                                <?php 
                                $message =$rows['sms'];
                                if(empty($message)){
                                    $message ="Message body was not found";
                                } 
                                echo $message;?>
                            </textarea>
                        </div>
                        <div class="modal-footer">
                            <a  class="btn btn-primary" data-dismiss="modal">Close</a>
                            <!--<button type="submit"  id="saveModalButton" class="btn btn-primary">Save changes</button>-->
                        </div>
                    <!--</form>-->
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-lg-12" id="bloc"></div>