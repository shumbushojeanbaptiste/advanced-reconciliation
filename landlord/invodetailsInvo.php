<!-- Custom CSS -->
<style>
  /* Fullscreen Modal */
  .modal-fullscreen {
    max-width: 100%;
    margin: 0;
  }
  .modal-fullscreen .modal-content {
    height: 100vh;   /* Full viewport height */
    border-radius: 0;
  }
  .modal-fullscreen .modal-body {
    overflow-y: auto; /* Scroll body if content is long */
  }
</style>

<!-- Modal -->
<div class="modal fade" id="modal-invodetails" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      
      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fe fe-list"></i> Statements <span id="ststype"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <!-- Body -->
      <div class="modal-body">
        <div id="fetching2"></div>
        <div id="Statementdatacomes"></div>
      </div>
      
      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!-- <button type="submit" name="update_currency" class="btn btn-primary">Save Change</button> -->
      </div>
      
    </div>
  </div>
</div>
