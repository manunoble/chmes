<?php
session_start();
require_once '../includes/db.php';

if (isset($_GET['member_id']) && isset($_GET['program_id'])) {
    $mid = $_GET['member_id'];
    $pid = $_GET['program_id'];

    try {
        $db->beginTransaction();

        // 1. Delete the enrollment record
        $stmt = $db->prepare("DELETE FROM discipleship_enrollments WHERE member_id = ? AND program_id = ?");
        $stmt->execute([$mid, $pid]);

        // 2. Decrement the participant count in the programs table
        $updateStmt = $db->prepare("UPDATE discipleship_programs SET target_participants = target_participants - 1 WHERE id = ? AND target_participants > 0");
        $updateStmt->execute([$pid]);

        $db->commit();
        header("Location: ../views/view_participants.php?id=$pid&success=removed");
        exit();
    } catch (PDOException $e) {
        $db->rollBack();
        die("Error: " . $e->getMessage());
    }
}