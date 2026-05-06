<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Robust path to the database connection
$db_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

if (file_exists($db_path)) {
    require_once $db_path;
} else {
    die("File not found: " . $db_path);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userInput = trim($_POST['username']);
    $passInput = $_POST['password'];

    try {
        // We select the EXACT column name 'password'
        $stmt = $db->prepare("SELECT id, username, password, full_name, role FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$userInput]);
        $userData = $stmt->fetch();

        if ($userData && password_verify($passInput, $userData['password'])) {
            // SUCCESS
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['full_name'] = $userData['full_name'];
            $_SESSION['role'] = $userData['role'];
            
            header("Location: ../views/dashboard.php");
            exit();
        } else {
            // INVALID CREDENTIALS
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: ../views/login.php");
            exit();
        }
    } catch (PDOException $e) {
        // THIS IS WHERE YOUR ERROR IS CAUGHT
        die("Database Error: " . $e->getMessage());
    }
}