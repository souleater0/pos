<?php
// Database configuration
$dbHost = 'localhost'; // Change this to your database host
$dbName = 'tako_db'; // Change this to your database name
$dbUser = 'root'; // Change this to your database username
$dbPass = ''; // Change this to your database password

//HOSTING
// $dbHost = 'sql311.infinityfree.com'; // Change this to your database host
// $dbName = 'if0_38268102_tako_db'; // Change this to your database name
// $dbUser = 'if0_38268102'; // Change this to your database username
// $dbPass = '5VxvQPevj6Em'; // Change this to your database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optionally, set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Optionally, set character set to utf8
    $pdo->exec("SET NAMES 'utf8'");

    // echo "db connected";
} catch (PDOException $e) {
    // If connection fails, display error message
    die("Connection failed: " . $e->getMessage());
}
?>
