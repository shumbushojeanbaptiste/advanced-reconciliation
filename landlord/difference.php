
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
  <h2 class="h5 page-title">Room Report</h2>
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
             <div class="form-group col-md-1"> <label for="multi-select2">Month 1</label></div>
        <div class="form-group col-md-3">
         
          <!--<input type="date" class="form-control" name="date_to" id="date_to" aria-label="Recipient's username" aria-describedby="basic-addon2" required>-->
               <select id="monthly1" class="form-control select2" name="monthly1" data-placeholder="Choose Month..." style="width:100%" required >
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
        
        </div>
        <div class="form-group col-md-1"> <label for="multi-select2"></label></div>
       <div class="form-group col-md-1"> <label for="multi-select2">Month 2</label></div>
        <div class="form-group col-md-3">
         
          <!--<input type="date" class="form-control" name="date_to" id="date_to" aria-label="Recipient's username" aria-describedby="basic-addon2" required>-->
                <select id="monthly2" class="form-control select2" name="monthly2" data-placeholder="Choose Month..." style="width:100%" required >
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
        $("#monthly2").change(function () {
            var monthly1 = $('#monthly1').val();
           
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('load_difference?monthly2=' + $(this).val() + '&monthly1=' + monthly1, function (data) {
             $("#display_specs").html(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>


