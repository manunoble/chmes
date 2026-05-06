<?php
// 1. Establish the absolute root path
$base_path = dirname(__DIR__);

// 2. Start session and check authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $base_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
require_once $base_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect Form Data
    $member_id      = $_POST['member_id'];
    $event_type     = $_POST['event_type'];
    $event_date     = $_POST['event_date'];
    $officiant_name = $_POST['officiant_name'];
    $location       = $_POST['location'];
    $witness_names  = $_POST['witness_names'];
    $notes          = $_POST['notes'];
    
    $certificate_name = null;

    // 3. Handle File Upload (Certificate)
    if (isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] === 0) {
        $target_dir = $base_path . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'certificates' . DIRECTORY_SEPARATOR;
        
        // Ensure directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_ext = pathinfo($_FILES["certificate_file"]["name"], PATHINFO_EXTENSION);
        $certificate_name = "CERT_" . time() . "_" . $member_id . "." . $file_ext;
        $target_file = $target_dir . $certificate_name;

        if (!move_uploaded_file($_FILES["certificate_file"]["tmp_name"], $target_file)) {
            $certificate_name = null; // Reset if upload fails
        }
    }

    try {
        // 4. Insert into Database
        $sql = "INSERT INTO pastoral_records 
                (member_id, event_type, event_date, officiant_name, witness_names, location, certificate_path, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $member_id, 
            $event_type, 
            $event_date, 
            $officiant_name, 
            $witness_names, 
            $location, 
            $certificate_name, 
            $notes
        ]);

        // 5. Log the Administrative Action
        $log_stmt = $db->prepare("INSERT INTO system_logs (username, action) VALUES (?, ?)");
        $log_stmt->execute([$_SESSION['full_name'], "Logged $event_type for Member ID: $member_id"]);

        header("Location: ../views/pastoral_records.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Critical Database Error: " . $e->getMessage());
    }
} else {
    header("Location: ../views/pastoral_records.php");
    exit();
}