<?php
session_start();
include __DIR__ . '/db_connect.php'; // Ensure correct path

if (!isset($_SESSION['user_id']) || !isset($_POST['field']) || !isset($_POST['value'])) {
    header("Location: ../emp_dashboard.php"); // Updated redirection path
    die(); // Terminate script execution
}

$user_id = $_SESSION['user_id'];
$field = $_POST['field'];
$value = trim($_POST['value']);

// Define allowed fields to prevent SQL injection
$allowed_fields = ['emailAddress', 'address', 'gender', 'mobileNumber', 'relationship_status'];

if (!in_array($field, $allowed_fields)) {
    die("Invalid field.");
}

// Update query
$query = "UPDATE tbl_employee SET $field = ? WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $value, $user_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Information updated successfully!";
} else {
    $_SESSION['error_message'] = "Failed to update information.";
}

$stmt->close();
$conn->close();

header("Location: ../emp_dashboard.php"); // Ensure correct redirection
die(); // Ensure no further execution
?>
