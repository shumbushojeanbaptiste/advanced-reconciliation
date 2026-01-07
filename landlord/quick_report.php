
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
  <h2 class="h5 page-title">General Report</h2>
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
        
        <div class=" col-md-4">
          <label for="simple-select2">Date From</label>
          <input type="date" class="form-control" name="date_from" id="date_from" aria-label="Recipient's username" aria-describedby="basic-addon2">
        </div> <!-- form-group -->
        <div class="col-md-1">
        </div>
        <div class=" col-md-4">
          <label for="multi-select2">Date To</label>
          <input type="date" class="form-control" name="date_to" id="date_to" aria-label="Recipient's username" aria-describedby="basic-addon2" required>
        </div> <!-- form-group -->
        
        
        <div class=" col-md-1"></div>
          <div class=" col-md-1"> <br>
             <button type="button" name="show_general"  id="show_general" class="btn btn-sm btn-primary">Go</button>
             </div>
      </div> <!-- form-row -->
      
  <div name='display_specs' id='display_specs'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->

 <?php  include'paiddetailsInvo.php'; ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#show_general").click(function () {
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('load_quick_report?date_to=' +date_to + '&date_from=' + date_from , function (data) {
             $("#display_specs").html(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>


