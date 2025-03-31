<?php
session_start();
require "../db_connect.php";

if (!isset($_SESSION['company_id'])) {
    header("Location: ../../company/comp_login.php");
    exit();
}

$company_id = $_SESSION['company_id'];

// Function to generate random prefix
function generateRandomPrefix($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $prefix = '';
    for ($i = 0; $i < $length; $i++) {
        $prefix .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $prefix;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["business_permit"])) {
    $target_dir = "../../db/pdf/comp_business_permit/";
    $original_name = basename($_FILES["business_permit"]["name"]);
    $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $random_prefix = generateRandomPrefix();
    $file_name = $random_prefix . '_' . $original_name;
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a PDF
    if ($file_type != "pdf") {
        $_SESSION['error_message'] = "Only PDF files are allowed.";
        header("Location: ../../company/comp_dashboard.php");
        exit();
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["business_permit"]["tmp_name"], $target_file)) {
        // Insert verification request into `tbl_comp_verification`
        $stmt = $conn->prepare("INSERT INTO tbl_comp_verification (comp_id, status, dir_business_permit) VALUES (?, 'pending', ?)");
        $stmt->bind_param("is", $company_id, $target_file);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Verification request submitted successfully.";
        } else {
            $_SESSION['error_message'] = "Error submitting verification request: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Error uploading file.";
    }
}

$conn->close();
header("Location: ../../company/comp_dashboard.php");
exit();
?>
