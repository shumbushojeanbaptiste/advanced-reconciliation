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

      <!-- LEFT COLUMN -->
      <div class="col-md-5 d-flex align-items-start text-center">
        <div class="w-100">

          <span class="circle circle-md bg-primary mb-3 d-inline-flex align-items-center justify-content-center">
            <i class="fe fe-archive fe-24 text-white"></i>
          </span>

            <form action="" method="POST" id="save_income_type">
                             <input type="hidden" name="action" value="save_income_type"   >
                       <hr class="my-4">
                           <h6 class="mb-3">Expense Type Name:</h6>
                      <!--<form action="" method="POST">-->
                        <div class="form-group">
                          <input type="text" name="type_name" id="rm_code" placeholder="Write expense type name..." class="form-control"  required >
                        </div>
                       
                          <h6 class="mb-3">Description:</h6>
                      <!--<form action="" method="POST">-->
                        <div class="form-group">
                          
                         <input type="text" name="description" id="rm_code" placeholder="Write the description..." class="form-control"   >
                        </div>
                       
                        
                       
                      
                        <hr class="my-4" style="width:100%;background-color:#000; height: 1px; border: 0; ">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="form-check form-check-inline ml-1">
                            
                          </div>
                          
                         <button type="submit"  class="btn btn-lg bg-primary text-white btn-block waves-effect" id="savebtn">
                             <span id="indicator_save"></span> Save <i class="fe fe-arrow-right fe-16 ml-2"></i></button>
                          <br>
                        
                          
                        </div>
                        <hr class="my-4">
                        <div class="form-group">
                         <small> <div id="result"></div> </small> 
                        </div>
                      </form>

        </div>
      </div>

      <div class="col-md-7">
  <br>

  <!-- Progress Bar -->
  <div id="progressContainer" style="display:none; margin-bottom:15px;">
    <div class="progress" style="height:20px;">
      <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
           role="progressbar" 
           style="width:0%" 
           aria-valuenow="0" 
           aria-valuemin="0" 
           aria-valuemax="100">
        0%
      </div>
    </div>
  </div>

  <div id="dataDepart"></div>
</div>

    </div>
  </div>
</div>

          </div> <!-- .row -->
        </div> <!-- .container-fluid -->
        
     <?php

     include '../finance/base/new_account_modal.php';
     ?>
        
         <script type="text/javascript">
    $(document).ready(function () {
        // /alert();
        //registered(1);
        incomeTypeList();
       
        
        
         $("#save_department200").submit(function(e){
            e.preventDefault();
        //   alert(4);  
         $('#indicator').html("<img src='../img/ajax_loader.gif' width='15'>").fadeIn('fast');
           var formdata = new FormData(this);
           $("#savebtn").attr("disabled", true);
            $.ajax({
                url: "base_file_letter.php",
                type: "POST",
                data: formdata,
                mimeTypes:"multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(formData){
                    // $.get('load_payment_results.php?&data=' + data , function (data1) {
                    //       $("#pay_result22").html(data1);
                    //       $('#firstdiv').hide();
                    //  // alert(date);
                    // });
                    if(formData==1)
                    {
                registered(1)
                $('#save_department').trigger("reset");
                $('#indicator').html("").fadeIn('fast');
               	$('#result').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><strong>Well done!</strong>  </div>').fadeIn('fast');
                }
                else 
                {
                 
        $('#indicator').html("").fadeIn('fast');       
    	$('#result').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>went Wrong!</strong>  </div>').fadeIn('fast');
                }
                
                $("#savebtn").attr("disabled", false);
               
                },error: function(){
                    alert("okey");
                }
             });
          });

        $("#build_id").change(function () {
     
	$(this).after('<div id="loader11sss33"><img src="../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
           window.location='?file=allrooms' +  '&build_id=' + this.value
        });

    });
    
    
    function registered(val1){
        
           $.get('departmentData.php?auto_code=' + val1, function (data) {
            //  alert(data);  
             $("#dataDepart").html(data); 
               
            });
    }

 let page = 1;
let allData = [];

function incomeTypeList() {
    // Reset
    allData = [];
    page = 1;

    // Show progress bar
    $('#progressContainer').show();
    updateProgressBar(0);

    // Start loading batches
    loadBatch(page);
}

