<?php
session_start();
require "../db_connect.php";

if (!isset($_SESSION['company_id'])) {
    header("Location: ../../company/comp_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_id = $_SESSION['company_id'];

    // Check if a file was uploaded
    if (isset($_FILES['job_photo']) && $_FILES['job_photo']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../../db/images/job_listing/';
        $file_tmp = $_FILES['job_photo']['tmp_name'];
        $file_name = $_FILES['job_photo']['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        // Generate a unique file name
        $random_string = bin2hex(random_bytes(8));
        $new_file_name = $random_string . '_' . time() . '.' . $file_ext;
        $file_path = $upload_dir . $new_file_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Return the file path for further processing
            echo json_encode(["success" => true, "file_path" => $file_path]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to move uploaded file."]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "No file uploaded or upload error."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>