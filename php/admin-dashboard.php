<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Query to fetch all songs and instruments for admin
$songs = $conn->query("SELECT * FROM songs")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TuneCart</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <header class="header">
        <h1>Welcome Admin</h1>
        <a href="logout.php" class="btn logout">Logout</a>
    </header>
    <main>
        <section class="song-list">
            <h2>Manage Songs</h2>
            <button class="btn-add">Add Song</button>
            <ul>
                <?php foreach ($songs as $song): ?>
                    <li><?php echo $song['title']; ?> <button class="btn-delete">Delete</button></li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>
</html>
