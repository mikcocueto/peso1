<?php
session_start();
include __DIR__ . '/db_connect.php'; // Ensure correct path

if (!isset($_SESSION['user_id']) || !isset($_POST['category'])) {
    header("Location: ../emp_dashboard.php"); // Updated redirection path
    die(); // Terminate script execution
}

$user_id = $_SESSION['user_id'];
$category = $_POST['category'];
$id = isset($_POST['id']) ? intval($_POST['id']) : null;

switch ($category) {
    case 'personal':
        $table = 'tbl_employee';
        $fields = ['emailAddress', 'address', 'gender', 'mobileNumber', 'relationship_status'];
        break;
    case 'careerhistory':
        $table = 'tbl_careerhistory';
        $fields = ['job_title', 'company_name', 'start_date', 'end_date', 'still_in_role', 'description'];
        break;
    case 'education':
        $table = 'tbl_educback';
        $fields = ['course', 'institution', 'end_date', 'course_highlights'];
        break;
    case 'languages':
        $table = 'tbl_language';
        $fields = ['language_name'];
        break;
    default:
        die("Invalid category.");
}

$update_fields = [];
$update_values = [];
foreach ($fields as $field) {
    if (isset($_POST[$field])) {
        if ($field == 'still_in_role') {
            $update_fields[] = "$field = ?";
            $update_values[] = isset($_POST[$field]) ? 1 : 0;
        } else {
            $update_fields[] = "$field = ?";
            $update_values[] = trim($_POST[$field]);
        }
    }
}

if (empty($update_fields)) {
    die("No valid fields to update.");
}

if ($category == 'personal') {
    $update_values[] = $user_id;
    $query = "UPDATE $table SET " . implode(", ", $update_fields) . " WHERE user_id = ?";
} else {
    if ($id) {
        $update_values[] = $id;
        $query = "UPDATE $table SET " . implode(", ", $update_fields) . " WHERE id = ?";
    } else {
        $update_values[] = $user_id;
        $query = "INSERT INTO $table (" . implode(", ", $fields) . ", user_id) VALUES (" . implode(", ", array_fill(0, count($fields), '?')) . ", ?)";
    }
}

$stmt = $conn->prepare($query);
$stmt->bind_param(str_repeat("s", count($update_values) - 1) . "i", ...$update_values);

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
