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
 <div class="col-md-12">
    <div class="w-100 text-right">

        <!-- Icon -->
        <span class="circle circle-md bg-primary mb-3 d-inline-flex align-items-center justify-content-center">
            <i class="fe fe-layers text-white"></i>
        </span>

        <!-- Account name -->
        <h5 class="mb-2"><?= $account_name ?></h5>

        <!-- Balance -->
        <div class="card-header text-info text-right">
            <h5 class="card-title mb-0">
                <span class="text-success" id="balance_area">
                    <?= number_format(0, 2) ?> Frw
                </span>
            </h5>
        </div>

        <!-- Form -->
        <form action="" method="POST" id="save_expense_data">
            <input type="hidden" name="action" value="save_expense_data">
            <input type="hidden" name="account_id" id="account_id" value="<?= $account_id ?>">

            <!-- Alert -->
            <div class="alert alert-warning mt-3 text-right" role="alert">
                <h4 class="alert-heading">Notification!</h4>
                <p>
                   Reconciliation has not been initiated for this account yet.
                   Please start the reconciliation process to enable accurate balance tracking and transactions.
                </p>
                <hr>
                <div class="btn-list text-right">
                    <!-- <button class="btn btn-success btn-sm" type="button">Okay</button> -->
                    <button class="btn btn-primary btn-sm btn-block" type="button">Next to initiate</button>
                </div>
            </div>
        </form>

    </div>
</div>


    <?php } 
    else { 

    





      $row= $stmt->fetch();
      $code= $row['reco_code'];
      $reco_mode_id=$row['reco_mode_id'];
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
                  <a class="btn btn-primary btn-sm btn-block"  href="javascript:void(0)" onClick="proceedToMakeAudit(<?= $account_id ?>,'<?= $account_name ?>',<?= $reco_mode_id ?>,'<?= $code ?>')"> <span id="spinnerAudit"></span>Next to proceed</a>
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