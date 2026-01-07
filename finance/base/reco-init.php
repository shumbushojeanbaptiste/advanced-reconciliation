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
      <div class="col-md-12">

      
          <span class="circle circle-md bg-primary mb-3 d-inline-flex align-items-center justify-content-center">
            <i class="fe fe-archive fe-24 text-white"></i>
          </span>
        <br>
        <div id="dataDepart"></div>
      </div>

    </div>
  </div>
</div>

          </div> <!-- .row -->
        </div> <!-- .container-fluid -->


          <?php

     include '../finance/base/new_reco_modal.php';
     ?>
        
        
         <script type="text/javascript">
    $(document).ready(function () {
        // /alert();
        //registered(1);
        initData();
       
        
        
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

 function initData() {
   
    $('#indicator')
        .html("<img src='../img/ajax_loader.gif' width='15'>")
        .fadeIn('fast');

    $.ajax({
        url: '../finance/controller/reconciliationController.php',
        type: 'POST',
        data: {
            action: 'init-list'
        },
        success: function (data) {
            $("#dataDepart").html(data);
            $('#indicator').fadeOut('fast');
        },
        error: function () {
            $("#dataDepart").html("<p style='color:red;'>Failed to load data</p>");
            $('#indicator').fadeOut('fast');
        }
    });
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
function removeInit(id) {

    if (!confirm('Are you sure you want to delete this reconciliation?')) {
        return;
    }

    $.ajax({
        url: '../finance/controller/reconciliationController.php',
        type: 'POST',
        dataType: 'json', // 👈 IMPORTANT
        data: {
            action: 'delete-initReco',
            reco_mode_id: id
        },
        success: function (res) {

            if (res.status === 'success') {
                alert(res.message);
                initData(); // reload list
            } else {
                alert(res.message);
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert("Server error while deleting reconciliation");
        }
    });
}


</script>

<script>
$('#save_reco_init').on('submit', function (e) {
   
    e.preventDefault();

    $('#indicator-save').html("<img src='../../img/ajax_loader.gif' width='15'>");

    $.ajax({
    url: '../finance/controller/reconciliationController.php',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function (res) {
        $('#indicator-save').html('');

        if (res.status === 'success') {
            $('#result').html("<span class='text-success'>" + res.message + "</span>");
            initData();

            setTimeout(() => {
                $('#newInitModal').modal('hide');
                $('#save_reco_init')[0].reset();
            }, 1000);
        } else {
            $('#result').html("<span class='text-danger'>" + res.message + "</span>");
        }
    },
    error: function (xhr) {
        console.error(xhr.responseText);
        $('#indicator-save').html('');
        $('#result').html("<span class='text-danger'>Server error</span>");
    }
 });
});
</script>
        
  