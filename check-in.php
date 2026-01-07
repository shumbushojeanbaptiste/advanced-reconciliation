<?php
ob_start();
require_once "./include/config.php";

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Utility: generate random session code
function sessionCode($length = 8) {
    $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $pass = '';
    for ($i = 0; $i < $length; $i++) {
        $pass .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $pass;
}

$now_date = date('Y-m-d');
$scode = sessionCode();
$sess_id = md5($scode);

// Handle "Remember Me" cookies securely
if (!empty($_POST["login_remember_me"])) {
    setcookie("login_email", $_POST["login_email"], time() + 3600, "/", "", true, true);
    setcookie("login_password", $_POST["login_password"], time() + 3600, "/", "", true, true);
} else {
    setcookie("login-email", "", time() - 3600, "/");
    setcookie("login-password", "", time() - 3600, "/");
}

// Validate input


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST["login_email"] ?? '';
    $password = $_POST["login_password"] ?? '';

    try {
        // Fetch user
        $stmt = $db->prepare("SELECT * FROM tbl_users WHERE email = ? AND status = 1");
        $stmt->execute([$phone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            $dbPassword = $user['security_key']; // stored password

            $passwordVerified = false;

            // Check if the password is still MD5 (32 chars)
            if (strlen($dbPassword) === 32 && ctype_xdigit($dbPassword)) {
                // Verify MD5
                if (md5($password) === $dbPassword) {
                    $passwordVerified = true;

                    // Rehash using password_hash and update DB
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $update = $db->prepare("UPDATE tbl_users SET password = ? WHERE acc_id  = ?");
                    $update->execute([$newHash, $user['acc_id']]);
                }
            } else {
                // Assume password_hash
                if (password_verify($password, $dbPassword)) {
                    $passwordVerified = true;
                }
            }

            if ($passwordVerified) {
                // Generate token/session
                $token = random_int(10000, 99999);
                $updateToken = $db->prepare("UPDATE tbl_users SET remember_token = ? WHERE acc_id = ?");
                $updateToken->execute([$token, $user['acc_id']]);

             

                // Create session variables
                $_SESSION['sess_id'] = $sess_id;
                $_SESSION['role'] = $user['role_id'];
                $_SESSION['names'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['lname'] = $user['last_name'];
                $_SESSION['fname'] = $user['first_name'];
                $_SESSION['phone'] = $user['phone'];
                $_SESSION['USER_ID'] = $user['acc_id'];
                $_SESSION['side_value'] = $user['role_id'];

                echo json_encode([
                    'status' => 'success',
                    'token' => $token,
                    'phone' => $user['phone'],
                    'role' => $user['role_id'],
                    'side_value' => $user['role_id']
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
            }

        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found or inactive']);
        }

    } catch (PDOException $ex) {
        echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing credentials']);
}

ob_end_flush();
?>
