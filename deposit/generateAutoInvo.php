
<style>
    .modal { display:none; }
    .modal-title {color: #111;}
    td {
        font-size: 12px;
    }
    .title{
        margin-left:10px;
        font-size:15px;
    }
    .form-group{
        display: inline-flex;
        margin: 10px;
        padding: 10px;
    }
    #mon{
        margin: auto;
        margin-right: inherit;
    }
    #inv-dt{
        margin-top: 50px;
    }
    #inv-dt{
        margin-top: 50px;
    }
</style>
<div class="span9">
<div class="content"> 
 
 <div class="module">
<label class="title">Monthly Invoice</label>
<hr>      
<div class="row">
<div class="col-md-12">
<div class="card shadow mb-4">
    <div class="card-body">
    <form name="invoice" id="auto-form" method="POST" action="" >
     <div class="form-group">
      <label id="mon">Target Month </label>
      </div>
    <div class="form-group col-md-6">
   
	<input 
      type="month" 
      class="form-control" 
      name="month" 
      id="tenantly" 
      value="<?= date('Y-m') ?>" 
      required
       >
         </div>
        <div class="form-group col-md-2">
        <button type="submit" name="btn_save" id="sentInvo" class="btn btn-sm btn-primary" >
            
        Send and generate
        <span id="spinner-auto"></span>
    
        </button>
        </div>
        </form>
      
        
        
</div>
</div>
</div>
</div>
        
</div><!--/.module-->
</div><!--/.content-->
</div><!--/.span9-->
  


<script type="text/javascript">
$(document).ready(function () {

    $('#auto-form').on('submit', function (e) {
        e.preventDefault();

        // Show loading
        $("#spinner-auto").html('<img src="../images/ajax_loader.gif" width="30" height="30">').fadeIn();

        var formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "../deposit/recentTransaction.php",
            data: formData + "&action=save-all-invoice",
            success: function (response) {

             
                $("#spinner-auto").fadeOut(); // hide spinner

                try {
                    // Always trim to avoid whitespace issues
                    var clean = response.trim();
                    var res = JSON.parse(clean);

                    if (res.status == 1) {

                        Swal.fire({
                            icon: "success",
                            title: res.total_invoices ? `${res.total_invoices} Invoices` : " All Invoice already generated!",
                            text: res.message || "Well Done",
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // Reset form
                       // $('#auto-form')[0].reset();

                    } else {

                        Swal.fire({
                            icon: "error",
                            title: "Failed to generate",
                            text: res.message || "Something went wrong",
                        });

                    }

                } catch (err) {

                    console.error("JSON Parse Error:", err, response);

                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Invalid JSON returned from server.',
                    });
                }
            },

            error: function (xhr, status, error) {

                $("#spinner-auto").fadeOut();

                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: 'Something went wrong. Please try again.',
                });

                console.error("AJAX Error:", error);
            }
        });
    });

});
</script>

