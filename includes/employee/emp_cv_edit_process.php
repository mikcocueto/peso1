<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../employee/emp_login.php");
    die();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cv_id']) && isset($_POST['cv_name'])) {
    $cv_id = $_POST['cv_id'];
    $cv_name = $_POST['cv_name'];
    $file = isset($_FILES['cv_file']) ? $_FILES['cv_file'] : null;
    $upload_dir = '../../db/pdf/emp_cv/';
    $file_name = $file ? basename($file['name']) : null;
    $target_file = $file ? $upload_dir . $file_name : null;
    $file_type = $file ? strtolower(pathinfo($target_file, PATHINFO_EXTENSION)) : null;

    // Check if file is a PDF
    if ($file && $file_type != 'pdf') {
        $_SESSION['error_message'] = 'Only PDF files are allowed.';
        error_log('Error: Only PDF files are allowed.');
        header("Location: ../../employee/emp_dashboard.php");
        exit();
    }

    // Move the uploaded file to the target directory
    if ($file && !move_uploaded_file($file['tmp_name'], $target_file)) {
        $_SESSION['error_message'] = 'Failed to move uploaded file.';
        error_log('Error: Failed to move uploaded file.');
        header("Location: ../../employee/emp_dashboard.php");
        exit();
    }

    // Update file details in the database
    $query = "UPDATE tbl_emp_cv SET cv_name = ?, cv_file_name = IFNULL(?, cv_file_name), upload_timestamp = NOW() WHERE id = ? AND emp_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $cv_name, $file_name, $cv_id, $user_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'CV updated successfully.';
    } else {
        $_SESSION['error_message'] = 'Failed to update CV.';
        error_log('Error: Failed to update CV. ' . $stmt->error);
    }
    $stmt->close();
} else {
    $_SESSION['error_message'] = 'CV ID or name missing.';
    error_log('Error: CV ID or name missing.');
}

header("Location: ../../employee/emp_dashboard.php");
exit();
?>
