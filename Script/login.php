<?php
// Start the session for user login
session_start();
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
    $username = htmlspecialchars(trim($_POST['loginUsername']));
    $password = htmlspecialchars(trim($_POST['loginPassword']));

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    if ($stmt === false) {
        die("MySQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if username exists
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Successful login
            $_SESSION['username'] = $username; // Store username in session
            echo "Login successful!"; // Optionally redirect or display a message
            // Example: header("Location: welcome.php"); exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Username does not exist.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>