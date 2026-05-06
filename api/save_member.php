<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $status = $_POST['status'];
    $date = $_POST['membership_date'];

    try {
        $stmt = $db->prepare("INSERT INTO members (full_name, email, phone, status, membership_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $status, $date]);

        // Smart Redirect: Take them back to marking attendance
        header("Location: ../views/mark_attendance.php?success=member_added");
        exit();
    } catch (PDOException $e) {
        die("Error adding member: " . $e->getMessage());
    }
}