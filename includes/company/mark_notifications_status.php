<?php
session_start();
require "../db_connect.php";

if (!isset($_SESSION['company_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

$company_id = $_SESSION['company_id'];

// Get the notification ID and is_read value from the request
$data = json_decode(file_get_contents("php://input"), true);
$notification_id = $data['notification_id'] ?? null;
$is_read = $data['is_read'] ?? null;

if ($notification_id === null || $is_read === null) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit();
}

// Update the notification's read status
$query = "UPDATE tbl_job_notifications SET is_read = ? WHERE notification_id = ? AND company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $is_read, $notification_id, $company_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update notification status']);
}

$stmt->close();
$conn->close();
?>
