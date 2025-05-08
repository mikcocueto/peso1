<?php
require "../db_connect.php";

if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);

    $query = "SELECT ei.firstName, ei.lastName, ei.emailAddress, ja.application_time, ja.status
              FROM tbl_job_application ja
              INNER JOIN tbl_emp_info ei ON ja.emp_id = ei.user_id
              WHERE ja.job_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $candidates = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($candidates);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid job ID']);
}
?>
