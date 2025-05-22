<?php
session_start();
require "../db_connect.php";

// Check if the user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (!isset($_POST['application_id']) || !isset($_POST['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Application ID and status are required']);
    exit();
}

$application_id = $_POST['application_id'];
$status = $_POST['status'];

// Validate status
$valid_statuses = ['applied', 'awaiting', 'reviewed', 'contacted', 'hired', 'rejected'];
if (!in_array($status, $valid_statuses)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid status']);
    exit();
}

// Update the application status
$query = "UPDATE tbl_job_application SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $application_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update status']);
}

$stmt->close();
?> 