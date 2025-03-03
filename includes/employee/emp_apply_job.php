<?php
require "db_connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $job_id = intval($data['job_id']);
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO tbl_job_application (emp_id, job_id, application_time, status) VALUES (?, ?, NOW(), 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $job_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Application submitted successfully']);
    } else {
        echo json_encode(['error' => 'Failed to submit application']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>
