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
                  <h2 class="h5 page-title"><?= $title ?> </h2>
            
                </div>
              
                <div class="col-auto">
                 
                </div>
              </div>
             
<div class="card my-4 shadow">
  <div class="card-body">
    <div class="row">

      <!-- RIGHT COLUMN -->
      <div class="col-md-12">
        <br>
        <div id="dataDepart"></div>

        <div class="row">

    <!-- Vertical Nav Tabs -->
    <div class="col-md-5">
          <span class="circle circle-md bg-primary mb-3 d-inline-flex align-items-center justify-content-center">
            <i class="fe fe-layout fe-24 text-white"></i>
          </span>
        <ul class="nav nav-tabs flex-column" id="myTab" role="tablist">
            <?php 
            // fetc first account ID

             $sqlAC = $db->prepare("SELECT account_id, account_name, account_reference, account_method_type, balance, created_at,method_name FROM tbl_accounts
                 LEFT JOIN payment_methods ON payment_methods.method_id= tbl_accounts.account_method_type order by tbl_accounts.account_id asc limit 1");
                 $sqlAC->execute();

                $ac = $sqlAC->fetch(PDO::FETCH_ASSOC);
                $Firstaccount_id=$ac['account_id'];
                $firstDescription =  htmlspecialchars($ac['account_name']) ." | ".htmlspecialchars($ac['method_name']);

            // Fetch wallet methods from database
              $methods = $db->query("
              SELECT account_id, account_name, account_reference, account_method_type, balance, created_at,method_name FROM tbl_accounts
              LEFT JOIN payment_methods ON payment_methods.method_id= tbl_accounts.account_method_type
              ");
              $accounts = $methods->fetchAll(); ?>
            <?php
            $no=0;
             foreach($accounts as $data){ 
                $no++;
                if($no==1){
                    $active='active';

                }
                else {
                     $active='';


                }
                $account_id=$data['account_id'];
                $accountsDescription= htmlspecialchars($data['account_name']) ." | ".htmlspecialchars($data['method_name'])
                ?>
            <li class="nav-item">
                <a class="nav-link <?= $active ?>"
                   id="general-tab"
                   data-toggle="tab"
                   href="#general"
                   role="tab"
                   aria-controls="general"
                   aria-selected="true"
                   onclick="nextToRecords(<?= $account_id ?>,'<?= $accountsDescription ?>')">
                    <?= $no." . ".$accountsDescription ?>

                    <span id="spinner<?= $account_id ?>"></span>
                </a>
            </li>
            <?php } ?>


        </ul>
    </div>

    <!-- Tab Content -->
    <div class="col-md-7">
        <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade show active"
                 id="general"
                 role="tabpanel"
                 aria-labelledby="general-tab">
                <div id="recent-data"> #</div>
            </div>

            <div class="tab-pane fade"
                 id="deposited"
                 role="tabpanel"
                 aria-labelledby="deposited-tab">
                <div id="recent-deposit"></div>
            </div>

            <div class="tab-pane fade"
                 id="invoice"
                 role="tabpanel"
                 aria-labelledby="invoice-tab">
                <div id="recent-invoice"></div>
            </div>

            <div class="tab-pane fade"
                 id="invo_paid"
                 role="tabpanel"
                 aria-labelledby="invo_paid-tab">
                <div id="recent-invo_paid"></div>
            </div>

            <div class="tab-pane fade"
                 id="invo_and_pay"
                 role="tabpanel"
                 aria-labelledby="invo_and_pay-tab">
                <div id="recent-invo_and_pay"></div>
            </div>

        </div>
    </div>

    </div>


        
      </div>

    </div>


    <div class="row">
 <div class="col-md-8 col-xl-12">
      
           <div id="recent-income"></div>
                    
    </div>
</div>

  </div>
</div>

          </div> <!-- .row -->



        </div> <!-- .container-fluid -->
 
<script>
    var firstID = <?php echo json_encode($Firstaccount_id); ?>;
    var firstDescs = <?php echo json_encode($firstDescription); ?>;
</script>       
<script type="text/javascript">
  

recent_expense(1);
nextToRecords(firstID,firstDescs)


 function nextToRecords(account_id,description) {
   
    $('#spinner' + account_id)
        .html("<img src='../img/ajax_loader.gif' width='15'>")
        .fadeIn('fast');

    $.ajax({
        url: '../finance/base/debit-records-form.php',
        type: 'POST',
        data: {
            action: 'record-new',
            account_id: account_id,
            description: description


        },
        success: function (data) {
            $("#recent-data").html(data);
            $('#spinner' + account_id).fadeOut('fast');
            recent_expenseAccount(account_id,description);
        },
        error: function () {
            $("#recent-data").html("<p style='color:red;'>Failed to load data</p>");
            $('##spinner' +account_id).fadeOut('fast');
        }
    });
}

function cancelIncome(id,account_id,description,amount) {
    $('#remove_spinner' + id)
        .html("<img src='../img/ajax_loader.gif' width='15'>")
        .fadeIn('fast');
    $.ajax({
        url: '../finance/controller/creditController.php',
        type: 'POST',
        data: {
            action: 'delete-income-data',
            income_id: id,
            account_id: account_id,
            description: description,
            amount: amount
        },
        success: function (res) {

             if (res.status === 'success') {
                // reload recent income list
                recent_incomeAccount(account_id,description)
                balance(account_id);
            } else {
                alert(res.message || 'Failed to delete income');
            }
            
        },
        error: function () {
            alert("Error deleting account");
        }
    });
}

function recent_expense(val) {
    $("#recent-income").html('<div class="text-center"><span>Loading...</span></div>').fadeIn();

    $.ajax({
        type: "POST",
        url: "../finance/controller/debitController.php",
        data: { keyword1: val, action: "recent" }, // action as string
        success: function(data) {
            $("#recent-income").html(data).fadeIn();
        },
        error: function(xhr, status, error) {
            $("#recent-income").html('<span class="text-danger">Error loading recent deposits</span>' + error);
            console.error(error);
        }
    });
}
function recent_expenseAccount(id,description) {
    $("#recent-income").html('<div class="text-center"><span>Loading...</span></div>').fadeIn();

    $.ajax({
        type: "POST",
        url: "../finance/controller/debitController.php",
        data: { keyword1: id,description:description, action: "recent-account" }, // action as string
        success: function(data) {
            $("#recent-income").html(data).fadeIn();
        },
        error: function(xhr, status, error) {
            $("#recent-income").html('<span class="text-danger">Error loading recent deposits</span>' + error);
            console.error(error);
        }
    });
}

</script>
        
  