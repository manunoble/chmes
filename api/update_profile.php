<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_SESSION['user_id'];
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    try {
        // 1. Basic Profile Update
        if (!empty($new_pass)) {
            // Check if passwords match
            if ($new_pass !== $confirm_pass) {
                header("Location: ../views/settings.php?tab=profile&error=pw_mismatch");
                exit;
            }
            
            // Hash the password for security
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET full_name = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $email, $hashed, $uid]);
        } else {
            // Update without changing password
            $sql = "UPDATE users SET full_name = ?, email = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $email, $uid]);
        }

        // 2. Update the session name so the header updates immediately
        $_SESSION['full_name'] = $name;

        header("Location: ../views/settings.php?tab=profile&success=profile_updated");
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}