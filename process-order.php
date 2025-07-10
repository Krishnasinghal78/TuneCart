<?php
session_start();
include 'php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch data from POST request
    $name = $_POST['name'] ?? null;
    $address = $_POST['address'] ?? null;
    $payment_method = $_POST['payment_method'] ?? null;
    $instrument_id = $_POST['instrument_id'] ?? null;
    $price = $_POST['price'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    // Validate input
    if (!$name || !$address || !$payment_method || !$instrument_id || !$price || !$user_id) {
        die('All fields are required!');
    }

    // Insert order into database
    try {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, total_amount, address, payment_method) 
                                VALUES (:user_id, NOW(), :total_amount, :address, :payment_method)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':total_amount', $price);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->execute();

        echo "Order placed successfully!";
    } catch (PDOException $e) {
        echo "Error processing order: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
}
?>
