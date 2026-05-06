<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $speaker = $_POST['speaker'];
    $type = $_POST['content_type'];
    $p_date = $_POST['published_date'];

    // 1. Handle File Upload
    $target_dir = "../uploads/content/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($_FILES["media_file"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["media_file"]["tmp_name"], $target_file)) {
        try {
            // 2. Save Path to Database
            $stmt = $db->prepare("INSERT INTO content_hub (title, speaker, content_type, published_date, file_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $speaker, $type, $p_date, $file_name]);

            header("Location: ../views/content_hub.php?success=uploaded");
            exit();
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    } else {
        die("Sorry, there was an error uploading your file.");
    }
}