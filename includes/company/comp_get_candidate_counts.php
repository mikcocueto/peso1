<?php
session_start();
require "../db_connect.php";

// Check if user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Check if job_id is provided
if (!isset($_GET['job_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Job ID is required']);
    exit();
}

$job_id = $_GET['job_id'];
$company_id = $_SESSION['company_id'];

// Verify that the job belongs to the company
$verify_query = "SELECT job_id FROM tbl_job_listing WHERE job_id = ? AND employer_id = ?";
$stmt = $conn->prepare($verify_query);
$stmt->bind_param("ii", $job_id, $company_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access to this job']);
    exit();
}
$stmt->close();

// Get counts for each status
$counts_query = "SELECT 
    SUM(CASE WHEN ja.status = 'applied' THEN 1 ELSE 0 END) as applied_count,
    SUM(CASE WHEN ja.status = 'awaiting' THEN 1 ELSE 0 END) as awaiting_count,
    SUM(CASE WHEN ja.status = 'reviewed' THEN 1 ELSE 0 END) as reviewed_count,
    SUM(CASE WHEN ja.status = 'contacted' THEN 1 ELSE 0 END) as contacted_count,
    SUM(CASE WHEN ja.status = 'hired' THEN 1 ELSE 0 END) as hired_count,
    SUM(CASE WHEN ja.status = 'rejected' THEN 1 ELSE 0 END) as rejected_count
FROM tbl_job_application ja
WHERE ja.job_id = ?";

$stmt = $conn->prepare($counts_query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$counts = $result->fetch_assoc();
$stmt->close();

// Convert null values to 0
$counts = array_map(function($value) {
    return $value === null ? 0 : (int)$value;
}, $counts);

// Return the counts as JSON
header('Content-Type: application/json');
echo json_encode($counts); 