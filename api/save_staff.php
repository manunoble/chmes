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
    // Collect and sanitize Form Data
    $full_name    = trim($_POST['full_name']);
    $username     = trim($_POST['username']);
    $plain_pass   = $_POST['password'];
    $role         = $_POST['role'];
    $department   = $_POST['department'];
    $phone        = trim($_POST['phone']);
    $joined_date  = $_POST['joined_date'];

    try {
        // 3. Check if Username already exists
        $check_stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $check_stmt->execute([$username]);
        
        if ($check_stmt->rowCount() > 0) {
            header("Location: ../views/add_staff.php?error=username_taken");
            exit();
        }

        // 4. Secure Password Hashing (BCrypt)
        $hashed_password = password_hash($plain_pass, PASSWORD_BCRYPT);

        // 5. Insert into Database
        $sql = "INSERT INTO users 
                (full_name, username, password, role, department, phone, joined_date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Active')";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $full_name, 
            $username, 
            $hashed_password, 
            $role, 
            $department, 
            $phone, 
            $joined_date
        ]);

        // 6. Audit Trail: Log the action
        $log_stmt = $db->prepare("INSERT INTO system_logs (username, action) VALUES (?, ?)");
        $log_stmt->execute([$_SESSION['full_name'], "Created new staff account for: $full_name"]);

        header("Location: ../views/staff_directory.php?success=account_created");
        exit();

    } catch (PDOException $e) {
        die("Error creating staff account: " . $e->getMessage());
    }
} else {
    header("Location: ../views/staff_directory.php");
    exit();
}