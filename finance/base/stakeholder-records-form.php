   <?php 
require_once("../../include/initialize.php");
?>
   <?php  
   $account_id=$_POST['account_id'];;
   $account_name=$_POST['description'];
   $shorter =$_POST['shorter'];

   $stmt = $db->prepare("
            SELECT * 
            FROM reconciliation_mode 
            WHERE account_id = :account_id and (status=1 or status=3)
            LIMIT 1
        ");

        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($account_id != 5) {

   ?>
   <input type="hidden" id="names" value="<?= $account_name ?>"  >
   <div class="col-md-12 d-flex align-items-start text-right">
        <div class="w-100">

          <span class="circle circle-md bg-primary mb-3 d-inline-flex align-items-center justify-content-center">
            <i class="fe fe-users fe-16 text-white"></i> 
          </span>
          <h5> <?= $account_name ?> <span></span></h5>
           <div class="card-header  text-info">
               
                 <h5 class="card-title mb-0"> <span class="text-success"><span id="balance_area"><?php echo number_format(0,2)." Frw"; ?></span></span></h5>
            </div>


            <form action="" method="POST" id="save_stakeholder_data">
                <input type="hidden" name="action" value="save_stakeholder_data"  >
                <input type="hidden" name="stakeholder_type_id" id="stakeholder_type_id" value="<?= $account_id ?>" >
                             
              <h6> names</h6>
              <div class="form-group">
                          <input type="text" name="names"  class="form-control" placeholder=" write <?= $shorter ?> names"  required >
                        </div>
                 <h6 class="mb-3">Phone number :</h6>
                      <!--<form action="" method="POST">-->
                        <div class="form-group">
                          <input type="text" name="phone"  class="form-control" placeholder=" write phone number"  required >
                        </div>

                      <h6 class="mb-3">ID number:</h6>
                      <!--<form action="" method="POST">-->
                         <div class="form-group">
                          <input type="text" name="ID_number"  class="form-control" placeholder=" write ID number"  required >
                        </div>
                         <h6 class="mb-3">Email:</h6>
                      <!--<form action="" method="POST">-->
                         <div class="form-group">
                          <input type="text" name="email"  class="form-control" placeholder=" write email address"  required >
                        </div>

                         <h6 class="mb-3">Leader names:</h6>
                      <!--<form action="" method="POST">-->
                         <div class="form-group">
                          <input type="text" name="leader"  class="form-control" placeholder=" write leader names"  required >
                        </div>
                        <hr class="my-4" style="width:100%;background-color:#000; height: 1px; border: 0; ">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="form-check form-check-inline ml-1">
                            
                        </div>
                          
                         <button type="submit"  class="btn btn-lg bg-primary text-white btn-block waves-effect" id="savebtn">
                             <span id="indicator_save"></span>  Next and save <i class="fe fe-arrow-right fe-16 ml-2"></i></button>
                        <br>
                        
                          
                        </div>
                        <hr class="my-4">
                        <div class="form-group">
                         <small> <div id="result"></div> </small> 
                        </div>
                      </form>

        </div>
    </div>
    <?php } 
    
else { 

    





     


      ?>
       	
                <input type="hidden" name="account_id" id="account_id" value="<?= $account_id ?>" >
								<div class="card-header px-4 pt-4 text-right">
									<div class="card-actions float-end">
										<div class="dropdown position-relative">
											<a href="#" data-bs-toggle="dropdown" data-bs-display="static">
												<i class="align-middle" data-feather="more-horizontal"></i>
											</a>
                  </div>
									</div>
									

						<span class="circle circle-md bg-primary mb-3 d-inline-flex align-items-center justify-content-center">
            <i class="fe fe-users fe-16 text-white"></i> 
          </span>
          <h5> <?= $account_name ?> <span></span></h5>
								</div>
								<ul class="list-group list-group-flush">
									
                    <li class="list-group-item px-4 pb-4">
                          <input type="text" name="code" id="code" class="form-control" placeholder=" write tenants code or names"  required >
                        
                     </li>
                    
                  <li class="list-group-item px-4 pb-4">
                  <a class="btn btn-primary btn-sm btn-block" onClick="tenantData()" href="javascript:void(0)"> <span id="await"></span>Next to proceed</a>
                  </li>
                     <li class="list-group-item px-4 pb-4">
                          <span id="result-data">  </span>
                   </li>
                   <span id="area-data">
                  <li class="list-group-item px-4 pb-4">
                          <span id="names">  </span>
                   </li>
                   <li class="list-group-item px-4 pb-4">
                          <span id="phone">  </span>
                   </li>
                   <li class="list-group-item px-4 pb-4">
                          <span id="id">  </span>
                   </li>
                   <li class="list-group-item px-4 pb-4">
                          <span id="leader">  </span>
                   </li>

                    <li class="list-group-item px-4 pb-4">
                  <form action="" method="POST" id="save_stakeholder_data">

                  <input type="hidden" name="action" value="save_stakeholder_data"  >
                  <input type="hidden" name="stakeholder_type_id" id="stakeholder_type_id" value="<?= $account_id ?>" >
                             
             
              
                          <input type="hidden" name="names" id="from_names"  class="form-control"   >
                      
                          <input type="hidden" name="phone" id="from_phone" class="form-control"   >
                       
                          <input type="hidden" name="ID_number" id="from_id" class="form-control"  >
                        
                          <input type="hidden" name="email" id="from_email" class="form-control"   >
                        
                          <input type="hidden" name="leader" id="from_leader" class="form-control"  >
                           <input type="hidden" name="client_code" id="client_code" class="form-control"   >
                        
                  
                   <button type="submit"  class="btn btn-lg bg-primary text-white btn-block waves-effect" id="savebtn">
                             <span id="indicator_save"></span>  SAVE INFO <i class="fe fe-arrow-right fe-16 ml-2"></i></button>
                        <br>

                        <hr class="my-4">
                        <div class="form-group">
                         <small> <div id="result"></div> </small>  
                        </div>
                   </form>
                  </li>
                  </span>

                  
								</ul>
							

      <?php } ?>



    <script>
       
    var account_id=$('#account_id').val();
    $("#area-data").hide();
    
function tenantData() {
    $('#await')
        .html("<img src='../img/ajax_loader.gif' width='15'>")
        .fadeIn('fast');

    var code = $('#code').val();

    $.ajax({
        url: '../finance/controller/APIcontroller.php',
        type: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
            tenant_code: code
        }),
        success: function (response) {
            if (response.status === "success") {
              $("#area-data").show();
                $("#names").html("Names : " + response.data.names);
                $("#id").html("ID Number : " + response.data.ID);
                $("#phone").html("Phone : " + response.data.phone);
                $("#leader").html("Leader : " + response.data.leader);
                 $("#result-data").html('');

                 // parse data

                 $("#from_names").val(response.data.names);
                 $("#from_phone").val(response.data.phone);
                 $("#from_id").val(response.data.ID);
                 $("#from_email").val(response.data.email);
                 $("#from_leader").val(response.data.leader);
                 $("#client_code").val(response.data.client_code);
       




            } else {
                $("#result-data").html("<p style='color:red;'>" + response.message + "</p>");
                 $("#area-data").hide();


            }

            $('#await').fadeOut('fast');
           
        },
        error: function () {
            $("#result-data").html("<p style='color:red;'>Failed to load data</p>");
            $('#await').fadeOut('fast'); // fixed typo here
        }
    });
}

$('#save_stakeholder_data').on('submit', function (e) {
    e.preventDefault();

    const $form = $(this);
    const $btns = $form.find("button, input[type=submit]");

    $('#indicator-save').html("<img src='../img/ajax_loader.gif' width='15'>");
    $btns.prop("disabled", true);

    $.ajax({
        url: '../finance/controller/stakeholderController.php',
        type: 'POST',
        data: $form.serialize(),
        dataType: 'json',

        success: function (res) {
            console.log("Response:", res); // ✅ debug

            $('#indicator-save').html('');

            if (res.status === 'success') {
                $('#result').html(`<span class='text-success'>${res.message}</span>`);

                setTimeout(() => {
                    //$form[0].reset();
                    $btns.prop("disabled", false);
                }, 1000);

            } else {
                $('#result').html(`<span class='text-danger'>${res.message}</span>`);
                $btns.prop("disabled", false);
            }
        },

        error: function (xhr) {
            console.error("Server error:", xhr.responseText);

            $('#indicator-save').html('');
            $('#result').html("<span class='text-danger'>Server error</span>");
            $btns.prop("disabled", false);
        }
    });
}); 





    </script>