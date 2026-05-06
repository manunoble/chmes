<?php
// Database Credentials
$host     = 'localhost';
$db_name  = 'chmanagement';
$username = 'root'; // Default for XAMPP/WAMP
$password = '';     // Default for XAMPP/WAMP is empty

try {
    // Create a new PDO instance
    $dsn = "mysql:host=$host;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throws errors as exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Returns data as associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements
    ];

    $db = new PDO($dsn, $username, $password, $options);

} catch (PDOException $e) {
    // If connection fails, stop the script and show the error
    die("Database Connection Failed: " . $e->getMessage());
}
?>