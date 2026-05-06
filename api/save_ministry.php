<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $lead = !empty($_POST['lead_id']) ? $_POST['lead_id'] : null;
    $assistant = !empty($_POST['assistant_id']) ? $_POST['assistant_id'] : null;
    $schedule = trim($_POST['meeting_schedule']);
    $resp = trim($_POST['responsibilities']);
    $desc = trim($_POST['description']);

    // List of gradients to randomly assign to new ministries
    $gradients = [
        'linear-gradient(to right, #8b5cf6, #7c3aed)',
        'linear-gradient(to right, #10b981, #059669)',
        'linear-gradient(to right, #f59e0b, #d97706)',
        'linear-gradient(to right, #06b6d4, #2563eb)'
    ];
    $randomGradient = $gradients[array_rand($gradients)];

    try {
        $sql = "INSERT INTO ministries (name, lead_id, assistant_id, meeting_schedule, responsibilities, description, color_gradient) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$name, $lead, $assistant, $schedule, $resp, $desc, $randomGradient]);

        header("Location: ../views/ministries.php?success=ministry_created");
    } catch (PDOException $e) {
        die("Error creating ministry: " . $e->getMessage());
    }
}