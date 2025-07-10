<?php
session_start();
include 'php\db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $file = $_FILES['file'];

    // Upload the song file to the server
    $uploadDir = 'uploads/';
    $filePath = $uploadDir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Insert the song into the database
        $stmt = $conn->prepare("INSERT INTO songs (title, file_path) VALUES (?, ?)");
        $stmt->execute([$title, $filePath]);

        header('Location: admin-dashboard.php'); // Redirect to the admin dashboard
        exit();
    } else {
        echo "Error uploading the file.";
    }
}
?>
