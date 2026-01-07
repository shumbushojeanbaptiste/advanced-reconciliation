<?php
require_once("../include/initialize.php");

// ==============================
// INSERT ROOM
// ==============================
if (isset($_POST['insert'])) {
    try {
        $auto_code = $_POST['auto_code'];
        $cpid = $_POST['cpid'];
        $floor = $_POST['floor'];
        $rm_code = trim($_POST['rm_code']);
        $price = $_POST['price'];
        $descr = $_POST['descr'];
        $room_area = $_POST['room_area'];

        $sql = $db->prepare("
            INSERT INTO tbl_rooms 
            (room_no, price, descr, room_area, company_id, r_floor, rm_status, auto_code)
            VALUES (:rm_code, :price, :descr, :room_area, :cpid, :floor, 1, :auto_code)
        ");

        $sql->execute([
            ':rm_code' => $rm_code,
            ':price' => $price,
            ':descr' => $descr,
            ':room_area' => $room_area,
            ':cpid' => $cpid,
            ':floor' => $floor,
            ':auto_code' => $auto_code
        ]);

        $msg = "Room with Number: <b>" . $rm_code . "</b> has been registered successfully!";
    } catch (PDOException $e) {
        echo "Error inserting room: " . $e->getMessage();
    }
}

// ==============================
// UPDATE ROOM
// ==============================
if (isset($_POST['update'])) {
    try {
        $rmid = $_POST['rid'];
        $no = $_POST['room_no'];
        $price = $_POST['price'];
        $descr = $_POST['descr'];
        $area = $_POST['area'];

        $sql = $db->prepare("
            UPDATE tbl_rooms 
            SET room_no = :no, price = :price, descr = :descr, room_area = :area
            WHERE room_id = :rmid
        ");
        $sql->execute([
            ':no' => $no,
            ':price' => $price,
            ':descr' => $descr,
            ':area' => $area,
            ':rmid' => $rmid
        ]);

        $sql1 = $db->prepare("
            UPDATE tbl_assign 
            SET amount = :price 
            WHERE room_id = :rmid
        ");
        $sql1->execute([':price' => $price, ':rmid' => $rmid]);

        $msg = "Room with Number: <b>" . $no . "</b> has been modified successfully!";
    } catch (PDOException $e) {
        echo "Error updating room: " . $e->getMessage();
    }
}

// ==============================
// DELETE ROOM
// ==============================
if (isset($_GET['delete_room'])) {
    try {
        $stmt = $db->prepare("DELETE FROM tbl_rooms WHERE room_id = :rid");
        $stmt->execute([':rid' => $_GET['delete_room']]);
        $msge = "Room <b>" . htmlspecialchars($_GET['room_no']) . "</b> is successfully removed";
        echo '<meta http-equiv="refresh" content="2;URL=?file=rooms">';
    } catch (PDOException $e) {
        echo "Error deleting room: " . $e->getMessage();
    }
}

// ==============================
// ACTIVATE / DEACTIVATE ROOM
// ==============================
if (isset($_GET['acid'])) { // deactivate
    try {
        $sql = $db->prepare("UPDATE tbl_rooms SET rm_status = 2 WHERE room_id = :rid");
        $sql->execute([':rid' => $_GET['acid']]);
        $msge = "Room deactivated successfully";
        echo '<meta http-equiv="refresh" content="2;URL=?file=rooms">';
    } catch (PDOException $e) {
        echo "Error deactivating room: " . $e->getMessage();
    }
} elseif (isset($_GET['did'])) { // activate
    try {
        $sql = $db->prepare("UPDATE tbl_rooms SET rm_status = 1 WHERE room_id = :rid");
        $sql->execute([':rid' => $_GET['did']]);
        $msg = "Room activated successfully";
        echo '<meta http-equiv="refresh" content="2;URL=?file=rooms">';
    } catch (PDOException $e) {
        echo "Error activating room: " . $e->getMessage();
    }
}
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <?php if (!empty($msg)) { ?>
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Well done!</strong> <?php echo $msg; ?>
                </div>
            <?php } elseif (!empty($msge)) { ?>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Oh snap!</strong> <?php echo $msge; ?>
                </div>
            <?php } ?>

            <div class="row align-items-center mb-2">
                <div class="col">
                    <h2 class="h5 page-title">All Rooms</h2>
                </div>
                <div class="col-auto"></div>
            </div>

            <div class="row my-4">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <table class="table datatables" style="font-size: 11px;" id="dataTable-1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Room No.</th>
                                        <th>Client(s)</th>
                                        <th></th>
                                        <th>Area(Sqm.)</th>
                                        <th>Room Status</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
$i = 0;
$tot_price = 0;

// Fetch all rooms for the company
$sql_rooms = $db->prepare("
    SELECT * FROM tbl_rooms 
    WHERE company_id = :company AND availability = 4
    ORDER BY room_no ASC
");
$sql_rooms->execute([':company' => $company]);

while ($room = $sql_rooms->fetch(PDO::FETCH_ASSOC)) {
    $i++;
    $tot_price += $room['price'];
    $rm_id = $room['room_id'];

    // Fetch assigned tenants for this room
    $sql_tenants = $db->prepare("
        SELECT t.te_names, t.ten_id 
        FROM tbl_assign a
        JOIN tbl_tenants t ON a.tent_id = t.ten_id
        WHERE a.room_id = :rmid AND a.assgn_status = 1
    ");
    $sql_tenants->execute([':rmid' => $rm_id]);
    $tenants = $sql_tenants->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo htmlspecialchars($room['room_no']); ?></td>
        <td>
            <?php 
            if ($tenants) {
                foreach ($tenants as $t) {
                    echo "<b>" . htmlspecialchars($t['te_names']) . "</b><br>";
                }
            } else {
                echo "-";
            }
            ?>
        </td>
        <td>
            <?php
            if (count($tenants) > 1) {
                foreach ($tenants as $t) {
                    echo '<a href="" data-toggle="modal" data-target="#viewRoom' . $t['ten_id'] . '" class="edit_data"><i class="fe fe-eye"></i></a> ';
                }
            }
            ?>
        </td>
        <td><?php echo $room['room_area']; ?> m<sup>2</sup></td>
        <td>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="flexSwitch<?php echo $rm_id; ?>" 
                       <?php echo $room['rm_status'] == 1 ? "checked" : ""; ?> >
                <label class="custom-control-label" for="flexSwitch<?php echo $rm_id; ?>"></label>
            </div>
        </td>
        <td><?php echo number_format($room['price'], 4, '.', ' '); ?></td>
        <td class="cell-icon text-center">
            <a href="" data-toggle="modal" data-target="#update<?php echo $rm_id; ?>" class="edit_data"><i class="fe fe-edit"></i></a>
        </td>
    </tr>
    <?php include 'room_update_modal.php'; ?>
<?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total.</td>
                                        <td colspan="5"></td>
                                        <td align="center"><?php echo number_format($tot_price, 2); ?></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 
    </div> 
</div>
