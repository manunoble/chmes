<?php
session_start();
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // 1. Fetch the file path before deleting the record
        $stmt = $db->prepare("SELECT file_path FROM content_hub WHERE id = ?");
        $stmt->execute([$id]);
        $content = $stmt->fetch();

        if ($content) {
            $file_to_delete = "../uploads/content/" . $content['file_path'];

            // 2. Delete the file from the physical storage
            if (file_exists($file_to_delete)) {
                unlink($file_to_delete);
            }

            // 3. Delete the record from the database
            $deleteStmt = $db->prepare("DELETE FROM content_hub WHERE id = ?");
            $deleteStmt->execute([$id]);

            header("Location: ../views/content_hub.php?success=deleted");
            exit();
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: ../views/content_hub.php");
}