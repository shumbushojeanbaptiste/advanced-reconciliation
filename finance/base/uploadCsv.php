    <?php
    //require_once ("../include/initialize.php");
    

?>
        <div class="container-fluid">
          <div class="row justify-content-center">
           
            <div class="col-12">
                                   <?php
		        if(!empty($msg)) {?>
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>Well done!</strong> <?php echo $msg; ?> 
			</div>
		            
		    <?php
		      }
		       else if(!empty($msge)) {
		    ?>
			<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>Oh snap!</strong>  <?php echo $msge; ?> 
			</div>
		    <?php
		       }
		    ?>
		    
		    <div class="row align-items-center mb-2">
 
         
                <div class="col-md-6">
                  <!--<h2 class="h5 page-title">All Rooms</h2>-->
            
                </div>
                 <div class="col-md-2">
               
                    
                 
                </div>
                 
                
               
              </div>
              <div class="row align-items-center mb-2">
                  
 
         
                <div class="col">
                  <h2 class="h5 page-title"><?= $title ?></h2>
            
                </div>
              
                <div class="col-auto">
                 
                </div>
              </div>
             
<div class="card my-4 shadow">
  <div class="card-body">
    <div class="row">

      <div class="row">
  <!-- LEFT COLUMN: CSV Upload -->
  <div class="col-md-5 d-flex align-items-start text-center">
    <div class="w-100">

      <span class="circle circle-md bg-primary mb-3 d-inline-flex align-items-center justify-content-center">
        <i class="fe fe-archive fe-24 text-white"></i>
      </span>

      <form action="" method="POST" id="upload_csv" enctype="multipart/form-data">
        <input type="hidden" name="action" value="save_bank_file">
        <hr class="my-4">

        <h6 class="mb-3">Choose CSV File:</h6>
        <div class="form-group">
          <input type="file" name="file_name" id="file_name" class="form-control" accept=".csv" required>
        </div>

        <div class="mb-3" id="progressContainer" style="display:none;">
          <div class="progress" style="height:20px;">
            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width:0%">0%</div>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
          <button type="submit" class="btn btn-lg bg-primary text-white btn-block waves-effect" id="savebtn">
            <span id="indicator_save"></span> Upload & Save <i class="fe fe-arrow-right fe-16 ml-2"></i>
          </button>
        </div>

        <hr class="my-4">
        <div class="form-group">
          <small><div id="result"></div></small>
        </div>
      </form>

    </div>
  </div>

  <!-- RIGHT COLUMN: Display Data / Table -->
  <div class="col-md-7">
    <br>
    <div id="dataDepart"></div>
  </div>
</div>


    </div>
  </div>
</div>

          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
    


<script>
$('#upload_csv').on('submit', function(e){
    e.preventDefault();

    let formData = new FormData(this); // FormData to handle file
    $('#indicator_save').html("<img src='../../img/ajax_loader.gif' width='15'>");
    $('#result').html('');
    $('#progressContainer').show();
    updateProgressBar(0);

    $.ajax({
        url: '../finance/controller/uploadController.php',
        type: 'POST',
        data: formData,
        processData: false, // important
        contentType: false, // important
        dataType: 'json',
        success: function(response){
            if(response.success){
                $('#result').html("<span class='text-success'>"+response.message+"</span>");
                updateProgressBar(100);

                // Reload table after finish
                incomeTypeList();

                // Reset form
                setTimeout(()=>$('#upload_csv')[0].reset(), 1000);
            } else {
                $('#result').html("<span class='text-danger'>"+response.message+"</span>");
                updateProgressBar(0);
            }
            $('#indicator_save').html('');
        },
        error: function(){
            $('#result').html("<span class='text-danger'>Error uploading file</span>");
            $('#indicator_save').html('');
            updateProgressBar(0);
        }
    });
});

// Update progress bar
function updateProgressBar(percent){
    if($('#progressContainer').length === 0){
        $('#upload_csv').prepend(`
            <div id="progressContainer" class="mb-2">
                <div class="progress" style="height:20px;">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width:0%">0%</div>
                </div>
            </div>
        `);
    }
    $('#progressBar').css('width', percent+'%').attr('aria-valuenow', percent).text(percent+'%');
}

</script>
        
  