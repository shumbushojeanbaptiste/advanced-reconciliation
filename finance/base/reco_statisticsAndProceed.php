  
   <div class="col-md-12 col-xl-12">
  <h1 class="h3 mb-3">Total Sammary and Decision</h1>

					<div class="row">
						<div class="col-12">
							
								<div class="card-body m-sm-3 m-md-5">
									<div class="mb-4">
										 <strong>  <small>In progress <span class="float-end"><?= $finishedPercent ?> % </span>  |</small> <?= $description ?> </small></strong>,
										<br />
										Covered  <strong><?= $period ?> </strong> (RWF) #<?= $code ?>
									</div>

									

									<table class="table table-sm">
										<thead>
											<tr>
												<th>Description</th>
												<th>Total Transaction(s)</th>
												<th class="text-end">Balance <strong>(RWF)</strong></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Bank Transactions</td>
												<td><?= $i; ?></td>
												<td class="text-end"> <?= number_format($totalCredit - $totalDebit, 2); ?></td>
											</tr>
											<tr>
												<td>Internal Transaction </td>
												<td> <?= $i2 ?></td>
												<td class="text-end">  <?= number_format($totalAmount - $totalCharges, 2); ?></td>
											</tr>
											<tr>
												<td> Diffrence</td>
												<td>-</td>
												<td class="text-end text-danger">

                                                <?php $difference = ($totalCredit - $totalDebit) - ($totalAmount - $totalCharges); ?>
                                                <?= number_format($difference, 2); ?>
                                                <?php
                                                if ($difference < 0) {
                                                    $text="text-danger";
                                                } else if ($difference == 0) {
                                                     $text="";
                                                } else {
                                                     $text="text-success";
                                                }
                                                ?>
                                                </td>
											</tr>
											<tr>
												<th>&nbsp;</th>
												<th>Target </th>
												<th class="text-end <?= $text ?>">
                                                    <?php 
                                                    if ($difference < 0) {
                                                        $target = -1 * $difference;
                                                        $message = " You can removing this, Internal transaction exceed";
                                                    } 
                                                    else if ($difference == 0) {
                                                        $target = 0;
                                                        $message = " Perfect, no action needed.";
                                                    }
                                                    else {
                                                        $target =  $difference;
                                                        $message = " You can add this, Internal transaction missing";
                                                    }
                                                        
                                                    echo number_format($target, 2)." :".$message;?>
                                                    
                                                </th>
											</tr>
											
											<tr>
												<th>&nbsp;</th>
												<th>Remain on Target </th>
												<th class="text-end">
                                                <?php
                                                $remain = 100- $finishedPercent;
                                                ?>
                                                <?= number_format($remain, 2). " %"; ?>
                                                </th>
											</tr>
										
										</tbody>
									</table>

									<div class="text-center">
										<p class="text-sm">
											<strong>Extra note:</strong> 
										Please review the above summary and decide whether to proceed with the reconciliation process or reset and restart. If you choose to proceed, the system will guide you through the next steps to complete the reconciliation. If you choose to reset, all current progress will be cleared, and you can start the process again from the beginning.
										</p>
                                    <span id="successMsg" >
										<a href="javascript:void(0)"  onClick="resetRestart('<?= $code ?>')" class="btn btn-danger">
											
										<span id="spinnerReset"> </span>
										<span id="spinnerReset_processing">Reset and Restart </span>
										
										</a>
                                        <?php if($difference == 0): ?>   
                                            <a href="javascript:void(0)"  onClick="finilize('<?= $code ?>')" class="btn btn-primary">
                                        
											<span id="spinnerFini"> </span>
											<span id="spinnerFini_processing">Next and Proceed </span>
										</a>
                                        <?php endif ?>

										</span>

										
									</div>
								</div>
							
						</div>
					</div>
					
    </div>


	<script>
function finilize(code) {

    if (!code || code <= 0) {
        alert("Invalid reconciliation code");
        return;
    }

    // Show spinner
    $('#spinnerFini')
        .html("<img src='../img/ajax_loader.gif' width='15'>")
        .fadeIn('fast');

    $('#spinnerFini_processing')
        .html('Please Wait...')
        .fadeIn('fast');

    $.ajax({
        type: "POST",
        url: "../finance/controller/reconciliationController.php",
        data: { code: code, action: "finilize" },
        dataType: "json", // ✅ Let jQuery parse JSON automatically
        success: function(res) {

            $('#spinnerFini').fadeOut('fast');
            $('#spinnerFini_processing').html('Done').fadeIn('fast');

            if (res.status === 'success') {
                $('#successMsg')
                    .html('<div class="alert alert-success">' + res.message + '</div>')
                    .fadeIn();
            } else {
                $('#successMsg')
                    .html('<div class="alert alert-danger">' + res.message + '</div>')
                    .fadeIn();
            }
        },
        error: function(xhr, status, error) {

            $('#spinnerFini').fadeOut('fast');
            $('#spinnerFini_processing').html('Error').fadeIn('fast');

            $('#successMsg')
                .html('<div class="alert alert-danger">Server Error: ' + error + '</div>')
                .fadeIn();

            console.error(xhr.responseText);
        }
    });

	
}

function resetRestart(code) {

    if (!code || code <= 0) {
        alert("Invalid reconciliation code");
        return;
    }

    // Show spinner
    $('#spinnerReset')
        .html("<img src='../img/ajax_loader.gif' width='15'>")
        .fadeIn('fast');

    $('#spinnerReset_processing')
        .html('Please Wait...')
        .fadeIn('fast');

    $.ajax({
        type: "POST",
        url: "../finance/controller/reconciliationController.php",
        data: { code: code, action: "reset-restart" },
        dataType: "json", // ✅ Let jQuery parse JSON automatically
        success: function(res) {

            $('#spinnerReset').fadeOut('fast');
            $('#spinnerReset_processing').html('Done').fadeIn('fast');

            if (res.status === 'success') {
                $('#successMsg')
                    .html('<div class="alert alert-success">' + res.message + '</div>')
                    .fadeIn();
            } else {
                $('#successMsg')
                    .html('<div class="alert alert-danger">' + res.message + '</div>')
                    .fadeIn();
            }
        },
        error: function(xhr, status, error) {

            $('#spinnerReset').fadeOut('fast');
            $('#spinnerReset_processing').html('Error').fadeIn('fast');

            $('#successMsg')
                .html('<div class="alert alert-danger">Server Error: ' + error + '</div>')
                .fadeIn();

            console.error(xhr.responseText);
        }
    });

	
}

</script>
