<?php
$servername = "localhost"; // change this if necessary
$username = "root";        // your database username
$password = "";            // your database password
$dbname = "blog_website";       // the name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if the username or email already exists
    $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($check_query);
    
    if ($result->num_rows > 0) {
        echo "Username or Email already exists!";
    } else {
        // Insert data into the database
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
            header("Refresh: 2; url=login.html"); // Redirects after 2 seconds
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
