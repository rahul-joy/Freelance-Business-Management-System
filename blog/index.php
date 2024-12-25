<?php
include('db.php');  // Include the database connection

// Set the number of posts per page
$posts_per_page = 9;

// Get the current page number from the URL, default to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $posts_per_page;

// Fetch blog posts with LIMIT and OFFSET
$sql = "SELECT * FROM blogs ORDER BY created_at DESC LIMIT $posts_per_page OFFSET $offset";
$result = $conn->query($sql);

// Calculate the total number of blog posts
$sql_total = "SELECT COUNT(*) as total FROM blogs";
$total_result = $conn->query($sql_total);
$total_row = $total_result->fetch_assoc();
$total_posts = $total_row['total'];

// Calculate the total number of pages
$total_pages = ceil($total_posts / $posts_per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Blog Posts</title>
    <link rel="stylesheet" href="styles.css">  <!-- Correct path to styles.css -->
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f1f1f1; /* Light background for contrast */
        }
        .navbar {
      background-color: #2C6777;
      display: flex;
      align-items: center;
      justify-content: space-around;
      padding: 20px 30px;
      width: 100%;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      margin-bottom: 20px;
  }
  
.navbar .logo {
    color: white;
    font-weight: bold;
    font-size: 1.4rem; /* Slightly bigger font for the logo */
}
.nav-links {
    display: flex;
    gap: 30px;
}
.nav-links a {
    color: white;
    text-decoration: none;
    font-size: 1.1rem; /* Slightly larger text for better visibility */
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
.profile-icon {
    background-color: white; /* White background for profile icon */
    border-radius: 50%;
    width: 40px; /* Slightly bigger profile icon */
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}
.profile-icon:hover {
    background-color: #6cfff8; /* Orange color on hover */
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
  z-index: 999;
}
.dropdown-menu a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 10px;
    text-align: center;
}
.dropdown-menu a:hover {
    background-color: #1f4d4d; /* Darker shade for hover effect */
}

    </style>
</head>
<body>
<div class="navbar">
        <div class="logo">LOGO</div>
        <div class="nav-links">
            <a href="http://localhost/rixoybd.com">Home</a>
            <a href="http://localhost/rixoybd.com/blog/global_giftcard.html">Gift Cards</a>
            <a href="#">Services</a>
            <a href="http://localhost/rixoybd.com/blog/">Blog</a>
            <a href="#">About Us</a>
        </div>
        <div class="profile-icon" onclick="toggleDropdown()">&#128100;</div>
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="../user/login.html">Sign In</a>
            <a href="../user/register.html">Sign Up</a>
        </div>
    </div>

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

    <div class="container"> <!-- Centered container for content -->
        <!-- <header>
            <h1>All Blog Posts</h1>
        </header> -->

        <div class="blog-posts">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="blog-post">';
                    
                    // Display Image
                    if ($row['image']) {
                        echo '<img src="' . $row['image'] . '" alt="Blog Image">';
                    }
                    
                    // Display Title and Author
                    echo '<h2>' . $row['title'] . '</h2>';
                    echo '<p class="author"><strong>By:</strong> ' . $row['author'] . ' | <em>' . $row['created_at'] . '</em></p>';
                    
                    // "Read More" button (link to the full blog post page)
                    echo '<a href="view.php?id=' . $row['id'] . '">Read More</a>';
                    echo '</div>'; // End of blog-post div
                }
            } else {
                echo '<p>No blog posts found.</p>';
            }
            ?>
        </div>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php
            // Previous Page Link
            if ($page > 1) {
                echo '<a href="?page=' . ($page - 1) . '">&laquo; Previous</a>';
            }
            
            // Page Number Links
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo '<a href="?page=' . $i . '" class="active">' . $i . '</a>';
                } else {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
            }

            // Next Page Link
            if ($page < $total_pages) {
                echo '<a href="?page=' . ($page + 1) . '">Next &raquo;</a>';
            }
            ?>
        </div>

        <!-- <footer>
            <p>&copy; 2024 Blog Website</p>
        </footer> -->
    </div> <!-- End of container div -->
</body>
</html>
