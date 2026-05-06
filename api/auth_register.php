<?php
session_start();
// Make sure your db.php points to 'chmanagement' as we discussed!
require_once '../includes/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $pass      = $_POST['password'];
    $role      = $_POST['role'];

    // 1. Hash the password correctly
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    try {
        // 2. Prepare the SQL
        $sql = "INSERT INTO users (full_name, username, email, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        
        // 3. Execute
        $stmt->execute([$full_name, $username, $email, $hashed_password, $role]);

        $_SESSION['success'] = "Account created! You can now login.";
        header("Location: ../views/register.php");
        exit();

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Error code for duplicate entry
            $_SESSION['error'] = "Username or Email already exists.";
        } else {
            $_SESSION['error'] = "Database Error: " . $e->getMessage();
        }
        header("Location: ../views/register.php");
        exit();
    }
}