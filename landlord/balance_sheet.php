
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
<label class="title">Balance Sheet</label>
<hr>
 <div class="row">
<div class="col-md-12"><br><br>
  <div class="card shadow mb-4">
    <div class="card-body">
        <br><br>
        <div class="form-row">
        <div class="form-group col-md-1"></div>
            <div class="form-group col-md-3">
                <label for="simple-select2">Date From</label>
                <input type="date" class="form-control" name="date_from" id="date_from" aria-label="Recipient's username" aria-describedby="basic-addon2">
        </div> <!-- form-group -->
        <div class="form-group col-md-3">
          <label for="multi-select2">Date To</label>
          <input type="date" class="form-control" name="date_to" id="date_to" aria-label="Recipient's username" aria-describedby="basic-addon2" required>
        </div> <!-- form-group -->
        <div class="form-group col-md-3">
          <label for="multi-select2">Client name</label>
          <select name="ten" id="ten" class="form-control select2" id="simple-select2" required>
                           <option></option>
                 <?php
                     $sql = $conn->prepare("SELECT * FROM `tbl_tenants` WHERE ten_staff = $company ORDER BY ten_id");
                     $sql->execute();
                     while($fetch = $sql->fetch()){
                 ?>
         
             <option value="<?php echo $fetch['ten_ref_no']?>"><?php echo $fetch['te_names']?></option>
         
         <?php
             }
         ?>      
        </select>
        </div> <!-- form-group -->
      </div> <!-- form-row -->
      
  <div name='display_specs' id='display_specs'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->
        <div name='display_specs' id='display_specs'>
        </div>
      
 </div>
 
</div><!--/.module-->

</div><!--/.content-->
</div><!--/.span9-->


<script type="text/javascript">
    $(document).ready(function () {

        $("#ten").change(function () {
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('load_balance_sheetInd?ten=' + $(this).val() + '&date_from=' + date_from + '&date_to=' + date_to , function (data) {
             $("#display_specs").html(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>


