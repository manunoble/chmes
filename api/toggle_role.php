<?php
session_start();
require_once '../includes/db.php';

// 1. Security Check: Only an existing Admin can change roles
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Access Denied: You do not have permission to manage roles.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_user_id = $_POST['user_id'];
    $current_role = $_POST['current_role'];

    // 2. Logic: If they are Admin, make them Staff. If they are Staff, make them Admin.
    $new_role = ($current_role === 'Admin') ? 'Staff' : 'Admin';

    try {
        // Prevent an Admin from accidentally demoting themselves (Safety First!)
        if ($target_user_id == $_SESSION['user_id']) {
            header("Location: ../views/settings.php?tab=roles&error=self_demote");
            exit;
        }

        $stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$new_role, $target_user_id]);

        // 3. Redirect back to the roles tab with a success message
        header("Location: ../views/settings.php?tab=roles&success=1");
    } catch (PDOException $e) {
        die("Critical Error: " . $e->getMessage());
    }
}