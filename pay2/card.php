
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Paypal Payment</title>
</head>
<body>
    
<div class="col-lg-12 col-md-12 col-10 mx-auto text-center">           
          
        
          <div class="form-group row">
              <div class="col-md-4"> Amount </div>
            <div class="col-md-8">
            <input type="number" name="amount" id="amount" class="form-control form-control-lg" placeholder="Minimum:5,000 Frw" required>
            </div>
          </div>
          
         <div class="form-group row">
              <div class="col-md-9">  </div>
            <div class="col-md-3">
                 
           <button class="btn mb-2 btn-primary" id="checkout-button">
                 <div class="spinner-border mr-3 spinner-border-sm text-info" role="status" id="spinnerpay">
                      <span class="sr-only">Loading...</span>
                    </div> 
               <span id="paybtntext">Pay now</span></button>
            </div>
          </div>
                     
    </div>
                      
 <script>
    $(document).ready(function(){
   //alert(3);
 document.getElementById('spinnerpay').hide();
 document.getElementById('checkout-button').addEventListener('click', function() {
    const cart= getCartData()
    const data = {
   cart: cart,
  checkout_button_div_id: "checkout-button",
  paypal_button_div_id: "paypal-button-container"
};
 submitCart(data);
});

function getCartData() {
  // Get item details
   var items = [];
 
    var item = {
      name: "Rent",
      price: document.getElementById('amount').value,
      quantity: 1
    };
    
    items.push(item);
    return items
  }

          
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

</body>
</html>
