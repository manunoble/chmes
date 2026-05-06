<?php
ob_start(); // Start output buffering
session_start();

// Your existing auth logic here...
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user session is not set
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}
?>