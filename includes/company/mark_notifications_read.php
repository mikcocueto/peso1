<?php
session_start();
require "../db_connect.php";

if (!isset($_SESSION['company_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

$company_id = $_SESSION['company_id'];

// Mark all unread notifications as read
$query = "UPDATE tbl_job_notifications SET is_read = 1 WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $company_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update notifications']);
}

$stmt->close();
$conn->close();
?>
