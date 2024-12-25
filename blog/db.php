<?php
// Database connection details
$servername = "localhost";  // Database server (usually localhost for XAMPP)
$username = "root";         // Database username (default is "root" for XAMPP)
$password = "";             // Database password (default is empty for XAMPP)
$dbname = "blog_website";  // Replace with your actual database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
