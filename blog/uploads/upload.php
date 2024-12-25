<?php
// Include the database connection file (go one level up to include db.php)
include('../db.php');  // Correct path to db.php from the uploads folder

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];

    // Initialize the image variable
    $image = '';

    // Create the 'uploads' folder if it doesn't exist
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);  // Create the 'uploads' folder with proper permissions
    }

    // Check if an image file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];          // Get the file name
        $imageTmp = $_FILES['image']['tmp_name'];      // Get the temporary file path
        $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);  // Get the file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];    // Allowed file extensions

        // Check if the file extension is allowed
        if (in_array($imageExt, $allowedExtensions)) {
            // Create a unique file name to avoid overwriting
            $imagePath = 'uploads/' . uniqid() . '.' . $imageExt;
            
            // Move the uploaded file to the "uploads" directory
            if (move_uploaded_file($imageTmp, '../' . $imagePath)) {
                // Successfully uploaded image
                $image = $imagePath;  // Store the file path to save in the database
            } else {
                echo "Error uploading the image.";
                exit();
            }
        } else {
            echo "Only jpg, jpeg, png, gif images are allowed.";
            exit();
        }
    }

    // Insert the blog post into the database
    $sql = "INSERT INTO blogs (title, content, author, image) VALUES ('$title', '$content', '$author', '$image')";
    if ($conn->query($sql) === TRUE) {
        echo "New blog post created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Blog</title>
    <style>
        /* General styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: #2C6777;
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 2rem;
        }

        /* Navbar styles */
        .navbar {
            background-color: #2C6777;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            color: white;
            font-weight: bold;
            font-size: 1.4rem;
        }

        .nav-links {
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            position: relative;
        }

        .nav-links a:hover::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 2px;
            background-color: #fff200; /* Bright yellow hover effect */
        }

        /* Profile Icon Dropdown */
        .profile-icon {
            background-color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .profile-icon:hover {
            background-color: #6cfff8;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 135px;
            top: 80px;
            background-color: #2C6777;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            width: 160px;
            padding: 10px 0;
        }

        .dropdown-menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            text-align: center;
        }

        .dropdown-menu a:hover {
            background-color: #1f4d4d;
        }

        /* Form styles */
        form {
            background-color: white;
            padding: 40px;
            margin: 30px auto;
            width: 60%;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            font-size: 1.1rem;
            margin-bottom: 10px;
            display: block;
        }

        input[type="text"], input[type="file"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="file"]:focus, textarea:focus {
            border-color: #2C6777;
            outline: none;
        }

        textarea {
            min-height: 200px;
            resize: vertical;
        }

        button[type="submit"] {
            background-color: #2C6777;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #1d4f58;
        }

        footer {
            background-color: #2C6777;
            color: white;
            text-align: center;
            padding: 15px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">LOGO</div>
    <div class="nav-links">
        <a href="../../index.html">Home</a>
        <a href="../../blog/global_giftcard.html">Gift Cards</a>
        <a href="#">Services</a>
        <a href="../../blog/">Blog</a>
        <a href="#">About Us</a>
    </div>
    <div class="profile-icon" onclick="toggleDropdown()">&#128100;</div>
    <div class="dropdown-menu" id="dropdownMenu">
    <a href="../../user/login.html">Sign In</a>
    <a href="../../user/register.html">Sign Up</a>
    </div>
</div>

<!-- Upload Form -->
<header>
    <h1>Upload New Blog Post</h1>
</header>

<form action="upload.php" method="POST" enctype="multipart/form-data">
    <label for="title">Blog Title:</label>
    <input type="text" name="title" id="title" required>

    <label for="content">Content:</label>
    <textarea name="content" id="content" required></textarea>

    <label for="author">Author:</label>
    <input type="text" name="author" id="author" required>

    <label for="image">Upload Image:</label>
    <input type="file" name="image" id="image" accept="image/*">

    <button type="submit">Submit Blog</button>
</form>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Blog Website</p>
</footer>

<script>
    function toggleDropdown() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    }

    window.onclick = function (event) {
        if (!event.target.matches('.profile-icon')) {
            const dropdownMenu = document.getElementById('dropdownMenu');
            if (dropdownMenu.style.display === 'block') {
                dropdownMenu.style.display = 'none';
            }
        }
    }
</script>

</body>
</html>
