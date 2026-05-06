<?php
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'create') {
        $name = $_POST['member_name'];
        $type = $_POST['case_type'];
        $priority = $_POST['priority'];
        $desc = $_POST['description'];
        $date = $_POST['date_reported'];

        $stmt = $db->prepare("INSERT INTO welfare_cases (member_name, case_type, priority, description, date_reported) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $type, $priority, $desc, $date]);
        $_SESSION['success'] = "Welfare case opened.";

    } elseif ($action === 'update_status') {
        $case_id = $_POST['case_id'];
        $status = $_POST['status'];

        $stmt = $db->prepare("UPDATE welfare_cases SET status = ? WHERE id = ?");
        $stmt->execute([$status, $case_id]);
        $_SESSION['success'] = "Case status updated.";
    }

    header("Location: ../views/welfare.php");
    exit();
}