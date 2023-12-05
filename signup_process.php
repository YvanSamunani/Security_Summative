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
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
$confirmPassword = htmlspecialchars($_POST['confirm_password'], ENT_QUOTES, 'UTF-8');
$role = htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8');

// Check if the user already exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$userExists = $stmt->fetch();

if ($userExists) {
    // Redirect to the signup page with an error message in the URL
    header("Location: signup.php?error=user_exists");
    exit();
}

// Check if the password meets strength requirements and matches the confirmation
if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/\d/", $password) || $password !== $confirmPassword) {
    // Redirect to the signup page with an error message in the URL
    header("Location: signup.php?error=weak_password");
    exit();
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user into the respective table based on role
switch ($role) {
    case 'student':
        $insertQuery = "INSERT INTO Students (username, email, password) VALUES (:username, :email, :password)";
        break;

    case 'tester':
        $insertQuery = "INSERT INTO Tester (username, email, password) VALUES (:username, :email, :password)";
        break;

    case 'admin':
        $insertQuery = "INSERT INTO Admin (username, email, password) VALUES (:username, :email, :password)";
        break;

    default:
        // Handle other roles or invalid role values as needed
        header("Location: signup.php?error=invalid_role");
        exit();
}

// Use a prepared statement to prevent SQL injection
$stmt = $pdo->prepare($insertQuery);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $hashedPassword);
$stmt->execute();

// Redirect to the login page after successful signup
header("Location: login.php");
exit();
?>
