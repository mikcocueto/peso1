<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../employee/emp_login.php");
    die();
}

$user_id = $_SESSION['user_id'];

// Function to generate random prefix
function generateRandomPrefix($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $prefix = '';
    for ($i = 0; $i < $length; $i++) {
        $prefix .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $prefix;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['cv_file']) && isset($_POST['cv_name'])) {
    $file = $_FILES['cv_file'];
    $cv_name = $_POST['cv_name'];
    $upload_dir = '../../db/pdf/emp_cv/';
    $original_name = basename($file['name']);
    $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $random_prefix = generateRandomPrefix();
    $file_name = $random_prefix . '_' . $original_name;
    $target_file = $upload_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a PDF
    if ($file_type != 'pdf') {
        $_SESSION['error_message'] = 'Only PDF files are allowed.';
        error_log('Error: Only PDF files are allowed.');
        header("Location: ../../employee/emp_dashboard.php");
        exit();
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $_SESSION['error_message'] = 'File already exists.';
        error_log('Error: File already exists.');
        header("Location: ../../employee/emp_dashboard.php");
        exit();
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // Insert file details into the database
        $query = "INSERT INTO tbl_emp_cv (emp_id, cv_file_name, cv_name, cv_dir, upload_timestamp) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $user_id, $file_name, $cv_name, $upload_dir);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'CV uploaded successfully.';
        } else {
            $_SESSION['error_message'] = 'Failed to upload CV.';
            error_log('Error: Failed to upload CV. ' . $stmt->error);
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = 'Failed to move uploaded file.';
        error_log('Error: Failed to move uploaded file.');
    }
} else {
    $_SESSION['error_message'] = 'No file uploaded or CV name missing.';
    error_log('Error: No file uploaded or CV name missing.');
}

header("Location: ../../employee/emp_dashboard.php");
exit();
?>
