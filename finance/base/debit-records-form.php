   <?php 
require_once("../../include/initialize.php");
?>
   <?php  
   $account_id=$_POST['account_id'];;
   $account_name=$_POST['description'];

   $stmt = $db->prepare("
            SELECT reconciliation_mode.*, reco_mode_type.*
            FROM reconciliation_mode 
            LEFT JOIN reco_mode_type ON reconciliation_mode.mode_type=reco_mode_type.m_id
            WHERE account_id = :account_id and (status=1 or status=3)
            LIMIT 1
        ");

        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {

   ?>
   <input type="hidden" id="names" value="<?= $account_name ?>"  >
   <div class="col-md-12 d-flex align-items-start text-right">
        <div class="w-100">

          <span class="circle circle-md bg-primary mb-3 d-inline-flex align-items-center justify-content-center">
            <i class="fe fe-layers fe-16 text-white"></i> 
          </span>
          <h5> <?= $account_name ?> <span></span></h5>
           <div class="card-header  text-info">
               
                 <h5 class="card-title mb-0"> <span class="text-success"><span id="balance_area"><?php echo number_format(0,2)." Frw"; ?></span></span></h5>
            </div>


            <form action="" method="POST" id="save_expense_data">
                <input type="hidden" name="action" value="save_expense_data"  >
                <input type="hidden" name="account_id" id="account_id" value="<?= $account_id ?>" >
                             
                       <h6>Expense Type</h6>
          <div class="form-group">
            <select name="expense_type" class="form-control" required>
             
              <?php
              // Fetch wallet methods from database
              $methods = $db->query("
              SELECT `category_id`, `category_name`,`description` FROM `expense_categories`
              ");
              while ($method = $methods->fetch()) {
                  echo "<option value='" . htmlspecialchars($method['category_id']) . "'>" .htmlspecialchars($method['category_name'])." | ".htmlspecialchars($method['description']). "</option>";
              }
              ?>
          
            </select>
          </div>
                           <h6 class="mb-3">Due date:</h6>
                      <!--<form action="" method="POST">-->
                        <div class="form-group">
                          <input type="date" name="due_date"  max="<?= date("Y-m-d") ?>" value="<?= date("Y-m-d") ?>" class="form-control"  required >
                        </div>

                              <h6 class="mb-3">Amount:</h6>
                      <!--<form action="" method="POST">-->
                        <div class="form-group">
                          <input type="number" min="1" name="amount"  placeholder="Write Expense amount..." class="form-control"  required >
                        </div>
                       
                               <h6 class="mb-3">Charges:</h6>
                      <!--<form action="" method="POST">-->
                        <div class="form-group">
                          <input type="number" min="0" name="charges"  placeholder="Write Debited chargres..." class="form-control"  required >
                        </div>
                       
                           <h6 class="mb-3">Description:</h6>
                    
                        <div class="form-group">
                          
                         <textarea  rows="2" name="description"  placeholder="Write the description..." class="form-control" ></textarea>
                        </div>
                       
                      
                        <hr class="my-4" style="width:100%;background-color:#000; height: 1px; border: 0; ">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="form-check form-check-inline ml-1">
                            
                          </div>
                          
                         <button type="submit"  class="btn btn-lg bg-primary text-white btn-block waves-effect" id="savebtn">
                             <span id="indicator_save"></span>  Save and Continue <i class="fe fe-arrow-right fe-16 ml-2"></i></button>
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

    





      $row= $stmt->fetch();
      $code= $row['reco_code'];
      $mode_type=$row['re_mode_name'];
      $period = date('d M Y', strtotime($row['from_date'])) . '</b> up to <b>' . date('d M Y', strtotime($row['to_date']));

      // Load bank transaction
     $stmt = $db->prepare("
    SELECT 
        COALESCE(SUM(debit_amount), 0)  AS total_debit,
        COALESCE(SUM(credit_amount), 0) AS total_credit
    FROM tbl_bank_transaction
    WHERE account_id = :account_id
      AND reference_code = :reference_code and (status=1 OR status=2)
");

$stmt->execute([
    ':account_id' => (int)$account_id,
    ':reference_code' => $code
]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total= (float)$row['total_credit'] + (float)$row['total_debit'];
$balance = (float)$row['total_credit'] - (float)$row['total_debit'];

// reconcilied
$recStmt = $db->prepare("
    SELECT
        COALESCE(SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END), 0) AS reconciled_credit,
        COALESCE(SUM(CASE WHEN transaction_type = 'expense'  THEN amount ELSE 0 END), 0) AS reconciled_debit
    FROM tbl_reconciliation
    WHERE reference_code = :reference_code
      AND account_id = :account_id
");

$recStmt->execute([
    ':reference_code' => $code,
    ':account_id' => (int)$account_id
]);

$rec = $recStmt->fetch(PDO::FETCH_ASSOC);

$reconciledCredit = (float)$rec['reconciled_credit'];
$reconciledDebit  = (float)$rec['reconciled_debit'];
$recTotal=$reconciledCredit + $reconciledDebit;
$reconciledBalance = $reconciledCredit - $reconciledDebit;

$finishedPercent=$recTotal > 0 && $total > 0 ? ($recTotal / $total) * 100 : 0;
$finishedPercent = round($finishedPercent, 2);


      ?>
       	
                 <input type="hidden" name="account_id" id="account_id" value="<?= $account_id ?>" >
								<div class="card-header px-4 pt-4 text-right">
									<div class="card-actions float-end">
										<div class="dropdown position-relative">
											<a href="#" data-bs-toggle="dropdown" data-bs-display="static">
												<i class="align-middle" data-feather="more-horizontal"></i>
											</a>

											<div class="dropdown-menu dropdown-menu-end">
												<a class="dropdown-item" href="#">Pause</a>
												<a class="dropdown-item" href="#">Cancel</a>
												<a class="dropdown-item" href="#">Finish</a>
											</div>
										</div>
									</div>
									<h5 class="card-title mb-0"><?= $account_name ?></h5>
									 Reconciliation status : <div class="badge bg-warning my-2">In progress <?= $total."#".$account_id ?></div>
								</div>
								<div class="card-body px-4 pt-2 text-right">
									<p><b> <?=  $code  ?> | <?= $mode_type ?></b>, <br>
                  <b> <?= $period ?> </b>
                  
                 
                  </p>

									<h5 class="card-title mb-0"> Balance: <span class="text-success"><span id="balance_area"><?php echo number_format(0,2)." Frw"; ?></span></span></h5>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item px-4 pb-4">
										<p class="mb-2 font-weight-bold">Progress <span class="float-end"><?= $finishedPercent ?>%</span></p>
										<div class="progress progress-sm">
											<div class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
												style="width: <?= $finishedPercent ?>%;">
											</div>
										</div>

                    
									</li>
                  <li class="list-group-item px-4 pb-4">
                  <a class="btn btn-primary btn-sm btn-block" href="credited">Next to proceed</a>
                  </li>
								</ul>
							

      <?php } ?>



    <script>
       
    var account_id=$('#account_id').val();
    var description= $('#names').val();
    

balance(account_id);
    
    function balance(id) {
       

     $('#balance_area').html("<img src='../img/ajax_loader.gif' width='15'>");
     $.ajax({
        url: '../finance/controller/accountController.php',
        type: 'POST',
        data: {
            action: 'balance',
            account_id: id
        },
        
        success: function (data) {
           
            var res = JSON.parse(data);
            $('#balance_area').html(res.balance);
        },
        error: function () {
            alert("Error  account balance ");
        }
     });
}

$('#save_expense_data').on('submit', function (e) {
   
    e.preventDefault();

    $('#indicator-save').html("<img src='../img/ajax_loader.gif' width='15'>");
    $("#save_expense_data button, #save_expense_data input[type=submit]").prop("disabled", true);

    $.ajax({
    url: '../finance/controller/debitController.php',
    type: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function (res) { 
        
        $('#indicator-save').html('');
        
        
        if (res.status === 'success') {
            $('#result').html("<span class='text-success'>" + res.message + "</span>");
            balance(account_id);
            recent_expenseAccount(account_id,description);
         
            setTimeout(() => {
                
            $('#save_expense_data')[0].reset();
            $("#save_expense_data button, #save_expense_data input[type=submit]").prop("disabled", false);
            }, 1000);
        } else 
        {
            $('#result').html("<span class='text-danger'>" + res.message + "</span>");
            $("#save_expense_data button, #save_expense_data input[type=submit]").prop("disabled", false);
            
        }
    },
    error: function (xhr) {
        $('#indicator-save').html('');
        $('#result').html("<span class='text-danger'>Server error</span>" );
         $("#save_expense_data button, #save_expense_data input[type=submit]").prop("disabled", false);
    }
 });
});





    </script>