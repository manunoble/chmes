<?php
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type']; // tithe, offering, expense, donation
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $member_name = $_POST['member_name'] ?? null;
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO finances (type, amount, category, description, member_name, date, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    
    if ($stmt->execute([$type, $amount, $category, $description, $member_name, $date, $user_id])) {
        $_SESSION['success'] = "Transaction recorded successfully!";
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
    }

    header("Location: ../views/finance.php");
    exit();
}