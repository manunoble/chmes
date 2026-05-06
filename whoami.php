<?php
require_once 'includes/db.php';

try {
    // This SQL command asks MySQL: "Which database am I currently using?"
    $stmt = $db->query("SELECT DATABASE()");
    $current_db = $stmt->fetchColumn();

    echo "<h1>Database Connection Report</h1>";
    echo "Your PHP is currently connected to: <b style='color:blue;'>" . $current_db . "</b>";
} catch (Exception $e) {
    echo "Connection error: " . $e->getMessage();
}
?>