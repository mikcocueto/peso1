<?php
session_start();
require "../db_connect.php";

// Check if the user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (!isset($_POST['application_id']) || !isset($_POST['subject']) || !isset($_POST['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Application ID, subject, and message are required']);
    exit();
}

$application_id = $_POST['application_id'];
$subject = trim($_POST['subject']);
$message = trim($_POST['message']);

// Validate input
if (empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Subject and message cannot be empty']);
    exit();
}

if (strlen($subject) > 64) {
    http_response_code(400);
    echo json_encode(['error' => 'Subject must be 64 characters or less']);
    exit();
}

// Get employee ID from application
$query = "SELECT emp_id FROM tbl_job_application WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();

if (!$application) {
    http_response_code(404);
    echo json_encode(['error' => 'Application not found']);
    exit();
}

// Insert the message
$query = "INSERT INTO tbl_job_message (emp_id, comp_id, application_id, subject, message, is_seen) 
          VALUES (?, ?, ?, ?, ?, 0)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiiss", $application['emp_id'], $_SESSION['company_id'], $application_id, $subject, $message);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'message' => 'Message sent successfully',
        'message_id' => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message']);
}

$stmt->close();
?> 