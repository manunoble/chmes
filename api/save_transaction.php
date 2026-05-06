<?php
session_start();
// Use the absolute path logic to ensure the DB connection is found
$db_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
require_once $db_path;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Get the value and trim whitespace
    $raw_type = isset($_POST['type']) ? trim($_POST['type']) : null;
    
    // 2. Format it to match your DB (e.g., Capitalizing the first letter)
    $type = ucfirst(strtolower($raw_type)); 

    if (empty($type)) {
        die("Error: Transaction type cannot be empty.");
    }
    
    // Proceed with your INSERT statement...
}

    try {
        // 3. Prepare the SQL Statement
        $sql = "INSERT INTO finances (type, description, amount, transaction_date) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$type, $desc, $amount, $date]);

        // 4. Success! Redirect back to the finance dashboard
        header("Location: ../views/finances.php?success=1");
        exit();

    } catch (PDOException $e) {
        // Helpful error for your IT project troubleshooting
        die("Database Error: " . $e->getMessage());
    }
} else {
    // Redirect if someone tries to access the script directly
    header("Location: ../views/finances.php");
    exit();
}