function loadBatch(page) {
    $.ajax({
        url: '../finance/controller/advancedController.php',
        type: 'POST',
        data: { action: 'income-type-list', page: page },
        dataType: 'json',
        success: function(response) {
            if(response.data.length > 0){
                // Append batch
                allData = allData.concat(response.data);

                // Update progress bar
                updateProgressBar(response.progress);

                // Load next batch if not finished
                if(response.progress < 100){
                    page++;
                    loadBatch(page);
                } else {
                    // Complete, hide progress after short delay
                    setTimeout(() => { $('#progressContainer').fadeOut('fast'); }, 500);
                    renderIncomeTable(allData);
                }
            } else {
                $('#progressContainer').fadeOut('fast');
                $("#dataDepart").html("<p>No data found</p>");
            }
        },
        error: function() {
            $('#progressContainer').fadeOut('fast');
            $("#dataDepart").html("<p style='color:red;'>Failed to load data</p>");
        }
    });
}

function updateProgressBar(percent) {
    $('#progressBar').css('width', percent + '%').attr('aria-valuenow', percent).text(percent + '%');
}

function renderIncomeTable(data) {
    let html = `<br>
    <table class="table table-hover" id="dataTable-10" style="font-size:11px;">
        <thead>
            <tr>
                <th>#</th>
                <th>Income Type Name</th>
                <th>Description</th>
                <th></th>
            </tr>
        </thead>
        <tbody>`;

    data.forEach((row, i) => {
        html += `<tr>
            <td>${i+1}</td>
            <td><b>${row.income_name}</b></td>
            <td>${row.description}</td>
            <td class="text-center">
                <a href="javascript:void(0);" onClick="editAccount(${row.income_type_id});">
                    <i class="fe fe-edit"></i>
                </a> |
                <a href="javascript:void(0);" onClick="removeIncomeType(${row.income_type_id});">
                    <i class="fe fe-trash-2 text-danger"></i>
                </a>
            </td>
        </tr>`;
    });

    html += `</tbody></table>`;
    $("#dataDepart").html(html);
}




 function methodList() {
   
    $('#indicator')
        .html("<img src='../img/ajax_loader.gif' width='15'>")
        .fadeIn('fast');

    $.ajax({
        url: '../finance/controller/accountController.php',
        type: 'POST',
        data: {
            action: 'payment-methods'
        },
        success: function (data) {
            $("#method-data").html(data);
            $('#indicator').fadeOut('fast');
        },
        error: function () {
            $("#dataDepart").html("<p style='color:red;'>Failed to load data</p>");
            $('#indicator').fadeOut('fast');
        }
    });
}

function editAccount(id) {
    //alert(id);
    $.ajax({
        url: '../finance/controller/accountController.php',
        type: 'POST',
        data: {
            action: 'get-account',
            account_id: id
        },
        success: function (data) {
            $('#editAccountModal .modal-content').html(data);
            $('#editAccountModal').modal('show');
        },
        error: function () {
            alert("Error loading account data");
        }
    });
}
function removeIncomeType(id) {
    if (!confirm("Are you sure you want to delete this account?")) {
        return;
    }

    $.ajax({
        url: '../finance/controller/incomeController.php',
        type: 'POST',
        data: {
            action: 'delete-income-type',
            income_type_id: id
        },
        success: function (data) {
            incomeTypeList();
        },
        error: function () {
            alert("Error deleting account");
        }
    });
}

</script>

<script>
$('#save_income_type').on('submit', function (e) {
    e.preventDefault();

    $('#indicator_save').html("<img src='../../img/ajax_loader.gif' width='15'>");

    $.ajax({
        url: '../finance/controller/incomeController.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            $('#indicator_save').html('');
            $('#result').html(response);

            // Reload account list
            incomeTypeList();

            // Close modal after success
            setTimeout(() => {
                
                $('#save_income_type')[0].reset();
                 
            }, 1000);
        },
        error: function () {
            $('#indicator_save').html('');
            $('#result').html("<span class='text-danger'>Error saving data</span>");
        }
    });
});
</script>
        
  