<?php
require_once("../include/initialize.php");



/* =========================
   AUTH CHECK
   ========================= */
if (empty($sess_id) || empty($phone)) {
    header("Location: ../index");
    exit;
}


/* =========================
   GET ROUTE
   ========================= */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

$segments = explode('/', $uri);
$route = end($segments);
/* Default route */
if ($route === 'finance' || $route === '') { 
    $route = 'home';
}

/* =========================
   ROUTING
   ========================= */
switch ($route) {

    case 'home':
        $title   = "Dashboard";
        $content = "dashboard.php";
        break;

    case 'wallet-account':
        $title   = "Manage Accounts";
        $content = "../finance/base/accounts.php";
        break;
    case 'Wallets':
        $title   = "Manage Accounts";
        $content = "../finance/base/accounts.php";
        break;
    case 'expense-type':
        $title   = "Manage Expense Type";
        $content = "../finance/base/expense-type.php";
        break;
     case 'income-type':
        $title   = "Manage Income Type";
        $content = "../finance/base/income-type.php";
        break;
     case 'income-type2':
        $title   = "Manage Income Type";
        $content = "../finance/base/progress-bar-income.php";
        break;
      case 'income-type3':
        $title   = "Manage Income Type";
        $content = "../finance/base/pagination-data.php";
        break;
    case 'income-csv':
        $title   = "Manage Income Type";
        $content = "../finance/base/uploadCsv.php";
        break;
    case 'initialization':
        $title   = "Reconciliation Initialization";
        $content = "../finance/base/reco-init.php";
        break;
    case 'credited':
        $title   = "Credit Account Transactions";
        $content = "../finance/base/credit-trans.php";
        break;
    case 'debited':
        $title   = "Debit Account";
        $content = "../finance/base/debit-trans.php";
        break;
    case 'reconciliation':
        $title   = "Account Reconciliation and Fix Transaction";
        $content = "../finance/base/reconc_process.php";
        break;
     case 'statements':
        $title   = "Financials Statements";
        $content = "../finance/base/statistics-statement.php";
        break;
    case 'journal':
        $title   = "Journal Transactions";
        $content = "../finance/base/journal-statement.php";
        break;
    case 'audit-history':
        $title   = "Verified Transactions";
        $content = "../finance/base/verified-transaction.php";
        break;
    
    
    


    case 'incomes':
        $title   = "Income Types";
        $content = "income.php";
        break; 

    case 'expenses':
        $title   = "Expenses";
        $content = "expenses.php";
        break;

    default:
        $title = "Dashboard";
        $content = "dashboard.php";
        break; 
}

/* =========================
   LOAD LAYOUT
   ========================= */
require_once("../theme/navigation.php");
