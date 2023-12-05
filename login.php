<?php
session_start();

// Check if the error parameter is present in the URL
$error = isset($_GET['error']) ? $_GET['error'] : null;

// Display an error message based on the error parameter
$error_message = '';
if ($error == 1) {
    $error_message = 'Invalid username or password.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="center-container">
            <!-- Display error message if present -->
            <?php if ($error_message): ?>
                <center> <p style="color: red;"><?php echo $error_message; ?></p></center>
            <?php endif; ?>

            <form action="login_process.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" autocomplete="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>

                <button type="submit">Login</button>
            </form>
            
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>
    
    <!-- Add any other necessary content here -->
</body>
</html>
