<?php
require '../db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['emp_id'])) {
    echo json_encode(['error' => 'Employee ID is required']);
    exit();
}

$emp_id = intval($_GET['emp_id']);

$query = $conn->prepare("SELECT DISTINCT ja.id AS application_id, jl.title AS job_title, ci.companyName AS company_name 
    FROM tbl_job_application ja 
    INNER JOIN tbl_job_listing jl ON ja.job_id = jl.job_id 
    INNER JOIN tbl_job_message jm ON ja.id = jm.application_id 
    INNER JOIN tbl_comp_info ci ON jl.employer_id = ci.company_id 
    WHERE ja.emp_id = ?");
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
