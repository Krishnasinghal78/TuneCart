<?php
session_start();
include 'php\db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch songs for display
$songs = $conn->query("SELECT * FROM songs")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TuneCart</title>
    <link rel="stylesheet" href="assets/admin-dashboard.css">
</head>
<body>
    <header class="header">
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="btn logout">Logout</a>
    </header>
    <main>
        <section class="song-list">
            <h2>Manage Songs</h2>
            <button id="btn-add-song" class="btn-add">Add Song</button>
            <ul>
                <?php foreach ($songs as $song): ?>
                    <li>
                        <span><?php echo htmlspecialchars($song['title']); ?></span>
                        <button class="btn-delete">Delete</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <!-- Modal for Adding Song -->
    <div id="add-song-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Add New Song</h2>
            <form action="add_song.php" method="POST" enctype="multipart/form-data">
                <label for="title">Song Title</label>
                <input type="text" id="title" name="title" required>

                <label for="artist">Artist</label>
                <input type="text" id="artist" name="artist" required>

                <label for="file">Song File</label>
                <input type="file" id="file" name="file" accept="audio/*" required>

                <button type="submit" class="btn-submit">Upload Song</button>
            </form>
        </div>
    </div>

    <script src="assets/admin-dashboard.js"></script>
</body>
</html>
