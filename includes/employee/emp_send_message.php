<?php
require '../db_connect.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['emp_id'], $data['message'], $data['application_id'])) {
    echo json_encode(['error' => 'Employee ID, message, and application ID are required']);
    exit();
}

$emp_id = intval($data['emp_id']);
$message = $data['message'];
$application_id = intval($data['application_id']);

$query = $conn->prepare("INSERT INTO tbl_job_message (emp_id, application_id, message) VALUES (?, ?, ?)");
$query->bind_param("iis", $emp_id, $application_id, $message);

if ($query->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to send message']);
}

$query->close();
$conn->close();
