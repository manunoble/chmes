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
    $policy_title   = trim($_POST['policy_title']);
    $category       = $_POST['category'];
    $version_number = trim($_POST['version_number']);
    $uploaded_by    = $_SESSION['full_name'];
    
    $file_name = null;

    // 3. Handle File Upload (Strictly PDF)
    if (isset($_FILES['policy_file']) && $_FILES['policy_file']['error'] === 0) {
        $target_dir = $base_path . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'policies' . DIRECTORY_SEPARATOR;
        
        // Ensure directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($_FILES["policy_file"]["name"], PATHINFO_EXTENSION));
        
        // Security check: Only allow PDFs for official policies
        if ($file_ext !== 'pdf') {
            header("Location: ../views/upload_policy.php?error=invalid_format");
            exit();
        }

        // Generate a clean, readable filename
        $clean_title = preg_replace('/[^A-Za-z0-9\-]/', '_', $policy_title);
        $file_name = "POLICY_" . $clean_title . "_v" . $version_number . "_" . time() . ".pdf";
        $target_file = $target_dir . $file_name;

        if (!move_uploaded_file($_FILES["policy_file"]["tmp_name"], $target_file)) {
            $file_name = null;
        }
    }

    try {
        if (!$file_name) {
            die("Error: File upload failed. Please ensure you selected a valid PDF.");
        }

        // 4. Insert into Database
        $sql = "INSERT INTO church_policies 
                (policy_title, category, version_number, file_path, uploaded_by) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $policy_title, 
            $category, 
            $version_number, 
            $file_name, 
            $uploaded_by
        ]);

        // 5. Audit Trail
        $log_stmt = $db->prepare("INSERT INTO system_logs (username, action) VALUES (?, ?)");
        $log_stmt->execute([$uploaded_by, "Published Policy: $policy_title (v$version_number)"]);

        header("Location: ../views/church_policies.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
} else {
    header("Location: ../views/church_policies.php");
    exit();
}