<?php
session_start();
include 'php/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all songs
$songs = $conn->query("SELECT * FROM songs")->fetchAll(PDO::FETCH_ASSOC);

// Fetch playlists for the user
$playlists_query = $conn->prepare("SELECT * FROM playlists WHERE user_id = :user_id");
$playlists_query->bindParam(':user_id', $user_id);
$playlists_query->execute();
$playlists = $playlists_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch songs for a specific playlist
$playlist_songs = [];
if (isset($_GET['playlist_id'])) {
    $playlist_id = $_GET['playlist_id'];

    // Get songs for this playlist
    $stmt = $conn->prepare("SELECT songs.* FROM songs 
                            JOIN playlist_songs ON songs.id = playlist_songs.song_id 
                            WHERE playlist_songs.playlist_id = :playlist_id");
    $stmt->bindParam(':playlist_id', $playlist_id);
    $stmt->execute();
    $playlist_songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Create a new playlist
if (isset($_POST['create_playlist'])) {
    $playlist_name = trim(htmlspecialchars($_POST['playlist_name']));
    if (!empty($playlist_name)) {
        $stmt = $conn->prepare("INSERT INTO playlists (user_id, name) VALUES (:user_id, :name)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $playlist_name, PDO::PARAM_STR);
        if ($stmt->execute()) {
            header("Location: user-dashboard.php"); // Refresh to show the updated playlist
            exit;
        } else {
            echo "<script>alert('Error: Could not create the playlist. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Playlist name cannot be empty.');</script>";
    }
}

// Add song to a playlist
if (isset($_POST['add_to_playlist'])) {
    $song_id = $_POST['song_id'];
    $playlist_id = $_POST['playlist_id'];
    $stmt = $conn->prepare("INSERT INTO playlist_songs (playlist_id, song_id) VALUES (:playlist_id, :song_id)");
    $stmt->bindParam(':playlist_id', $playlist_id);
    $stmt->bindParam(':song_id', $song_id);
    $stmt->execute();
    header("Location: user-dashboard.php?playlist_id=$playlist_id"); // Reload page after adding song
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - TuneCart</title>
    <link rel="stylesheet" href="assets/dashboard.css">
</head>
<body class="dashboard-page">
<header>
    <div class="navbar">
        <h1>TuneCart</h1>
        <nav>
            <ul>
                <li><a href="instruments.php">Instruments</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="dashboard-container">
        <!-- Sidebar Playlist -->
        <aside class="sidebar">
            <h3>Your Playlists</h3>
            <ul>
                <?php if (!empty($playlists)): ?>
                    <?php foreach ($playlists as $playlist): ?>
                        <li class="playlist-item">
                            <h4 onclick="loadPlaylist(<?php echo $playlist['id']; ?>)">
                                <?php echo htmlspecialchars($playlist['name']); ?>
                            </h4>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No playlists found. Create a new playlist!</p>
                <?php endif; ?>
                <li class="playlist-item">
                    <button onclick="showCreatePlaylistModal()">Create New Playlist</button>
                </li>
            </ul>
        </aside>

        <!-- Main Content with Available Songs or Playlist Songs -->
        <section class="song-list">
            <h2><?php echo isset($_GET['playlist_id']) && count($playlist_songs) > 0 ? 'Songs in Playlist' : 'Your Available Songs'; ?></h2>
            <div class="songs">
                <?php if (!empty($playlist_songs)): ?>
                    <?php foreach ($playlist_songs as $song): ?>
                        <div class="song-item" data-file-path="uploads/<?php echo htmlspecialchars($song['file_path']); ?>" data-song-id="<?php echo $song['id']; ?>">
                            <img src="assets/tunecartimage.webp" alt="Album Cover">
                            <h4><?php echo htmlspecialchars($song['title']); ?></h4>
                            <button class="play-button">Play</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php foreach ($songs as $song): ?>
                        <div class="song-item" data-file-path=" <?php echo htmlspecialchars($song['file_path']); ?>" data-song-id="<?php echo $song['id']; ?>">
                            <img src="assets/tunecartimage.webp" alt="Album Cover">
                            <h4><?php echo htmlspecialchars($song['title']); ?></h4>
                            <button class="play-button">Play</button>
                            <!-- Add to Playlist -->
                            <form method="POST" action="user-dashboard.php">
                                <input type="hidden" name="song_id" value="<?php echo $song['id']; ?>">
                                <select name="playlist_id">
                                    <option value="">Select Playlist</option>
                                    <?php foreach ($playlists as $playlist): ?>
                                        <option value="<?php echo $playlist['id']; ?>"><?php echo htmlspecialchars($playlist['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" name="add_to_playlist">Add to Playlist</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<!-- Sticky Media Player -->
<div class="media-player">
    <div class="now-playing">
        <img src="assets/tunecartimage.webp" alt="Song Cover" id="now-playing-cover">
        <h4 id="now-playing-title">Now Playing: </h4>
    </div>
    <div class="player-controls">
        <button id="prev">⏮</button>
        <button id="play">▶</button>
        <button id="pause" style="display: none;">⏸</button>
        <button id="next">⏭</button>
    </div>
    <audio id="audio-player" controls></audio>
</div>

<!-- Create Playlist Modal -->
<div id="create-playlist-modal" style="display: none;">
    <div class="modal-content">
        <span id="close-modal-btn" style="cursor: pointer;">&times;</span>
        <h2>Create New Playlist</h2>
        <form method="POST" action="user-dashboard.php">
            <label for="playlist_name">Playlist Name:</label>
            <input type="text" id="playlist_name" name="playlist_name" required>
            <button type="submit" name="create_playlist">Create Playlist</button>
        </form>
    </div>
</div>

<script>
    // Handle audio play
    const audioPlayer = document.getElementById("audio-player");
    const songItems = document.querySelectorAll(".song-item");
    let currentSongIndex = -1;

    function playSong(filePath, songTitle) {
        audioPlayer.src = filePath;
        audioPlayer.play();
        document.getElementById("now-playing-title").textContent = "Now Playing: " + songTitle;
    }

    // Play button for each song
    songItems.forEach((item, index) => {
        const playButton = item.querySelector(".play-button");
        const filePath = item.getAttribute("data-file-path");
        const songTitle = item.querySelector("h4").textContent;

        playButton.addEventListener("click", () => {
            currentSongIndex = index;
            playSong(filePath, songTitle);
        });
    });

    // Next song
    document.getElementById("next").addEventListener("click", () => {
        if (songItems.length === 0) return;
        currentSongIndex = (currentSongIndex + 1) % songItems.length;
        const nextSong = songItems[currentSongIndex];
        playSong(nextSong.getAttribute("data-file-path"), nextSong.querySelector("h4").textContent);
    });

    // Previous song
    document.getElementById("prev").addEventListener("click", () => {
        if (songItems.length === 0) return;
        currentSongIndex = (currentSongIndex - 1 + songItems.length) % songItems.length;
        const prevSong = songItems[currentSongIndex];
        playSong(prevSong.getAttribute("data-file-path"), prevSong.querySelector("h4").textContent);
    });

    // Show modal for creating playlist
    function showCreatePlaylistModal() {
        console.log("Enter Playlist Name");
        document.getElementById("create-playlist-modal").style.display = "flex";
    }

    // Close modal
    document.getElementById("close-modal-btn").addEventListener("click", () => {
        document.getElementById("create-playlist-modal").style.display = "none";
    });

    // Close modal if clicking outside the modal content
    window.addEventListener("click", (event) => {
        if (event.target === document.getElementById("create-playlist-modal")) {
            document.getElementById("create-playlist-modal").style.display = "none";
        }
    });

    // Load playlist
    function loadPlaylist(playlistId) {
        window.location.href = `user-dashboard.php?playlist_id=${playlistId}`;
    }
</script>

</body>
</html>
