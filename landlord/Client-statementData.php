

<style>
.icon-rtl {
  border-radius: 20px;
  border: 1px solid #2d9fd9;
  color: #2d9fd9;
  width: 100%;
  height: 40px;
  padding-left: 15px;
  background: url("../images/search.png") no-repeat right 10px center;
  background-size: 20px;
}
.icon-rtl:focus {
  outline: none;
  border-color: #a0d18c;
  color: #2d9fd9;
}
</style>

<div class="row">

    <div class="col-md-12 mt-4">
      <div class="card shadow mb-4">
        <div class="card-body">
          <div class="form-row">
            <div class="col-12">
              <input type="text" class="form-control icon-rtl" id="search-box" placeholder="Search by Tenant Name,Phone or shop">
            </div>
          </div>
          <div class="form-row mt-2">
            <div class="col-12" id="suggesstion-box"></div>
          </div>

          <br>
          <div id="resultedOK">
          
          </div>
        </div>
      </div>
    </div>
</div>



<script>
$(function() {
  $("#search-box").keyup(function() {
    $.ajax({
      type: "POST",
      url: "load_slip_search_result2",
      data: { keyword1: $(this).val() },
      success: function(data) {
        $("#suggesstion-box").show().html(data);
        $("#resultedOK").hide();
      }
    });
  });
});
function selecttbl_admission2(val) {
   

    // Remove existing suggestion box if any
    $("#suggesstion-box").hide();
    $("#resultedOK").show();


   $("#resultedOK").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />');
    $.ajax({
        type: "POST",
        url: "../deposit/client-transaction.php",
        data: { keyword1 : val }, 
        success: function(data) {
           
            $("#resultedOK").html(data);
           
        }
    });
}

</script>
