<?php 
require_once("../include/initialize.php");

function roomAndTenant(){
    global $db;
    global $company;

    ## total room
    $sql_shop = $db->prepare("
    SELECT COUNT(*) AS total_rooms
    FROM tbl_rooms
    WHERE company_id = ? 
      AND side = 1 
    ");

    $sql_shop->execute([$company]);
    $totalShop = $sql_shop->fetchColumn();

    // available
    $sql_shop_avai = $db->prepare("
    SELECT COUNT(*) AS total_rooms
    FROM tbl_rooms
    WHERE company_id = ? 
      AND side = 1 
      AND availability = 3
     ");

    $sql_shop_avai->execute([$company]);
    $totalShopavailabe = $sql_shop_avai->fetchColumn();
    
    ## occupied
    $sql_shop_occup = $db->prepare("
    SELECT COUNT(*) AS total_rooms
    FROM tbl_rooms
    WHERE company_id = ? 
      AND side = 1 
      AND availability = 4
     ");

    $sql_shop_occup->execute([$company]);
    $totalShopOccupied = $sql_shop_occup->fetchColumn();

    $accupiedPer= $totalShopOccupied*100/$totalShop;
    $availbalePer= $totalShopavailabe*100/$totalShop;


    ## occupacy ALL
     $sql_shop_area = $db->prepare("
    SELECT SUM(room_area) AS total_area
    FROM tbl_rooms
    WHERE company_id = ? 
      AND side = 1 
     
     ");

    $sql_shop_area->execute([$company]);
    $totalShopArea = $sql_shop_area->fetchColumn();

    ## Area accupied

     $sql_shop_area_occupied = $db->prepare("
    SELECT SUM(room_area) AS total_area
    FROM tbl_rooms
    WHERE company_id = ? 
      AND side = 1 
    AND availability = 4
     
     ");

    $sql_shop_area_occupied->execute([$company]);
    $totalShopArea_occupied = $sql_shop_area_occupied->fetchColumn();

     ## Area availabel

     $sql_shop_area_vailable = $db->prepare("
    SELECT SUM(room_area) AS total_area
    FROM tbl_rooms
    WHERE company_id = ? 
      AND side = 1 
    AND availability = 3
     
     ");

    $sql_shop_area_vailable->execute([$company]);
    $totalShopArea_available = $sql_shop_area_vailable->fetchColumn();


    ## client 
    $sql_clients = $db->prepare("
    SELECT COUNT(*) AS total_clinet
    FROM tbl_tenants
    WHERE ten_staff = ? 
     ");

    $sql_clients->execute([$company]);
    $totalClient = $sql_clients->fetchColumn();



## client 
   $sql_clients_has_shop = $db->prepare("
    SELECT COUNT(DISTINCT tent_id) AS total_clients
    FROM tbl_assign
    WHERE company_id = ?
      AND assgn_status = 1
");

$sql_clients_has_shop->execute([$company]);
$totalClientWithShops = $sql_clients_has_shop->fetchColumn();

## clent no shop
$client_no_shop= $totalClient-$totalClientWithShops;

## with shop percentage

$clientShopPercentage= $totalClientWithShops*100/$totalClient;


    
    

##  contract

   $sql_clients_contract = $db->prepare("
    SELECT COUNT(DISTINCT tenant_id) AS total_contract
    FROM tbl_ten_contracts
    WHERE cpn_id = ?
     
");

$sql_clients_contract->execute([$company]);
$totalClientContract = $sql_clients_contract->fetchColumn();

# active


   $sql_clients_contract_active = $db->prepare("
    SELECT COUNT(DISTINCT tenant_id) AS total_contract
    FROM tbl_ten_contracts
    WHERE cpn_id = ?
    AND cntr_status = 1
     
");

$sql_clients_contract_active->execute([$company]);
$totalClientContractActive = $sql_clients_contract_active->fetchColumn();

$contractActivePer= $totalClientContractActive*100/$totalClientContract;






?>
 <div class="row my-4">

              <div class="col-md-3">
                  <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Total Shops</h5>
                                        </div>

                                        <div class="col-auto">
                                            <div class="stat text-primary">
                                                <i class="align-middle" data-feather="truck"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-1 mb-3"><?= $totalShop ?></h1>
                                    <div class="mb-0">
                                        <span class="badge badge-success"><?= $totalShopOccupied ?> (<?= number_format($accupiedPer,1)?> %) </span>
                                        <span class="text-muted"><small>Occupied</small></span>
                                        <br>
                                         <span class="badge badge-primary"><?= $totalShopavailabe ?>  (<?= number_format($availbalePer,1)?> %) </span>
                                         <span class="text-muted"><small>Available</small></span>
                                    </div>
                                </div>
                     </div>
                </div> <!-- /. col -->
                <div class="col-md-3">
                  <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Toatal Clients</h5>
                                        </div>

                                        <div class="col-auto">
                                            <div class="stat text-primary">
                                                <i class="align-middle" data-feather="users"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-1 mb-3"><?= $totalClient ?></h1>
                                    <div class="mb-0">
                                        <span class="badge badge-success"><?= $totalClientWithShops ?> ( <?= number_format($clientShopPercentage,1) ?>%)</span>
                                        <span class="text-muted">has shop</span>
                                        <br>
                                        <span class="badge badge-warning"><?= $client_no_shop ?> ( <?= number_format((100-$clientShopPercentage),1) ?>%)</span>
                                        <span class="text-muted">no shop</span>
                                    </div>
                                </div>
                            </div>
                </div> <!-- /. col -->
                <div class="col-md-3">
                    <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Total Contract(s)</h5>
                                        </div>

                                        <div class="col-auto">
                                            <div class="stat text-primary">
                                                <i class="align-middle" data-feather="dollar-sign"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-1 mb-3"><?= $totalClientContract ?></h1>
                                    <div class="mb-0">
                                        <span class="badge badge-success"><?= $totalClientContractActive ?> ( <?= number_format($contractActivePer,1)?>%)</span>
                                        <span class="text-muted">valid Contract</span>
                                        <br>
                                        <span class="badge badge-warning"><?= $totalClientContract-$totalClientContractActive ?> ( <?= number_format((100-$contractActivePer),1)?>%)</span>
                                        <span class="text-muted">Expired </span>
                                    </div>
                                </div>
                            </div>
                </div> <!-- /. col -->
                <div class="col-md-3">
                 <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Total Occupacy</h5>
                                        </div>

                                        <div class="col-auto">
                                            <div class="stat text-primary">
                                                <i class="align-middle" data-feather="shopping-cart"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="mt-1 mb-3"><small><?= number_format($totalShopArea,2) ?> m<sup>2</sup></small></h1>
                                    <div class="mb-0">
                                        <span class="badge badge-danger"><?= number_format($totalShopArea_occupied,2); ?>  m<sup>2</sup></span>
                                        <span class="text-muted">Occupied</span>
                                        <br>
                                        <span class="badge badge-success"><?= number_format($totalShopArea_available,2); ?>  m<sup>2</sup></span>
                                        <span class="text-muted">Free Space</span>
                                    </div>
                                </div>
                            </div>
                </div> 
</div>

<?php  
}

function allInvoicePayment(){
    global $db;
    global $company;
    ?>
     <?php
                
    			$date_year = date('Y-m');
    			$date_yearForStatement=date('Y-m');
              $sql_us = $db->prepare("
                SELECT SUM(invoice_fees) AS sm 
                FROM tbl_invoice
                WHERE cpny_ID = ?
                AND DATE_FORMAT(invoice_date, '%Y-%m') = ?
                ");

                $sql_us->execute([$company, $date_year]);
                $fetch_us = $sql_us->fetch(PDO::FETCH_ASSOC);

                
                $sql_us1 = $db->prepare("SELECT SUM(pay_amount) as sm FROM `tbl_payment` WHERE pay_user='".$company."' AND DATE_FORMAT(pay_due_date, '%Y-%m') ='".$date_year."'");
                
                $sql_us1->execute();
                $fetch_us1 = $sql_us1->fetch();
                // in (select inv_ref_no from tbl_invoice where tbl_invoice.cpny_ID=$company) 
                
                $sql12 = $db->prepare("
                SELECT SUM(invoice_fees) AS total_invoice
                FROM tbl_invoice
                WHERE cpny_ID = ?
                ");

                $sql12->execute([$company]);

                $total_invoice = $sql12->fetchColumn();

                // and DATE_FORMAT(invoice_date, '%Y-%m') <='".$date_year."'
                //last invoice month
                $sql12invo = $db->prepare("SELECT * FROM `tbl_invoice` WHERE tbl_invoice.cpny_ID=$company order by invoice_date desc limit 1");
                $sql12invo->execute();
                $rowinvo= $sql12invo->fetch();
                $invodate=$rowinvo['invoice_date'];
                
                 $sql122 = $db->prepare("SELECT SUM(pay_amount) as total_payment FROM `tbl_payment` WHERE pay_user='".$company."' ");
                $sql122->execute();
                $total_payment = $sql122->fetchColumn();
                // and invoice_date <='".$invodate."'
                
                $stmt = $db->prepare("SELECT SUM(pay_amount) AS pay_amount4 FROM  tbl_payment where pay_user='".$company."' and status=1  and  DATE_FORMAT(pay_due_date, '%Y-%m') ='".$date_year."' ");
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $sumtot = $row['pay_amount4'];
                
                
                $stmt1 = $db->prepare("SELECT SUM(invoice_fees) AS invoice_fees FROM  tbl_invoice where cpny_ID='".$company."'  and  DATE_FORMAT(invoice_date, '%Y-%m') ='".$date_year."' ");
                $stmt1->execute();
                
                $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                $sumtot1 = $row1['invoice_fees'];
                
                
                
                ?>
              <div class="row">
                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary">
                            <i class="fe fe-16 fe-chevrons-up text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="small text-muted mb-0">All Invoices</p>
                          <span class="h3 mb-0"><small><?php echo number_format($total_invoice); ?></small></span>
                          <!--<span class="small text-success">+16.5%</span>-->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-success">
                            <i class="fe fe-16 fe-chevrons-down text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="small text-muted mb-0">All Payments</p>
                          <span class="h3 mb-0"><small><?php echo number_format($total_payment); ?></small></span>
                          <!--<span class="small text-success">+16.5%</span>-->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow bg-primary text-white border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-primary-light">
                            <i class="fe fe-16 fe-filter text-white mb-0"></i>
                          </span>
                        </div>
                        <?php 
                           $sql = $db->prepare("
    SELECT 
       
        COALESCE(SUM(p.pay_amount), 0) AS total_payments,
        COALESCE(inv.total_invoices, 0) AS total_invoices,
        (COALESCE(inv.total_invoices, 0) - COALESCE(SUM(p.pay_amount), 0)) AS difference
    FROM tbl_tenants t
    LEFT JOIN tbl_payment p 
        ON p.pay_ref_no = t.ten_ref_no
        AND p.status != 0
    LEFT JOIN (
        SELECT inv_ref_no, SUM(invoice_fees) AS total_invoices
        FROM tbl_invoice
        GROUP BY inv_ref_no
    ) inv ON inv.inv_ref_no = t.ten_ref_no
    LEFT JOIN tbl_assign a 
        ON a.tent_id = t.ten_id
        AND a.assgn_status = 0
    WHERE t.ten_staff = ?
      AND t.ten_id NOT IN (
            SELECT tent_id 
            FROM tbl_assign 
            WHERE assgn_status = 1
      )
    GROUP BY t.ten_id
    HAVING difference > 0
    ORDER BY difference ASC
");

$sql->execute([$company]);
$rows = $sql->fetchAll();

$differTotalBIHEMU = 0;

foreach ($rows as $r) {
    $differTotalBIHEMU += $r['difference'];
}


                        
                        ?>
                        <div class="col pr-0">
                          <p class="small text-white mb-0">G.Balance</p>
                          <span class="h4 mb-0 text-white"><small><?php echo number_format(($total_invoice-$total_payment)-$differTotalBIHEMU); ?></small></span>
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                  <div class="col-md-6 col-xl-3 mb-4">
                  <div class="card shadow border-0">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-3 text-center">
                          <span class="circle circle-sm bg-danger">
                            <i class="fe fe-16 fe-chevrons-down text-white mb-0"></i>
                          </span>
                        </div>
                        <div class="col pr-0">
                          <p class="small text-muted mb-0"> Unpaid Risk (Frw) </p>
                          <span class="h3 mb-0"><small><?php echo number_format($differTotalBIHEMU); ?></small></span>
                          <!--<span class="small text-success">+16.5%</span>-->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> 


 <?php
}
function pieChart(){
    global $db;

    $date_year = $_POST['year_id'];
    $company   = $_POST['company'];
    $date_mnth = $_POST['month_Id'];

    $month = $date_year . "-" . $date_mnth;

    // ✅ INVOICE
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(invoice_fees), 0) AS total_invoice
        FROM tbl_invoice
        WHERE DATE_FORMAT(invoice_date, '%Y-%m') = :invoice_date
    ");
    $stmt->execute([':invoice_date' => $month]);
    $total_invoice = $stmt->fetchColumn();

    // ✅ DEPOSIT
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(dep_amount), 0) AS total_deposit
        FROM tbl_deposit
        WHERE DATE_FORMAT(due_to, '%Y-%m') = :deposit_date
    ");
    $stmt->execute([':deposit_date' => $month]);
    $total_deposit = $stmt->fetchColumn();

    // ✅ PAYMENT
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(pay_amount), 0) AS total_payment
        FROM tbl_payment
        WHERE DATE_FORMAT(invoice_date, '%Y-%m') = :payment_date
    ");
    $stmt->execute([':payment_date' => $month]);
    $total_payment = $stmt->fetchColumn();

    // BALANCE
    $unpaid_balance = $total_invoice - $total_payment;

    // RETURN JSON
    echo json_encode([
        "total_deposit"    => (int)$total_deposit,
        "total_invoice"    => (int)$total_invoice,
        "total_payment"    => (int)$total_payment,
        "unpaid_balance"   => (int)$unpaid_balance
    ]);
}

function barCharPayment() {

    global $db;

    $year = $_POST['year'];
    

    $monthly_payments = [];

    // Loop through all months 01–12
    for ($m = 1; $m <= 12; $m++) {

        $month = str_pad($m, 2, "0", STR_PAD_LEFT);
        $year_month = $year . "-" . $month;

        $stmt = $db->prepare("
            SELECT COALESCE(SUM(pay_amount), 0) AS total_payment
            FROM tbl_payment
            WHERE  DATE_FORMAT(invoice_date, '%Y-%m') = :payment_date
        ");

        $stmt->execute([
           
            ":payment_date" => $year_month
        ]);

        $monthly_payments[] = (float)$stmt->fetchColumn();
    }

    echo json_encode([
        "monthly_payments" => $monthly_payments
    ]);
}

function barCharInvoice(){

  global $db;

    $year = $_POST['year'];
    

    $monthly_payments = [];

    // Loop through all months 01–12
    for ($m = 1; $m <= 12; $m++) {

        $month = str_pad($m, 2, "0", STR_PAD_LEFT);
        $year_month = $year . "-" . $month;

        $stmt = $db->prepare("
            SELECT COALESCE(SUM(invoice_fees), 0) AS total_invoice
            FROM tbl_invoice
            WHERE  DATE_FORMAT(invoice_date, '%Y-%m') = :invoice_date
        ");

        $stmt->execute([
           
            ":invoice_date" => $year_month
        ]);

        $monthly_invoices[] = (float)$stmt->fetchColumn();
    }

    echo json_encode([
        "monthly_invoice" => $monthly_invoices
    ]);

}

function barCharInvoicePaymentBalance() {
    global $db;

    $year = $_POST['year'];

    $monthly_invoice = [];
    $monthly_payment = [];
    $monthly_balance = [];

    for ($m = 1; $m <= 12; $m++) {

        $month = str_pad($m, 2, "0", STR_PAD_LEFT);
        $year_month = $year . "-" . $month;

        // Invoice
        $stmt1 = $db->prepare("
            SELECT COALESCE(SUM(invoice_fees), 0)
            FROM tbl_invoice
            WHERE DATE_FORMAT(invoice_date, '%Y-%m') = :ym
        ");
        $stmt1->execute([":ym" => $year_month]);
        $invoice = (float)$stmt1->fetchColumn();

        // Payment
        $stmt2 = $db->prepare("
            SELECT COALESCE(SUM(pay_amount), 0)
            FROM tbl_payment
            WHERE DATE_FORMAT(invoice_date, '%Y-%m') = :ym
        ");
        $stmt2->execute([":ym" => $year_month]);
        $payment = (float)$stmt2->fetchColumn();

        // Balance = invoice − payment
        $balance = $invoice - $payment;

        $monthly_invoice[] = $invoice;
        $monthly_payment[] = $payment;
        $monthly_balance[] = $balance;
    }

    echo json_encode([
        "invoice" => $monthly_invoice,
        "payment" => $monthly_payment,
        "balance" => $monthly_balance
    ]);
}





// Handle AJAX action
$action = $_POST['action'] ?? '';

switch($action) {
    case 'room-count':
        roomAndTenant();
        break;
    case 'all-invo-pay':
        allInvoicePayment();
        break;
    case 'pie-chart':
        pieChart();
        break;
    case 'bar-chart-pay':
        barCharPayment();
        break;
     case 'bar-chart-invo':
        barCharInvoice();
        break;
      case 'bar-chart-invoicePaymentBalance':
        barCharInvoicePaymentBalance();
        break;
   
        
    default:
        echo 'Invalid action';
        break;
}
?>