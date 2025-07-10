<?php
session_start();
include 'php/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch the latest order for this user
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_date DESC LIMIT 1");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Fetch the details of the latest order
        $stmt = $conn->prepare("
            SELECT od.*, i.name 
            FROM order_details od
            JOIN instruments i ON od.instrument_id = i.id
            WHERE od.order_id = :order_id
        ");
        $stmt->bindParam(':order_id', $order['id'], PDO::PARAM_INT);
        $stmt->execute();
        $order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $order_details = [];
    }
} catch (PDOException $e) {
    echo "Error fetching order details: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - TuneCart</title>
    <link rel="stylesheet" href="assets/dashboard.css">
</head>
<body>
<header>
    <h1>TuneCart</h1>
</header>
<main>
    <h2>Order Confirmation</h2>
    <?php if ($order && $order_details): ?>
        <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
        <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
        <h3>Order Details:</h3>
        <ul>
            <?php foreach ($order_details as $detail): ?>
                <li><?php echo htmlspecialchars($detail['name']); ?> - ₹<?php echo number_format($detail['price'], 2); ?></li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total Price:</strong> ₹<?php echo number_format(array_sum(array_column($order_details, 'price')), 2); ?></p>
    <?php else: ?>
        <p>No recent order found. Please check your order history or try again.</p>
    <?php endif; ?>
</main>
</body>
</html>
