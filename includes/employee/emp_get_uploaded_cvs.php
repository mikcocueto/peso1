<?php
require "../db_connect.php";
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 in production

// Start output buffering to prevent unintended output
ob_start();

$user_id = $_SESSION['user_id'];

$query = "SELECT cv_dir, cv_file_name, cv_name FROM tbl_emp_cv WHERE emp_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cvs = [];
while ($row = $result->fetch_assoc()) {
    $cvs[] = $row;
}

// Flush output buffer to ensure only JSON is sent
ob_end_clean();
echo json_encode($cvs);

$stmt->close();
$conn->close();
?>
