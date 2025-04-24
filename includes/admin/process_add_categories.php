<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../../admin/admin_login.php");
    exit();
}

include "../db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categories = $_POST["categories"];
    $categoryArray = array_map('trim', explode(',', $categories));

    $stmt = $conn->prepare("INSERT INTO tbl_job_category (category_name) VALUES (?)");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        header("Location: ../../admin/admin_job_category.php?error=Database error");
        exit();
    }

    foreach ($categoryArray as $category) {
        if (!empty($category)) {
            $stmt->bind_param("s", $category);
            $stmt->execute();
        }
    }

    $stmt->close();
    $conn->close();

    header("Location: ../../admin/admin_job_category.php?success=Categories added successfully");
    exit();
}
?>
