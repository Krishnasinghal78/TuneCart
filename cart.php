<?php
session_start();
include 'php/db.php';

// Ensure the cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$total = 0;

// Calculate the total price
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - TuneCart</title>
    <link rel="stylesheet" href="assets/dashboard.css">
    <style>
        body {
            background-color: #1a1a2e;
            color: #ffffff;
        }
        .cart-container {
            margin: 50px auto;
            width: 80%;
            max-width: 800px;
            background-color: #282846;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
        }
        .cart-container h1 {
            color: #ffdd83;
            text-align: center;
            margin-bottom: 20px;
        }
        .cart-container table {
            width: 100%;
            color: #ffffff;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .cart-container table th, .cart-container table td {
            padding: 10px;
            border: 1px solid #ffffff;
            text-align: left;
        }
        .cart-container .total {
            text-align: right;
            font-size: 18px;
            color: #ffdd83;
        }
        .cart-container .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #e0aaff;
        }
        .cart-container a {
            display: inline-block;
            background-color: #ff497c;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .cart-container a:hover {
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
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="cart-container">
        <h1>Your Cart</h1>

        <?php if (count($cart) === 0): ?>
            <p class="empty-cart">Your cart is currently empty. Add some instruments!</p>
        <?php else: ?>
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
        <?php endif; ?>

        <form action="checkout.php" method="POST">
    <button type="submit" class="checkout-btn" style="background: none; border: none; color: blue; text-decoration: underline; cursor: pointer;">
        Proceed to Checkout
    </button>
</form>

    </div>
</main>
</body>
</html>
