<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = $_POST['program_id'];
    $progress = intval($_POST['new_progress']);
    $status = $_POST['status'];

    try {
        $stmt = $db->prepare("UPDATE discipleship_programs SET current_progress = ?, status = ? WHERE id = ?");
        $stmt->execute([$progress, $status, $pid]);

        header("Location: ../views/discipleship.php?success=updated");
        exit();
    } catch (PDOException $e) {
        die("Error updating progress: " . $e->getMessage());
    }
}