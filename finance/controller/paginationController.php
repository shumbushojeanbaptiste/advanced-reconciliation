<?php 
require_once("../../include/initialize.php");

function incomeTypeList(){
    global $db;

    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 50;
    $page  = isset($_POST['page'])  ? intval($_POST['page'])  : 1;
    $offset = ($page - 1) * $limit;

    // Count total rows
    $countStmt = $db->prepare("SELECT COUNT(*) as total FROM tbl_income_type");
    $countStmt->execute();
    $totalRows = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Fetch batch from income types
    $stmt = $db->prepare("SELECT income_type_id, income_name, description
                          FROM tbl_income_type
                          LIMIT :offset, :limit");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate progress, safeguard against division by zero
    if ($totalRows > 0) {
        $progress = min(100, round((($offset + count($rows)) / $totalRows) * 100));
    } else {
        $progress = 100;
    }

    // Return JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'data' => $rows,
        'progress' => $progress,
        'total' => $totalRows
    ]);
}

// Handle AJAX
$action = $_POST['action'] ?? '';
switch($action){
    case 'income-type-list':
        incomeTypeList();
        break;
    default:
        echo json_encode(['data'=>[], 'progress'=>0, 'total'=>0]);
        break;
}
?>
