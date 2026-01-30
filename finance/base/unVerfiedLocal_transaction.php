  <?php if (!empty($localTransactions)): ?>
        <div class="table-responsive mb-3">
            <table class="table table-striped table-hover table-borderless" style="font-size:12px;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Transaction Type</th>
                        <th class="text-end">Amount (FRW)</th>
                        <th class="text-end">Charges (FRW)</th>
                         <th class="text-end">Total (FRW)</th>
                       
                       
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                           
                $i = 1;  
                $totalAmount = 0;
                $totalCharges = 0;
                
                foreach ($localTransactions as $row): 
              
                        $amount = number_format($row['amount'], 0);
                        $charges = number_format($row['charges'], 0);
                        $total = number_format($row['amount'] + $row['charges'], 0);
                        $totalAmount += $row['amount'];
                        $totalCharges += $row['charges'];
                     

                    
                 
                ?>
                    <tr>
                        <td><?= $i++; ?></td>

                        <td>
                            <b><?= htmlspecialchars(date("d/m/Y", strtotime($row['transaction_date']))); ?></b>
                        </td>

                        <td>
                            <b><?= htmlspecialchars($row['action']); ?></b>
                        </td>


                        <td class="text-end">
                            <?= $amount; ?>
                        </td>
                          <td class="text-end text-danger">
                            <?= $charges; ?>
                        </td>
                            <td class="text-end">
                                <?= $total; ?>
                            </td>
                         

                      

                        <td>
                           <?php if ($row['status'] == 0): ?>
                            <span class="fe fe-trash text-warning" title="Still under process" onClick="cancelExpense(<?= $row['transaction_id'] ?>)"></span>
                           <?php else: ?>
                          <span class="fe fe-check text-success" title="Approved transaction"></span>
                            <?php endif ?>
                          
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="3" class="text-end">Balance:</th>
                    <th class="text-end">
                        <?= number_format($totalAmount, 2); ?>
                    </th>
                    <th class="text-end text-danger">
                        <?= number_format($totalCharges, 2); ?>
                    </th>
                    <th class="text-end">
                        <?= number_format($totalAmount + $totalCharges, 2); ?>
                    </th>
                    <th></th>
                </tr>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">No records found.</p>
    <?php endif; ?>