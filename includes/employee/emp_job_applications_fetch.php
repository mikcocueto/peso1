<?php
require '../db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['emp_id'])) {
    echo json_encode(['error' => 'Employee ID is required']);
    exit();
}

$emp_id = intval($_GET['emp_id']);

$query = $conn->prepare("SELECT ja.id AS application_id, jl.title AS job_title FROM tbl_job_application ja JOIN tbl_job_listing jl ON ja.job_id = jl.job_id WHERE ja.emp_id = ?");
$query->bind_param("i", $emp_id);
$query->execute();
$result = $query->get_result();

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

$query->close();
$conn->close();

echo json_encode($applications);
