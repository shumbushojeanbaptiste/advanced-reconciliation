
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
  <h2 class="h5 page-title">Monthly Report</h2>
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
             <div class="form-group col-md-1"> <label for="multi-select2">Year</label></div>
        <div class="form-group col-md-3">
         
          <!--<input type="date" class="form-control" name="date_to" id="date_to" aria-label="Recipient's username" aria-describedby="basic-addon2" required>-->
                <select id="year_id" class="form-control select2" name="year_id" data-placeholder="Choose Month..." style="width:100%" required >
                             <option>2022</option>
                               
                                <option>2021</option>
                                
                                <option>2020</option>
                                <option>2019</option>
                                
                                
                              	
                            </select>
        
        </div>
        <div class="form-group col-md-1"> <label for="multi-select2"></label></div>
       <div class="form-group col-md-1"> <label for="multi-select2">Month</label></div>
        <div class="form-group col-md-3">
         
          <!--<input type="date" class="form-control" name="date_to" id="date_to" aria-label="Recipient's username" aria-describedby="basic-addon2" required>-->
                <select id="monthly" class="form-control select2" name="interval" data-placeholder="Choose Month..." style="width:100%" required >
                             <option></option>
                             
                                    <?php
                                             $stmt = $db->query('SELECT * FROM  tbl_month ');  
                                            try  {
                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                                <option value="<?php echo $row['mon_code']; ?>"><?php echo $row['month_name']; ?></option>
                                                            <?php
                                                             }
                                                        }
                                                        catch (PDOException $ex) {
                                                        //Something went wrong rollback!
                                                        echo $ex->getMessage();
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
<script type="text/javascript">
    $(document).ready(function () {
        $("#monthly").change(function () {
            var date_from = $('#date_from').val();
            var year_id=$('#year_id').val();
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('load_monthly_report3?monthly=' + $(this).val() + '&date_from=' + date_from + '&year_id=' + year_id , function (data) {
             $("#display_specs").html(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>


