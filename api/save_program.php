<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $name = trim($_POST['name']);
    $mentor = trim($_POST['mentor']);
    $duration = trim($_POST['duration']);
    $color = $_POST['theme_color'];
    $participants = intval($_POST['participants']);

    try {
        // Prepare the SQL statement
        $sql = "INSERT INTO discipleship_programs 
                (name, mentor_name, duration, target_participants, theme_color, status) 
                VALUES (?, ?, ?, ?, ?, 'Active')";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$name, $mentor, $duration, $participants, $color]);

        // Success redirect
        header("Location: ../views/discipleship.php?success=program_created");
        exit();

    } catch (PDOException $e) {
        // Error handling for your IT project logs
        die("Database Error: " . $e->getMessage());
    }
}