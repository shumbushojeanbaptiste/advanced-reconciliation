
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
    <label for="tenantly" class="fw-bold">From</label>
  </div>

   <div class="form-group col-md-2">
    <input 
      type="date" 
      class="form-control" 
      name="date_from" 
      id="date_from" 
      value="<?= date('Y-m-d') ?>" 
      required
    >
  </div>
  <div class="form-group col-md-1"></div>

  <div class="form-group col-md-1 lblClass">
    <label for="tenantly" class="fw-bold">up-to</label>
  </div>

  <div class="form-group col-md-2">
    <input 
      type="date" 
      class="form-control" 
      name="date_to" 
      id="date_to" 
      value="<?= date('Y-m-d') ?>" 
      required
    >
  </div>
<div class="form-group col-md-1"></div>
  <div class="form-group col-md-1"> <label for="multi-select2">Type</label></div>
        <div class="form-group col-md-2">
         
          <select id="action" class="form-control select2" name="action" data-placeholder="Choose Service(s)..." style="width:100%" required >
                             <option value="ALL">ALL</option>
                               <option value="INCOME">INCOME</option>
                                 <option value="EXPENSE">EXPENSE</option>
                               
          </select>
                
        
  </div>
  <div class="form-group col-md-1"></div>
  <div class="form-group col-md-10"></div>

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
   
  var date_from =$("#date_from").val();
  var date_to =$("#date_to").val();
  var action = $("#action").val();
    // Remove existing suggestion box if any
  
    $("#resultedOK").show();


   $("#resultedOK").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />');
  
    $.ajax({
        type: "POST",
        url: "../finance/base/statistics-overview.php",
        data: { date_from : date_from, date_to : date_to, action : action }, 
        success: function(data) {

       
           
            $("#resultedOK").html(data);
           
        }
    });
}
</script>


