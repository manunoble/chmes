<?php
session_start();
// Absolute path to DB connection
$db_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
require_once $db_path;

// 1. Check if user is logged in (Security)
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

// 2. Get the ID from the URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // 3. Prepare and Execute Delete
        $stmt = $db->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$id]);

        // 4. Redirect back with a success message
        header("Location: ../views/events.php?deleted=1");
        exit();

    } catch (PDOException $e) {
        die("Error deleting event: " . $e->getMessage());
    }
} else {
    header("Location: ../views/events.php");
    exit();
}