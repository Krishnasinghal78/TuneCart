<?php
session_start();
include 'php/db.php';

// Fetch the instrument details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM instruments WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $instrument = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$instrument) {
        echo "Instrument not found!";
        exit;
    }
} else {
    echo "No instrument selected!";
    exit;
}

// Add detailed information about each instrument
$additional_details = [
    'Acoustic Guitar' => [
        'history' => 'The acoustic guitar has roots tracing back to the 16th century and remains a staple in music genres like folk, classical, and pop.',
        'features' => 'Rich tone, durable wooden body, and suitable for beginners and professionals.',
        'uses' => 'Perfect for strumming chords, fingerpicking, and composing music.',
    ],
    'Electric Guitar' => [
        'history' => 'Popularized in the mid-20th century, the electric guitar transformed music, paving the way for genres like rock, metal, and jazz.',
        'features' => 'Equipped with pickups and controls for versatile sound adjustments.',
        'uses' => 'Best suited for solos, riffs, and amplified performances.',
    ],
    'Violin' => [
        'history' => 'Dating back to the 16th century, the violin is a key instrument in classical, folk, and contemporary music.',
        'features' => 'Handcrafted body, smooth bowing experience, and precise tuning.',
        'uses' => 'Ideal for orchestras, solo performances, and folk tunes.',
    ],
    'Keyboard' => [
        'history' => 'Modern keyboards evolved from traditional pianos, offering electronic sound versatility.',
        'features' => 'Lightweight, portable, with multiple sound modes and rhythms.',
        'uses' => 'Great for learning, composing, and stage performances.',
    ],
    'Drum Set' => [
        'history' => 'Drum sets became central to jazz bands in the 1920s and are now essential in almost every genre.',
        'features' => 'Includes bass drum, snare, toms, cymbals, and hi-hats.',
        'uses' => 'Perfect for rhythm, beats, and energetic performances.',
    ],
    'Flute' => [
        'history' => 'One of the oldest instruments, dating back to ancient civilizations.',
        'features' => 'Lightweight bamboo body producing melodious tones.',
        'uses' => 'Ideal for classical, folk, and meditative music.',
    ],
    'Saxophone' => [
        'history' => 'Invented in the 1840s, the saxophone became synonymous with jazz and big band music.',
        'features' => 'Brass body with smooth keys for fluid play.',
        'uses' => 'Great for jazz, blues, and pop performances.',
    ],
    'Ukulele' => [
        'history' => 'Originating in Hawaii, the ukulele is a cheerful, compact instrument.',
        'features' => 'Small, lightweight body with nylon strings.',
        'uses' => 'Perfect for casual strumming and beach tunes.',
    ],
    'Tabla' => [
        'history' => 'A traditional Indian percussion instrument with roots in classical music.',
        'features' => 'Handcrafted drums with tuned heads for clear sound.',
        'uses' => 'Ideal for Indian classical, folk, and fusion music.',
    ],
    'Harmonium' => [
        'history' => 'Introduced to India in the 19th century, the harmonium became integral to classical and devotional music.',
        'features' => 'Portable design with responsive bellows and keys.',
        'uses' => 'Great for Indian classical, devotional, and fusion performances.',
    ],
];

$detailed_info = $additional_details[$instrument['name']] ?? null;

// Handle the Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $instrument_id = $_POST['instrument_id'];
    $instrument_name = $_POST['instrument_name'];
    $price = $_POST['price'];

    // Initialize cart if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item already exists in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $instrument_id) {
            $item['quantity'] += 1; // Increase the quantity if item already exists
            $found = true;
            break;
        }
    }

    // If not found, add it to the cart as a new item
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $instrument_id,
            'name' => $instrument_name,
            'price' => $price,
            'quantity' => 1
        ];
    }

    // Ensure the session cart is properly updated
    $_SESSION['cart'] = $_SESSION['cart'];  // Force session update
    echo "<script>alert('Instrument added to cart!');</script>";
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($instrument['name']); ?> - TuneCart</title>
    <link rel="stylesheet" href="assets/dashboard.css">
    <style>
        body {
            background-color: #1a1a2e;
            color: #ffffff;
        }
        .instrument-detail {
            text-align: center;
            color: #ffffff;
            margin: 30px auto;
            width: 80%;
            max-width: 800px;
        }
        .instrument-detail img {
            width: 300px;
            height: auto;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
        }
        .instrument-detail h2 {
            color: #e0aaff;
            margin-bottom: 10px;
        }
        .instrument-detail h3 {
            color: #ffdd83;
        }
        .instrument-detail p {
            font-size: 16px;
            line-height: 1.6;
            color: #bdbdbd;
            text-align: justify;
        }
        .instrument-detail strong {
            color: #ffdd83;
            font-size: 18px;
        }
        .instrument-detail button {
            background-color: #ff497c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .instrument-detail button:hover {
            background-color: #e43f65;
        }
        .instrument-detail a {
            text-decoration: none;
            color: white;
            margin: 10px;
            background-color: #ff497c;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .instrument-detail a:hover {
            background-color: #e43f65;
        }

        .cart-container {
            margin-top: 50px;
            padding: 20px;
            background-color: #282846;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
        }

        .cart-container h3 {
            color: #ffdd83;
            text-align: center;
            margin-bottom: 20px;
        }

        .cart-container table {
            width: 100%;
            color: #ffffff;
            border-collapse: collapse;
            text-align: left;
        }

        .cart-container table th, .cart-container table td {
            padding: 10px;
            border: 1px solid #ffffff;
        }

        .cart-container .total {
            margin-top: 20px;
            font-size: 20px;
            color: #ffdd83;
            text-align: right;
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
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="instrument-detail">
            <img src="<?php echo $instrument['image_path']; ?>" alt="<?php echo htmlspecialchars($instrument['name']); ?>">
            <h2><?php echo htmlspecialchars($instrument['name']); ?></h2>
            <h3>Price: ₹<?php echo number_format($instrument['price'], 2); ?></h3>
            <p><strong>History:</strong> <?php echo $detailed_info['history']; ?></p>
            <p><strong>Features:</strong> <?php echo $detailed_info['features']; ?></p>
            <p><strong>Uses:</strong> <?php echo $detailed_info['uses']; ?></p>

            <!-- Add to Cart Form -->
            <form action="instrument-detail.php?id=<?php echo $instrument['id']; ?>" method="POST">
                <input type="hidden" name="instrument_id" value="<?php echo $instrument['id']; ?>">
                <input type="hidden" name="instrument_name" value="<?php echo $instrument['name']; ?>">
                <input type="hidden" name="price" value="<?php echo $instrument['price']; ?>">
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>

            <a href="checkout.php?instrument_id=<?php echo $instrument['id']; ?>&price=<?php echo $instrument['price']; ?>">Buy Now</a>

        </div>

        <!-- Display Cart -->
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
    <div class="cart-container">
        <h3>Your Cart</h3>
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
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['price'] * $item['quantity'];
                    echo "<tr>
                            <td>{$item['name']}</td>
                            <td>₹" . number_format($item['price'], 2) . "</td>
                            <td>{$item['quantity']}</td>
                            <td>₹" . number_format($item['price'] * $item['quantity'], 2) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="total">
            <strong>Total: ₹<?php echo number_format($total, 2); ?></strong>
        </div>
    </div>
<?php endif; ?>

    </main>
</body>
</html>
