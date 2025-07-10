<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Get the song ID from the URL
if (isset($_GET['id'])) {
    $songId = $_GET['id'];

    // Delete the song from the database
    $stmt = $conn->prepare("DELETE FROM songs WHERE id = ?");
    $stmt->execute([$songId]);

    header('Location: admin-dashboard.php'); // Redirect back to the admin dashboard
    exit();
}
?>
