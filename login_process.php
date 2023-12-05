<?php
session_start();

// Establish a database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=summative_application", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Sanitize user inputs
$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

// Use a prepared statement to prevent SQL injection
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();

// Fetch the results
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the user exists and verify the password using password_verify
if ($user && password_verify($password, $user['password'])) {
    // Set session variables
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $user['role'];

    // Fetch additional data based on the user's role
    switch ($user['role']) {
        case 'student':
            $dataStmt = $pdo->prepare("SELECT * FROM students WHERE username = :username");
            break;
        case 'admin':
            $dataStmt = $pdo->prepare("SELECT * FROM admin WHERE username = :username");
            break;
        case 'tester':
            $dataStmt = $pdo->prepare("SELECT * FROM tester WHERE username = :username");
            break;
        default:
            // Handle other roles as needed
            break;
    }

    $dataStmt->bindParam(':username', $username);
    $dataStmt->execute();
    $userData = $dataStmt->fetch(PDO::FETCH_ASSOC);

    // You can use $userData as needed, for example, store it in a session variable
    $_SESSION['userData'] = $userData;

    // Redirect to the home page
    header("Location: home.php");
    exit();
} else {
    // Redirect to the login page with an error message in the URL
    header("Location: login.php?error=1");
    exit();
}
?>
