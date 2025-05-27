<?php
require "../db_connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $target_dir = "../../db/images/emp/pfp/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $file_name = basename($_FILES["profile_picture"]["name"]); // Extract only the file name
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        $_SESSION['error_message'] = "File is not an image.";
        header("Location: ../../employee/emp_dashboard.php");
        exit();
    }

    // Check file size
    if ($_FILES["profile_picture"]["size"] > 500000) {
        $_SESSION['error_message'] = "Sorry, your file is too large.";
        header("Location: ../../employee/emp_dashboard.php");
        exit();
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $_SESSION['error_message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        header("Location: ../../employee/emp_dashboard.php");
        exit();
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $_SESSION['error_message'] = "Sorry, file already exists.";
        header("Location: ../../employee/emp_dashboard.php");
        exit();
    }

    // Try to upload file
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        // Update the database with the new profile picture file name
        $query = "UPDATE tbl_emp_info SET pfp_dir = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $file_name, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Profile picture uploaded successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update profile picture in the database.";
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
    }

    header("Location: ../../employee/emp_dashboard.php");
    exit();
} else {
    $_SESSION['error_message'] = "Invalid request method.";
    header("Location: ../../employee/emp_dashboard.php");
    exit();
}


?>