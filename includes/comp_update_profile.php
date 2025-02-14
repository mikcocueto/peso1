<?php
session_start();
include __DIR__ . '/db_connect.php'; // Ensure correct path

if (!isset($_SESSION['company_id']) || !isset($_POST['category'])) {
    header("Location: ../comp_dashboard.php"); // Updated redirection path
    die(); // Terminate script execution
}

$company_id = $_SESSION['company_id'];
$category = $_POST['category'];

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

header("Location: ../comp_dashboard.php"); // Ensure correct redirection
die(); // Ensure no further execution
?>
