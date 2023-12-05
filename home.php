<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <div>Welcome, <?php echo $_SESSION['username']; ?>!</div>
        <div>Role: <?php echo $_SESSION['role']; ?></div>
        <a href="logout.php">Logout</a>
    </nav>
    <!-- Add any other necessary content here -->
</body>
</html>
