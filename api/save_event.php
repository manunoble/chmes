<?php
session_start();
// Use the absolute path logic we perfected earlier
$db_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
require_once $db_path;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize data
    $title = trim($_POST['title']);
    $type  = $_POST['event_type'];
    $date  = $_POST['event_date'];
    $time  = $_POST['event_time'];
    $loc   = trim($_POST['location']);
    $desc  = trim($_POST['description']);
    $expected = (int)$_POST['expected_attendees'];

    try {
        $sql = "INSERT INTO events (title, event_type, event_date, event_time, location, description, expected_attendees) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$title, $type, $date, $time, $loc, $desc, $expected]);

        // Success! Redirect to the events list
        header("Location: ../views/events.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Error saving event: " . $e->getMessage());
    }
} else {
    header("Location: ../views/events.php");
    exit();
}