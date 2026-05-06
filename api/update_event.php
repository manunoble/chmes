<?php
session_start();
require_once dirname(__DIR__) . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int)$_POST['id'];
    $title = trim($_POST['title']);
    $type  = $_POST['event_type'];
    $date  = $_POST['event_date'];
    $time  = $_POST['event_time'];
    $loc   = trim($_POST['location']);
    $desc  = trim($_POST['description']);

    try {
        $sql = "UPDATE events SET 
                title = ?, event_type = ?, event_date = ?, 
                event_time = ?, location = ?, description = ? 
                WHERE id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$title, $type, $date, $time, $loc, $desc, $id]);

        header("Location: ../views/events.php?updated=1");
        exit();
    } catch (PDOException $e) {
        die("Update Error: " . $e->getMessage());
    }
}