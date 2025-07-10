<?php
session_start();
include 'php/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuneCart - Home</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <h1><a href="index.php">TuneCart</a></h1>
            <div class="logo">
           <img src="assets/tunecart2-removebg-preview.png" alt="TuneCart Logo" class="logo-img">

</div>

        </div>
        <nav>
            <ul>
                
                <li><a href="login.php" class="btn">Login</a></li>
                <li><a href="signup.php" class="btn">Sign Up</a></li>
            </ul>
        </nav>
    </header>

   <section class="hero">
    <div class="hero-content">
        <h2 class="welcome-text">Welcome to TuneCart</h2>
        <p>Your one-stop destination for music and musical instruments.</p>
        <a href="user-dashboard.php" class="btn-primary">Start Listening</a>
    </div>
</section>



    <section class="features">
        <div class="feature">
            <h3>Discover New Music</h3>
            <p>Explore the best music collection curated just for you.</p>
            <div class="feature-content">
                <div class="more-info">
                    <p>Explore the latest tracks, curated playlists, and discover music that suits your mood and style. Stay updated with new releases and trending songs.</p>
                </div>
                <div class="feature-image">
                    <img src="assets/3.jpg" alt="Discover Music">
                </div>
            </div>
        </div>
        <div class="feature">
            <h3>Buy Musical Instruments</h3>
            <p>Shop the finest instruments and gear at amazing prices.</p>
            <div class="feature-content">
                <div class="more-info">
                    <p>Browse through a wide range of musical instruments. Whether you're a beginner or a professional, we have the right gear for you. From guitars to synthesizers, find it all here.</p>
                </div>
                <div class="feature-image">
                    <img src="assets/4.jpg" alt="Musical Instruments">
                </div>
            </div>
        </div>
    </section>

    <!-- Full-Screen Background Image Section -->
    <section class="full-image">
        <?php
            // Check if the image file exists
            $image_path = 'assets/tunecart2.jpg';
            if (file_exists($image_path)) {
                echo '<div class="image" style="background-image: url(\'' . $image_path . '\');"></div>';
            } else {
                echo '<p>Image not found.</p>'; // Error message if the image is not found
            }
        ?>
    </section>

    <footer>
        <p>&copy; 2024 TuneCart. All rights reserved.</p>
    </footer>

    <script src="assets/script.js"></script>
</body>
</html>
