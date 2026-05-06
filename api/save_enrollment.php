<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mid = $_POST['member_id'];
    $pid = $_POST['program_id'];

    try {
        $db->beginTransaction();

        // 1. Insert into enrollments
        $stmt = $db->prepare("INSERT INTO discipleship_enrollments (member_id, program_id) VALUES (?, ?)");
        $stmt->execute([$mid, $pid]);

        // 2. Automatically update the participant count in the programs table
        $updateStmt = $db->prepare("UPDATE discipleship_programs SET target_participants = target_participants + 1 WHERE id = ?");
        $updateStmt->execute([$pid]);

        $db->commit();
        header("Location: ../views/discipleship.php?success=enrolled");
        exit();
    } catch (PDOException $e) {
        $db->rollBack();
        // If they are already enrolled, the UNIQUE KEY will trigger this
        header("Location: ../views/discipleship.php?error=already_enrolled");
    }
}