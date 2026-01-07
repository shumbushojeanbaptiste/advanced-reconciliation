
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
   
        
             <h2 class="h5 page-title"><?php echo $title ?></h2>
            
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
       
           
        
        <div class="form-group col-md-1"> <label for="multi-select2"></label></div>
       <div class="form-group col-md-2"> <label for="multi-select2">Options <?php  ?></label></div>
        <div class="form-group col-md-4">
         
            <select name="options" id="options" class="form-control select2"  required>
                           <option value="1"> On Rent Info</option>
                           <option value="2"> On Electricity Info</option>
                  
        </select>
        </div> <!-- form-group -->
        
        <div class="form-group col-md-2"> <label for="multi-select2"></label></div>
       
        <div class="form-group col-md-1"></div>
          <div class="form-group col-md-2"> 
             <button type="button" name="show_cons"  id="show_cons" class="btn btn-sm btn-primary">Go</button>
             </div>
      </div> <!-- form-row -->
      
  <div name='display_specs' id='display_specs'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->
<script type="text/javascript">
    $(document).ready(function () {
        $("#show_cons").click(function () {
            
            var options=$('#options').val();
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('../landlord/clientAndShopsInfoData?options=' + options, function (data) {
             $("#display_specs").html(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>


