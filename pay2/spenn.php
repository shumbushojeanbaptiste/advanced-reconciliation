<form class="col-lg-12 col-md-12 col-10 mx-auto text-center" action="pay/spennData" method="POST" id="spennData">           
          
          <div class="form-group row">
              <div class="col-md-4"> Phone number </div>
            <div class="col-md-8">
            <input type="text" name="payPassword" id="payPassword" class="form-control form-control-lg" placeholder="ex: 078...." required>
            </div>
          </div>
          <div class="form-group row">
              <div class="col-md-4"> Amount </div>
            <div class="col-md-8">
            <input type="number" name="amount" id="amount" class="form-control form-control-lg" placeholder="Minimum:5,000 Frw" required>
            </div>
          </div>
          
         <div class="form-group row">
              <div class="col-md-9">  </div>
            <div class="col-md-3">
                 
           <button class="btn mb-2 btn-primary" id="payNow">
              <div class="spinner-border mr-3 spinner-border-sm text-info" role="status" id="spinnerpay">
                      <span class="sr-only">Loading...</span>
                    </div> 
               <span id="paybtntext">Pay now</span></button>
            </div>
          </div>
                     
                      </form>
                      
                      <script>
    $(document).ready(function(){
   //alert(3);
 $("#spinnerpay").hide();
          
$("#spennData").submit(function(e){
    e.preventDefault();
    var formdata = new FormData(this);
  document.getElementById("payNow").disabled = true;
  $("#spinnerpay").show();
  $("#paybtntext").hide();
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
          $('#closeModal').trigger('click');
         $("#spinnerpay").hide();
         $("#paybtntext").show();
          document.getElementById("payNow").disabled = false;
        
     }, 1000); 
               
               
               
            
           
                },error: function(){
                    alert("okey");
                }
             });
          });
      
      
            
});


</script>