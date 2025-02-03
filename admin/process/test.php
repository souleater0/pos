<?php
require_once '../../config.php';

$username = "admin";
$password = "admin";

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL query to insert a new user
$sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

// Prepare the SQL statement
$stmt = $pdo->prepare($sql);

// Bind parameters
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashed_password);

// Execute the statement
$result = $stmt->execute();

if ($result) {
    echo "User inserted successfully.";
} else {
    echo "Failed to insert user.";
}
?>
