<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['member_name'];
    $service = $_POST['service_type'];
    $date = $_POST['service_date'];

    try {
        $stmt = $db->prepare("INSERT INTO attendance (member_name, service_type, service_date, status) VALUES (?, ?, ?, 'Present')");
        $stmt->execute([$name, $service, $date]);

        // Redirect back to main attendance page
        header("Location: ../views/attendance.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Error logging attendance: " . $e->getMessage());
    }
}