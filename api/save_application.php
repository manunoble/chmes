<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $ministry_id = (int)$_POST['ministry_id'];
    $skills = trim($_POST['skill_set']);

    try {
        // Check if application already exists
        $check = $db->prepare("SELECT id FROM volunteer_applications WHERE user_id = ? AND ministry_id = ? AND status = 'pending'");
        $check->execute([$user_id, $ministry_id]);

        if ($check->rowCount() > 0) {
            header("Location: ../views/ministries.php?error=already_applied");
            exit();
        }

        $sql = "INSERT INTO volunteer_applications (user_id, ministry_id, skill_set) VALUES (?, ?, ?)";
        $db->prepare($sql)->execute([$user_id, $ministry_id, $skills]);

        header("Location: ../views/ministries.php?success=application_sent");
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}