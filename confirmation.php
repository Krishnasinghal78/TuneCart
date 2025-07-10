<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

echo "<h2>Order Placed Successfully!</h2>";
echo "<p>Thank you for your purchase. Your order has been placed and is being processed.</p>";
echo "<a href='instrument-list.php'>Continue Shopping</a>";
?>
