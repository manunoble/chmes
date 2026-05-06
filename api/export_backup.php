<?php
// 1. Establish the absolute root path (Crucial for XAMPP environments)
$base_path = dirname(__DIR__);

// 2. Start session BEFORE any other logic
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Secure Pathing: Pull in the database connection
// This ensures $db is available and the script knows the root directory
require_once $base_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

// 4. Strict Security Check
// We check if the user is logged in AND is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    die("Unauthorized access: Administrative privileges required.");
}

/**
 * DATABASE BACKUP LOGIC (PDO Version)
 * Higher compatibility with your CHIMES system
 */
try {
    $tables = array();
    $result = $db->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $return = "-- CHIMES DATABASE BACKUP\n";
    $return .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $return .= "-- --------------------------------------------------\n\n";

    foreach ($tables as $table) {
        // Drop and Create table structure
        $return .= 'DROP TABLE IF EXISTS '.$table.';';
        $stmt = $db->query("SHOW CREATE TABLE $table");
        $row2 = $stmt->fetch(PDO::FETCH_NUM);
        $return .= "\n\n".$row2[1].";\n\n";

        // Fetch all data for this table
        $result = $db->query("SELECT * FROM $table");
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $return .= 'INSERT INTO '.$table.' VALUES(';
            foreach($row as $key => $value) {
                // Sanitize values for SQL insertion
                if (isset($value)) {
                    // Escape single quotes and handle nulls
                    $value = addslashes($value);
                    $value = str_replace("\n", "\\n", $value);
                    $return .= '"' . $value . '"';
                } else {
                    $return .= 'NULL';
                }
                
                if ($key < (count($row) - 1)) {
                    $return .= ',';
                }
            }
            $return .= ");\n";
        }
        $return .= "\n\n\n";
    }

    // 5. Force Download headers
    $filename = 'CHIMES_Backup_' . date('Y-m-d_H-i-s') . '.sql';
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . $filename . "\"");
    
    echo $return;
    exit;

} catch (PDOException $e) {
    die("Backup Error: " . $e->getMessage());
}