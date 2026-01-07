
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
  <h2 class="h5 page-title">Tenant Report</h2>
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
         
          <!--<input type="month" class="form-control" name="date_to" id="daterep" aria-label="Recipient's username" aria-describedby="basic-addon2" required>-->
                <!--<select id="year_id" class="form-control select2" name="year_id" data-placeholder="Choose Month..." style="width:100%" required >-->
                <!--            <option>2022</option>-->
                <!--            <option>2021</option>-->
                <!--            <option>2020</option>-->
                <!--            <option>2019</option>-->
                <!--            </select>-->
        
        </div>
        <!--<div class="form-group col-md-1"> <label for="multi-select2"></label></div>-->
       <div class="form-group col-md-1 lblClass"> <label for="multi-select2" style="width=100px">Floor</label></div>
        <div class="form-group col-md-3">
         
           <select id="floor_code100" class="form-control select2" name="floor" data-placeholder="Choose Floor..." style="width:100%" required >
                             <option></option>
                                 <?php
                                     $stmt = $db->query("SELECT Block_code, block_name FROM tbl_block where company_id='".$company."'  ORDER BY Block_code ASC");
        
                                            try
                                            {
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?php echo $row['Block_code']; ?>"><?php echo $row['block_name']; ?></option>
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
      </div>
      <!-- form-row -->
      
  <div name='display_specs' id='display_specs'>
  

  
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->

<script type="text/javascript">
    $(document).ready(function () {
        $("#floor_code100").change(function () {
            var date_from = $('#date_from').val();
            var year_id=$('#year_id').val();
			$(this).after('<div id="loader"><img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('floorData.php?floor_code=' + $(this).val() + '&date_from=' + date_from + '&year_id=' + year_id , function (data) {
             $("#display_specs").html(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
            });
        });

    });
</script>


