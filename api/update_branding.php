<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['church_name']);
    $final_logo_path = null;

    // 1. Check if a new file was uploaded
    if (isset($_FILES['church_logo_file']) && $_FILES['church_logo_file']['error'] === UPLOAD_ERR_OK) {
        
        // Fetch the OLD logo path from the database first to delete it later
        $oldFileQuery = $db->query("SELECT church_logo FROM system_settings WHERE id = 1");
        $oldFileData = $oldFileQuery->fetch();
        $oldFilePath = $oldFileData['church_logo'] ?? null;

        $fileTmpPath = $_FILES['church_logo_file']['tmp_name'];
        $fileName = $_FILES['church_logo_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Create unique name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = '../assets/uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        $allowedfileExtensions = array('jpg', 'png', 'jpeg', 'webp');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $final_logo_path = '../assets/uploads/' . $newFileName;

                // 2. CLEANUP: Delete the old file if it exists and isn't a default image
                if ($oldFilePath && file_exists($oldFilePath) && strpos($oldFilePath, 'default') === false) {
                    unlink($oldFilePath); 
                }
            }
        }
    }

    try {
        if ($final_logo_path) {
            $sql = "UPDATE system_settings SET church_name = ?, church_logo = ? WHERE id = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $final_logo_path]);
        } else {
            $sql = "UPDATE system_settings SET church_name = ? WHERE id = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name]);
        }

        header("Location: ../views/settings.php?tab=church&success=1");
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}