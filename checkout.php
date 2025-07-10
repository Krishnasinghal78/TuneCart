<?php
session_start();
include 'php/db.php';

// Ensure the cart exists
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo "<script>alert('Your cart is empty. Please add items to proceed.'); window.location.href = 'cart.php';</script>";
    exit;
}

// Get cart details
$cart = $_SESSION['cart'];

// Calculate total amount
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Process checkout (example: saving order data to database)
// You can customize this based on your business logic.
if (isset($_POST['confirm_checkout'])) {
    // Example: Save the order to the database
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (:user_id, :total_amount)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':total_amount', $total);
    $stmt->execute();
    $order_id = $conn->lastInsertId();

    // Save order details
    foreach ($cart as $item) {
        $stmt = $conn->prepare("INSERT INTO order_details (order_id, instrument_id, quantity, price) VALUES (:order_id, :instrument_id, :quantity, :price)");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':instrument_id', $item['id']);
        $stmt->bindParam(':quantity', $item['quantity']);
        $stmt->bindParam(':price', $item['price']);
        $stmt->execute();
    }

    // Clear the cart
    unset($_SESSION['cart']);

    echo "<script>alert('Order successfully placed!'); window.location.href = 'instruments.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TuneCart</title>
    <link rel="stylesheet" href="assets/dashboard.css">
    <style>
        body {
            background-color: #1a1a2e;
            color: #ffffff;
        }
        .checkout-container {
            margin: 50px auto;
            width: 80%;
            max-width: 800px;
            background-color: #282846;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
        }
        .checkout-container h1 {
            color: #ffdd83;
            text-align: center;
            margin-bottom: 20px;
        }
        .checkout-container table {
            width: 100%;
            color: #ffffff;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .checkout-container table th, .checkout-container table td {
            padding: 10px;
            border: 1px solid #ffffff;
            text-align: left;
        }
        .checkout-container .total {
            text-align: right;
            font-size: 18px;
            color: #ffdd83;
        }
        .checkout-container button {
            background-color: #ff497c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            width: 100%;
        }
        .checkout-container button:hover {
            background-color: #e43f65;
        }
    </style>
</head>
<body>
<header>
    <div class="navbar">
        <h1>TuneCart</h1>
        <nav>
            <ul>
                <li><a href="instruments.php">Back to Instruments</a></li>
                <li><a href="cart.php">Back to Cart</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="checkout-container">
        <h1>Checkout</h1>

        <table>
            <thead>
                <tr>
                    <th>Instrument</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">
            <strong>Total: ₹<?php echo number_format($total, 2); ?></strong>
        </div>

        <form method="POST" action="">
            <button type="submit" name="confirm_checkout">Confirm Checkout</button>
        </form>
    </div>
</main>
</body>
</html>
