<?php
    require_once ("../include/initialize.php");
if($sess_id == '' or $phone == ''){
    echo"<script>window.location.replace('../index')</script>";
}

$content='dashboard.php';
$view = (isset($_GET['file']) && $_GET['file'] != '') ? $_GET['file'] : '';

function too_many_requests() {
    $max_requests = 5; // max requests allowed
    $time_window = 60; // seconds

    $key = 'rate_limit_' . ($_SESSION['USER_ID'] ?? session_id());
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 1, 'start' => time()];
        return false;
    }

    $data = $_SESSION[$key];

    // Reset if time window passed
    if (time() - $data['start'] > $time_window) {
        $_SESSION[$key] = ['count' => 1, 'start' => time()];
        return false;
    }

    // Increment counter
    $_SESSION[$key]['count']++;

    return $_SESSION[$key]['count'] > $max_requests;
}

// Determine page
$page = $_GET['page'] ?? 'home';

// Rate-limit check
if (too_many_requests()) {
    http_response_code(429);
    echo "Too Many Requests. Please wait a minute.";
    exit;
}

switch ($view) {

	case 'home' :
    $title="Dashboard";
	$content='dashboard.php';   
	break;
	case 'home2' :
    $title="Dashboard";
	$content='dashboard2.php';   
	break;
	case 'home3' :
    $title="Dashboard";
	$content='dashboard3.php';   
	break;
	case 'home5' :
    $title="Dashboard";
	$content='../landlord/overview.php';   
	break;
	case 'analytics' :
    $title="Monthly Statistics";
	$content='../expenses/filter-analytics.php';   
	break;
	
	case 'active-clients' :
    $title="Client and shops info";
	$content='../landlord/clientAndShopsInfo.php';   
	break;
	
	case 'invopyt' :
    $title="Invo & Payment";
	$content='invopyt.php';   
	break;
	case 'rentpayment' :
    $title="Rent Payment";
	$content='Managerentpayment.php';     
	break;
	case 'tenant-paymentsNew' :
    $title="new Payment";
	$content='newPayment.php';   
	break;
	case 'unpaidmonthly' :
    $title="unpaid Balance";
	$content='unpaidbalancemonthly.php';   
	break;
	
	case 'SMS-ex' :
    $title="Dashboard";
	$content='smsexclude.php';    
	break;
	case 'listmonthly_inv' :
    $title="Monthly invoice Summary";
	$content='invoicesummary.php';    
	break; 
	case 'advance' :
    $title="advanc";
	$content='advanc.php';    
	break; 
	
	case 'unpaidBLC' :
    $title="Unpaid Balance";
	$content='unpaidBalance.php';    
	break;
	
	case 'manage-review' :
    $title="Contract";
	$content='manage-review.php';    
	break; 
	
		case 'info-messages' :
    $title="Messages";
	$content='infoSMSsent.php';   
	break;
	
	case 'chik' :
    $title="chik";
	$content='chik.php';   
	break;  
	
	
	case 'pay-tracker' :
    $title="pay-tracker";
	$content='pay-tracker.php';   
	break;
	
	case 'nextDate' :
    $title="Pay by Date";
	$content='next_DAte.php';   
	break;	
	
	case 'roomtest' :
    $title="roomtest";
	$content='roomtest.php';   
	break;
	
		case 'pay_interval' :
    $title="Contract Payment Interval";
	$content='pay_interval.php';
	break;	
	
		case 'contr_rent' :
    $title="Contract rent ";
	$content='contract_rent_amount.php';   
	break;

	case 'monrthlRecovery' :
    $title="Monthly Recovery";
	$content='monrthlRecovery.php'; 
	break;
	
	case 'map' :
    $title="Monthly Recovery";
	$content='map2.php'; 
	break;
	
		case 'CuurentlR' :
    $title="Actuals Monthly Recovery";
	$content='CrrentlyRecovery.php'; 
	break;
			case 'CuurentlRFloor' :
    $title="Recovery By Flor";
	$content='CrrentlyFloorAMT.php'; 
	break;
	
	
		case 'profile' :
    $title="Profile";
	$content='profile.php'; 
	break;
	
	case 'bill-link' :
    $title="Bill Link";
	$content='bill-link.php';
	break;



case 'bill_linkList' :
    $title="Bill Link List";
	$content='billlinkList.php';
	break;
	case 'montlyReport' :
    $title="Monthly Report";
	$content='montlyReport.php';
	break;
	
	
	case 'diff' :
    $title=" difference";
	$content='difference.php';
	break;
	
		case 'montlyReport2' :
    $title="Monthly Report";
	$content='montlyReport2.php';
	break;
	
	
		case 'ctr_changed' :
    $title="Contract cha";
	$content='montlyChanged.php';
	break;
	case 'rooms' :
    $title="Manage Rooms";
	$content='rooms.php';
	break;
	
	case 'Arooms' :
    $title="Manage Rooms";
	$content='Arooms.php';
	break;
    case 'available-rooms' :
    $title="Available Rooms";
	$content='available-rooms.php';
	break;
	
	 case 'occup' :
    $title="Occupied Rooms";
	$content='occup-rooms.php'; 
	break;
	
	case 'all-rooms' :
    $title="All Rooms";
	$content='all-rooms.php';
	break;
	case 'setdsc' :
	$title="Setting discounts";
	$content='room_setting_discounts.php';
	break;  

	case 'setcurrency' :
	$title="Setting currency";
	$content='currency_setting.php';
	break; 
	case 'new_block' :
    $title="New Block";
	$content='new_block.php';
	break;

	case 'view_blocks' :
    $title="View Block";
	$content='view_blocks.php';
	break;
		
	case 'manage-agree' :
    $title="Manage Agreements";
	$content='manage_all_agrees.php';
	break;
	
	
	case 'new_contract' :
    $title="Add Contract";
	$content='contract_asign.php';
	break;
	
	case 'contrct_rpt' :
    $title="Add Contract";
	$content='contract_repor.php';
	break;
	case 'transfer' :
    $title="Transaction";
	$content='trans_list.php';
	break;

	case 'tenmgnt' :
    $title="Manage Tenants";
	$content='manage_tnt.php';
	break;
	
	case 'tnt-rooms' :
    $title="Tenants Rooms Report";
	$content='tnt-rooms.php';
	break;

    case 'monthly_inv' :
    $title="Monthly Invoices";
	$content='month_invoice.php';
	break;

	case 'invoice' :
    $title="Manage Invoices";
	$content='manage_invoices.php';
	break;
	
	case 'tenant-payments':
    $title="View Payments";
	$content='view_payments.php';
	break;
	
	case 'balance-sheet' :
    $title="Balance Sheet";
	$content='balance_sheet.php';   
	break;
	
		case 'balance-invoice' :
    $title="Balance Sheet";
	$content='balance_invoiceBalance.php';  
	break;
	case 'tenant_leave' :
    $title="Tenant Leave";
	$content='ten_leave.php';
	break;
	
	case 'msg' :
    $title="Balance Sheet";
	$content='message.php';
	break;
	
		case 'inforselect' :
    $title="Informing Message";
	$content='InformMmessage.php';
	break;
	
    case 'success-messages' :
    $title="Balance Sheet";
    $content='succeededMessages.php';
    break;
	
	case 'qckreport' :
    $title="Quick Report";
	$content='quick_report.php';
	break;
	
	case 'recov' :
    $title="Recovery";
	$content='recovery.php';
	break;
	case 'recovList' :
    $title="Recovery List";
	$content='recoveryList.php'; 
	break;
	case 'specifyList' :
    $title="Recovery List";
	$content='specifyList.php'; 
	break;
		case 'specifyList2' :
    $title="Recovery List";
	$content='specifyList2.php'; 
	break;
		case 'recov2' :
    $title="Recovery";
	$content='recovery2.php';
	break;
	
	case 'GeneralRoom' :
    $title="Room Report";
	$content='GeneralRoom.php';
	break;
	case 'change-slp' :
    $title="Change Slip";
	$content='change-slp.php';
	break;
	case 'change-invoice':
    $title="Change Invoice";
	$content='change-invoice.php';
	break;  
	case 'new-slp2' :
    $title="New Slip";
	$content='new-slp2.php';
	break;
		case 'new-slp' :
    $title="New Slip";
	$content='new-slp.php';
	break;
	case 'pymt-sngl' :
    $title="Payment Report";
	$content='pymt-sngl.php';
	break;
	case 'stmt-indv' :
    $title="Individual Statement";  
	$content='individual-stmt.php';        
	break;
	case 'indv-msg' :
    $title="Individual Messaging";
	$content='individual-message.php';
	break;
    case 'informsms' :
    $title="Informing Client(s)";
	$content='informing-message.php';
	break;
	
	 case 'informfloor' :
    $title="Informing Client(s)";
	$content='informing-floor.php';
	break;
	case 'view_inv66' :
    $title="Invoice Reciept";
	$content='client_detail.php';
	break;
	case 'view_inv' :
    $title="Invoice Reciept";
	$content='view-inv.php';
	break;
		case 'view-inv2' :
    $title="Invoice Reciept";
	$content='view-inv2.php';
	break;
	case 'rpt-rooms' :
    $title="Rooms  Report";
	$content='room_reports.php'; 
	break;
	
	case 'changedcontracts' :
    $title="Rooms  Report";
	$content='changedcontracts.php'; 
	break;
	
	case 'quickreportrooms' :
    $title="Rooms  Report";
	$content='quick_report_rooms.php'; 
	break;
	
    case 'deposit' :
    $title="Bank Depost";
	$content='bank-deposit.php'; 
	break;
	case 'invoice-pay':
	$title="Invoice payment";
	$content='invoice-payment.php'; 
	break;
	
	case 'client-stms':
	$title="Client Transaction";
	$content='Client-statementData.php'; 
	break;
	case 'statistics':
	$title="Recovery  Statistics";
	$content='statistics-statement.php'; 
	break;
	
	case 're-assign':
	$title="Remove Shops on Tenants";
	$content='../deposit/make-shop-shop.php'; 
	break;
	case 'svadv_inv':
	$title="Advanve invoice";
	$content='../deposit/new_advanceInvoice.php'; 
	break;
	case 'auto-invo':
	$title="Advanve invoice";
	$content='../deposit/generateAutoInvo.php'; 
	break;
	default :
	    $title="Home";
		$content ='dashboard.php';
     }

    require_once("../theme/navigation.php");
?>

