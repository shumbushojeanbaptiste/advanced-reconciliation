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
    .lblClaa{
        text-seze:10px;
        color: rgb(100,100,100);
    }
</style>
     
<div class="span9">
  <div class="content">

 <div class="module">
<div class="col">
  <h2 class="h5 page-title">Next Date Payment</h2>
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
        
        <div class="form-group col-md-3">
        </div>
        
        <div class="form-group col-md-1"> <label for="multi-select2"></label></div>
       <div class="form-group col-md-1 lblClass">
            <label for="multi-select2" style="width=100px">Monthly</label></div>
        <div class="form-group col-md-3">
         
          <input type="month" class="form-control" name="date_to" id="tenantly" aria-label="Recipient's username" aria-describedby="basic-addon2" required>
               
        
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      
  <div name='display_specs' id='display_specs'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->
<script type="text/javascript">
    $(document).ready(function () {
        $("#tenantly").change(function () {
            var date_from = $('#date_from').val();
            var year_id=$('#year_id').val();
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('load_tenant?tenantly=' + $(this).val() + '&date_from=' + date_from + '&year_id=' + year_id , function (data) {
             $("#display_specs").html(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>