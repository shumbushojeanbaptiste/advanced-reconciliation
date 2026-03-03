
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

    #show_general {
  border-radius: 8px;
  transition: all 0.25s ease-in-out;
}



</style>
<div class="span9">
  <div class="content">

 <div class="module">
<div class="col">

    
    <h2 class="h5 page-title"><?= $title ?></h2>
   
  
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
     
<div class="form-row align-items-center mt-2">
   <div class="form-group col-md-1 lblClass">
    <label for="tenantly" class="fw-bold"> </label>
  </div>

  
        <div class="form-group col-md-7">
         
        <select id="mode_reco" 
        class="form-control select2" 
        name="mode_reco" 
        data-placeholder="Choose specific code..." 
        style="width:100%" 
        required>

    <option value=""></option>

    <?php
    $stmtP = $db->query("SELECT m.reco_code,m.from_date,m.to_date,a.account_name FROM reconciliation_mode m
    LEFT JOIN tbl_accounts a ON a.account_id = m.account_id");

    while($rowdata = $stmtP->fetch(PDO::FETCH_ASSOC)){

        $mode_name = $rowdata['reco_code'];
        $from_date = date('d M Y', strtotime($rowdata['from_date']));
        $to_date   = date('d M Y', strtotime($rowdata['to_date']));
        $code = $rowdata['reco_code'];
        $account_name = $rowdata['account_name'];
    ?>

        <option value="<?= htmlspecialchars($mode_name) ?>">
            <?= htmlspecialchars($account_name) ?> | <?= $from_date ?> up to <?= $to_date ?>
        </option>

    <?php } ?>

</select>

                
        
  </div>
  <div class="form-group col-md-1"></div>



 

  <div class="form-group col-md-1 text-center">
    <button 
      type="button" 
      name="show_general"  
      id="show_general" 
      class="btn btn-sm btn-primary shadow-sm d-flex align-items-center justify-content-center px-3"
      onClick="statistics()"
    >
       Next  <i class="fa fa-long-arrow-right me-2"></i>
    </button>
  </div>
</div>


      
  <div name='resultedOK' id='resultedOK'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->
<script type="text/javascript">
    $(document).ready(function () {
        var month =$("#tenantly").val();
        statistics();
    

    });

   function statistics() {
   
  var mode_reco =$("#mode_reco").val();
  
    // Remove existing suggestion box if any
  
    $("#resultedOK").show();


   $("#resultedOK").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />');
  
    $.ajax({
        type: "POST",
        url: "../finance/base/verified-data.php",
        data: { mode_reco : mode_reco }, 
        success: function(data) {

       
           
            $("#resultedOK").html(data);
           
        }
    });
}
</script>


