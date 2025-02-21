<?php
date_default_timezone_set('Asia/Manila');
// Database configuration
$dbHost = 'localhost'; // Change this to your database host
$dbName = 'tako_db'; // Change this to your database name
$dbUser = 'root'; // Change this to your database username
$dbPass = ''; // Change this to your database password

//HOSTING
// $dbHost = 'localhost'; // Change this to your database host
// $dbName = 'u426435337_takodb'; // Change this to your database name
// $dbUser = 'u426435337_tako'; // Change this to your database username
// $dbPass = '@Takoyame2025'; // Change this to your database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optionally, set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Optionally, set character set to utf8
    $pdo->exec("SET NAMES 'utf8'");

    // Set MySQL timezone to UTC+8 (for Philippines)
    $pdo->exec("SET time_zone = '+08:00'");

    // echo "db connected";
} catch (PDOException $e) {
    // If connection fails, display error message
    die("Connection failed: " . $e->getMessage());
}
?>
