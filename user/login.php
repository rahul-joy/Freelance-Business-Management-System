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
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if email exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            header("Location: ../blog/uploads/upload.php");
            exit();
            // You can start a session here if necessary
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Email not found!";
    }
}

$conn->close();
?>
