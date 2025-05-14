<?php
require "../db_connect.php";

if (isset($_GET['application_id'])) {
    $application_id = intval($_GET['application_id']);

    // Fix: Remove 'ei.contactNumber' if it does not exist in tbl_emp_info
    $query = "SELECT ja.id AS application_id, ei.firstName, ei.lastName, ei.emailAddress, ei.address, ja.application_time, ja.status
              FROM tbl_job_application ja
              INNER JOIN tbl_emp_info ei ON ja.emp_id = ei.user_id
              WHERE ja.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $info = $result->fetch_assoc();
    $stmt->close();

    // No CV processing for now

    header('Content-Type: application/json');
    echo json_encode([
        'info' => $info
    ]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid application ID']);
}
?>
