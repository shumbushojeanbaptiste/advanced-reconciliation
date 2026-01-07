<?php 
require_once("../../include/initialize.php");

$action = $_POST['action'] ?? '';
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$limit = 10; // rows per batch
$offset = ($page - 1) * $limit;

switch($action) {
    case 'income-type-list':
        incomeTypeList($page, $limit, $offset);
        break;
    default:
        echo 'Invalid action';
        break;
}

function incomeTypeList($page, $limit, $offset) {
    global $db;

    // Count total rows
    $countStmt = $db->prepare("SELECT COUNT(*) as total FROM tbl_clients");
    $countStmt->execute();
    $totalRows = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Fetch batch
    $stmt = $db->prepare("SELECT client_code as income_type_id, f_name as income_name, phone_number as description 
                          FROM tbl_clients 
                          LIMIT :offset, :limit");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON for frontend
    echo json_encode([
        'data' => $rows,
        'progress' => min(100, round(($offset + count($rows)) / $totalRows * 100)),
        'total' => $totalRows
    ]);
}
?>
