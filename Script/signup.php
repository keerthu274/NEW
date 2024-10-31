<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost'; // your MySQL host
$db = 'my_database'; // replace with your database name
$user = 'your_username'; // replace with your MySQL username
$pass = 'your_password'; // replace with your MySQL password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $email = htmlspecialchars(trim($_POST['email']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $repeatPassword = htmlspecialchars(trim($_POST['repeatPassword']));

    // Check if passwords match
    if ($password !== $repeatPassword) {
        die("Passwords do not match.");
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    if ($count > 0) {
        die("Username or email already exists.");
    }
    $stmt->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (name, email, username, password) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("MySQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $fullName, $email, $username, $hashedPassword);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Registration successful!"; // Success message
        // Optionally redirect or display a message
    } else {
        echo "Error: " . $stmt->error; // Error message
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>