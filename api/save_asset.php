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
    $asset_name    = trim($_POST['asset_name']);
    $category      = $_POST['category'];
    $serial_number = trim($_POST['serial_number']);
    $purchase_date = !empty($_POST['purchase_date']) ? $_POST['purchase_date'] : null;
    $purchase_cost = !empty($_POST['purchase_cost']) ? $_POST['purchase_cost'] : 0.00;
    $assigned_to   = trim($_POST['assigned_to']);
    $status        = $_POST['status'];
    $notes         = trim($_POST['notes']);

    try {
        // 3. Insert into church_assets table
        $sql = "INSERT INTO church_assets 
                (asset_name, category, serial_number, purchase_date, purchase_cost, assigned_to, status, notes, last_audit_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_DATE)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $asset_name, 
            $category, 
            $serial_number, 
            $purchase_date, 
            $purchase_cost, 
            $assigned_to, 
            $status, 
            $notes
        ]);

        // 4. Update the System Audit Trail
        $log_stmt = $db->prepare("INSERT INTO system_logs (username, action) VALUES (?, ?)");
        $log_stmt->execute([$_SESSION['full_name'], "Added new asset to inventory: $asset_name"]);

        // 5. Redirect back to the inventory list
        header("Location: ../views/assets.php?success=asset_added");
        exit();

    } catch (PDOException $e) {
        die("Error adding asset to inventory: " . $e->getMessage());
    }
} else {
    header("Location: ../views/assets.php");
    exit();
}