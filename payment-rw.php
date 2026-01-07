<?php include 'hd2.php';?>
  
    
      <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
            
              
              <div class="w-50 mx-auto text-center justify-content-center py-2 my-2">
                <h2 class="page-title mb-0">Payment</h2>
                <!--<p class="lead text-muted mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>-->
                <!--<form class="searchform searchform-lg">-->
                <!--  <input class="form-control form-control-lg bg-white rounded-pill pl-5" type="search" placeholder="enter your code" aria-label="Search">-->
                <!--  <p class="help-text mt-2 text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>-->
                <!--</form>-->
              </div>
            
              <div class="row my-4">
                  <div class="col-md-2"></div>
                <div class="col-md-8">
                  <div class="card shadow bg-primary text-center mb-4">
                    <div class="card-body ">
                      <span class="circle circle-md bg-primary-light">
                        <i class="fe fe-user fe-24 text-white"></i>
                      </span>
                      <!--<h3 class="h4 mt-4 mb-1 text-white"> RENT</h3>-->
                      <p class="text-white mb-4">Use your identification code and password</p>
                      <div id="display_info"></div>
                        <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" action="checking" id="checking" method="POST" autocomplete="off">
          
          <div class="form-group">
        
            <input type="text" id="paycode" name="paycode" class="form-control form-control-lg" placeholder="enter your code" required="" >
          </div>
          <div class="form-group">
            
            <input type="password" name="payPassword" id="payPassword" class="form-control form-control-lg" placeholder="Pin" required="">
          </div>
          
        
                     <button class="btn btn-lg bg-primary-light text-white" id="btncheck">
                      <div class="spinner-border mr-3 text-light" role="status" id="spinnerck">
                      <span class="sr-only">Loading...</span>
                    </div>   
                         <span id="textcheck">Check info<i class="fe fe-arrow-right fe-16 ml-2"></i></span>
                     
                     </button>
                     </form>
                     
                    </div> <!-- .card-body -->
                  </div> <!-- .card -->
                </div> <!-- .col-md-->
                <div class="col-md-2"></div>
              </div> <!-- .row -->
             
              
               

<?php include 'ft2.php';?>

<script>
    $(document).ready(function(){
   //alert(3);
   $("#spinnerck").hide();
   
  $("#checking").submit(function(e){
    e.preventDefault();
    var formdata = new FormData(this);
    document.getElementById("btncheck").disabled = true;
    
    $("#textcheck").hide();
      $("#spinnerck").show();
    
        $.ajax({
                url: "checking.php",
                type: "POST",
                data: formdata,
                mimeTypes:"multipart/form-data", 
                contentType: false,
                cache: false,
                processData: false,
                success: function(formData){
                   // alert(3);
               
                  
         setTimeout(function(){// wait for 5 secs(2)
        $("#display_info").html(formData);
          $("#checking").hide();
          
           $("#spinnerck").hide();
            document.getElementById("btncheck").disabled = false;
        $("#textcheck").show();
     }, 1000); 
               
               
               
           
           
           
                },error: function(){
                    alert("okey");
                }
             });
          });
          
$("#spennData").submit(function(e){
    e.preventDefault();
    var formdata = new FormData(this);
  
        $.ajax({
                url: "pay/spennData.php",
                type: "POST",
                data: formdata,
                mimeTypes:"multipart/form-data", 
                contentType: false,
                cache: false,
                processData: false,
                success: function(formData){
                   // alert(3);
           setTimeout(function(){// wait for 5 secs(2)
           $("#spenn_info").html(formData);
         // $("#checking").hide();
        
     }, 3000); 
               
               
               
            
           
                },error: function(){
                    alert("okey");
                }
             });
          });
      
      
            
});

function myClosing() {
    // $("#checking").show();
  location.reload(); 
}
</script>