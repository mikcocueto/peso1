<?php
session_start();
require "../../includes/db_connect.php";

// Check if user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Validate input parameters
if (!isset($_GET['job_id']) || !isset($_GET['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit();
}

$job_id = $_GET['job_id'];
$status = $_GET['status'];
$company_id = $_SESSION['company_id'];

// Verify that the job belongs to the company
$verify_query = "SELECT job_id FROM tbl_job_listing WHERE job_id = ? AND employer_id = ?";
$stmt = $conn->prepare($verify_query);
$stmt->bind_param("ii", $job_id, $company_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit();
}
$stmt->close();

// Base query for fetching candidates
$base_query = "SELECT ja.id as application_id, ja.application_time, ja.status,
                      ei.firstName, ei.lastName, ei.emailAddress, ei.mobileNumber as contactNumber,
                      jl.title as job_title,
                      (SELECT COUNT(*) FROM tbl_job_application_files jaf WHERE jaf.application_id = ja.id) as file_count
               FROM tbl_job_application ja
               JOIN tbl_emp_info ei ON ja.emp_id = ei.user_id
               JOIN tbl_job_listing jl ON ja.job_id = jl.job_id
               WHERE ja.job_id = ?";

// Modify query based on status
if ($status === 'all') {
    $query = $base_query . " ORDER BY ja.application_time DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $job_id);
} else {
    $query = $base_query . " AND ja.status = ? ORDER BY ja.application_time DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $job_id, $status);
}

$stmt->execute();
$result = $stmt->get_result();
$candidates = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Return the candidates as JSON
header('Content-Type: application/json');
echo json_encode($candidates);
?>
