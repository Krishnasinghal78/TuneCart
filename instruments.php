<?php
session_start();
include 'php/db.php';

// Fetch instruments from the database
$instruments_query = $conn->query("SELECT * FROM instruments ORDER BY name ASC");
$instruments = $instruments_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruments - TuneCart</title>
    <link rel="stylesheet" href="assets/dashboard.css">
    <style>
        body {
            background-color: #1a1a2e;
            color: #ffffff;
        }
        .instrument-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }
        .instrument-item {
            background-color: #22223b;
            padding: 15px;
            border-radius: 10px;
            width: 250px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        .instrument-item img {
            width: 100%;
            height: 150px;
            border-radius: 8px;
        }
        .instrument-item h4 {
            color: #e0aaff;
            margin: 10px 0;
        }
        .instrument-item p {
            font-size: 14px;
            color: #bdbdbd;
        }
        .instrument-item strong {
            color: #ffdd83;
            font-size: 16px;
        }
        .instrument-item button {
            margin-top: 10px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            background-color: #9b5de5;
            color: #fff;
            cursor: pointer;
        }
        .instrument-item button:hover {
            background-color: #6a4c93;
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <h1>TuneCart</h1>
            <nav>
                <ul>
                    <li><a href="user-dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <h2 style="text-align: center; margin-top: 20px;">Available Instruments</h2>
        <div class="instrument-list">
            <?php foreach ($instruments as $instrument): ?>
                <div class="instrument-item" onclick="showInstrumentDetails(<?php echo $instrument['id']; ?>)">
                    <img src="<?php echo $instrument['image_path']; ?>" alt="<?php echo htmlspecialchars($instrument['name']); ?>">
                    <h4><?php echo htmlspecialchars($instrument['name']); ?></h4>
                    <p><?php echo htmlspecialchars($instrument['description']); ?></p>
                    <strong>₹<?php echo number_format($instrument['price'], 2); ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
        function showInstrumentDetails(id) {
            window.location.href = "instrument-detail.php?id=" + id;
        }
    </script>
</body>
</html>
