<?php
session_start();
include 'php/db.php';  // Include your database connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to find the user in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists
    if ($user) {
        // If the email and password match
        if (password_verify($password, $user['password'])) {
            // Check if it's admin login (only for this specific email)
            if ($email == 'rishiop.812@gmail.com' && $password == '123') {
                // Set session for admin
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = 'admin';  // Set role as admin
                header('Location: admin-dashboard.php');  // Redirect to admin dashboard
                exit;
            } else {
                // Set session for regular user
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = 'user';  // Set role as user
                header('Location: user-dashboard.php');  // Redirect to user dashboard
                exit;
            }
        } else {
            $error = "Invalid credentials!";
        }
    } else {
        $error = "No user found with that email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TuneCart</title>
    <link rel="stylesheet" href="assets/login.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h2>Login to TuneCart</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email">
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
            
            <button type="submit" name="login" class="btn">Login</button>
        </form>

        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>
