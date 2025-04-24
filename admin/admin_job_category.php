<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include "../includes/db_connect.php";

// Fetch current categories
$query = "SELECT category_name FROM tbl_job_category";
$result = $conn->query($query);
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row["category_name"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Categories</title>
</head>
<body>
    <h1>Job Categories</h1>
    <h2>Current Categories</h2>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li><?php echo htmlspecialchars($category); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Add New Categories</h2>
    <form action="../includes/admin/process_add_categories.php" method="POST">
        <label for="categories">Enter categories (separated by commas):</label><br>
        <input type="text" id="categories" name="categories" required>
        <br><br>
        <button type="submit">Add Categories</button>
    </form>
</body>
</html>
