<?php
session_start(); // Start the session
require "../db_connect.php"; // Database connection

// Check if the company is logged in and category is set
if (!isset($_SESSION['company_id']) || !isset($_POST['category'])) {
    header("Location: ../../company/comp_dashboard.php"); // Redirect to dashboard if not logged in
    die(); // Terminate script execution
}

$company_id = $_SESSION['company_id']; // Get the company ID from the session
$category = $_POST['category']; // Get the category from the POST request

// Determine the table and fields to update based on the category
switch ($category) {
    case 'company':
        $table = 'tbl_company';
        $fields = ['firstName', 'lastName', 'companyName', 'country', 'companyNumber'];
        break;
    default:
        die("Invalid category.");
}

$update_fields = [];
$update_values = [];
foreach ($fields as $field) {
    if (isset($_POST[$field])) {
        $update_fields[] = "$field = ?";
        $update_values[] = trim($_POST[$field]);
    }
}

if (!empty($_FILES['comp_logo']['name'])) {
    $target_dir = "db/images/company/logo/"; // Adjusted path
    $target_file = $target_dir . basename($_FILES["comp_logo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["comp_logo"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["comp_logo"]["tmp_name"], "../../" . $target_file)) { // Adjusted path
            $update_fields[] = "comp_logo_dir = ?";
            $update_values[] = "../" . $target_file; // Adjusted path
        } else {
            $_SESSION['error_message'] = "Failed to upload logo.";
            header("Location: ../../company/comp_dashboard.php");
            die();
        }
    } else {
        $_SESSION['error_message'] = "File is not an image.";
        header("Location: ../../company/comp_dashboard.php");
        die();
    }
}

if (empty($update_fields)) {
    die("No valid fields to update.");
}

$update_values[] = $company_id;
$query = "UPDATE $table SET " . implode(", ", $update_fields) . " WHERE company_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param(str_repeat("s", count($update_values) - 1) . "i", ...$update_values);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Information updated successfully!";
} else {
    $_SESSION['error_message'] = "Failed to update information.";
}

$stmt->close();
$conn->close();

header("Location: ../../company/comp_dashboard.php"); // Redirect to dashboard
die(); // Ensure no further execution
?>
