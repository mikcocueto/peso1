<?php
require '../db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['application_id'])) {
    echo json_encode(['error' => 'Application ID is required']);
    exit();
}

$application_id = intval($_GET['application_id']);

$query = $conn->prepare("SELECT jm.subject, jm.message, jm.timestamp, 
    CASE WHEN jm.emp_id IS NOT NULL THEN 'employee' ELSE 'company' END AS sender, 
    ci.companyName AS company_name 
FROM tbl_job_message jm 
LEFT JOIN tbl_comp_info ci ON jm.comp_id = ci.company_id 
WHERE jm.application_id = ? 
ORDER BY jm.timestamp ASC");
$query->bind_param("i", $application_id);
$query->execute();
$result = $query->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$query->close();
$conn->close();

echo json_encode($messages);
