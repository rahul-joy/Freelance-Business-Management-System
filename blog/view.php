<?php
include('db.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM blogs WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Blog not found!";
        exit();
    }
} else {
    echo "Invalid blog id!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['title']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Blog Website</h1>
    </header>

    <div class="blog-post">
        <h2><?php echo $row['title']; ?></h2>
        <p>By <?php echo $row['author']; ?> | <?php echo $row['created_at']; ?></p>
        <div class="content">
            <p><?php echo nl2br($row['content']); ?></p>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Blog Website</p>
    </footer>
</body>
</html>
