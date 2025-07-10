<?php
  // Ensure this is the correct path

$host = 'localhost';  // Database host
$dbname = 'tunecart';  // Database name
$username = 'root';    // Database username
$password = '';        // Database password

try {
    // Create a PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
