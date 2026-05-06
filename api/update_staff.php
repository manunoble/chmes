<?php
// 1. Establish the absolute root path
$base_path = dirname(__DIR__);

// 2. Start session and check authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $base_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
require_once $base_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect Form Data
    $id           = intval($_POST['id']);
    $full_name    = trim($_POST['full_name']);
    $role         = $_POST['role'];
    $department   = $_POST['department'];
    $phone        = trim($_POST['phone']);
    $status       = $_POST['status'];
    $new_password = $_POST['new_password'];

    try {
        // 3. Base Update Query (Fields that are always updated)
        $sql = "UPDATE users SET 
                full_name = ?, 
                role = ?, 
                department = ?, 
                phone = ?, 
                status = ? 
                WHERE id = ?";
        
        $params = [$full_name, $role, $department, $phone, $status, $id];

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        // 4. Conditional Password Update
        if (!empty($new_password)) {
            // Hash the new password before saving
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            
            $pass_stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $pass_stmt->execute([$hashed_password, $id]);
        }

        // 5. Log the Administrative Action
        $log_stmt = $db->prepare("INSERT INTO system_logs (username, action) VALUES (?, ?)");
        $log_stmt->execute([$_SESSION['full_name'], "Updated staff profile for: $full_name (ID: $id)"]);

        header("Location: ../views/staff_directory.php?success=updated");
        exit();

    } catch (PDOException $e) {
        die("Critical Database Error: " . $e->getMessage());
    }
} else {
    header("Location: ../views/staff_directory.php");
    exit();
}