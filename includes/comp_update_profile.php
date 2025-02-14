<?php
session_start(); // Start the session
include __DIR__ . '/db_connect.php'; // Include database connection

// Check if the company is logged in and category is set
if (!isset($_SESSION['company_id']) || !isset($_POST['category'])) {
    header("Location: ../comp_dashboard.php"); // Redirect to dashboard if not logged in
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

header("Location: ../comp_dashboard.php"); // Redirect to dashboard
die(); // Ensure no further execution
?>
