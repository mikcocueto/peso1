<?php
require "../db_connect.php";

if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);

    $query = "SELECT ja.id AS application_id, ei.firstName, ei.lastName, ei.emailAddress, ja.application_time, ja.status
              FROM tbl_job_application ja
              INNER JOIN tbl_emp_info ei ON ja.emp_id = ei.user_id
              WHERE ja.job_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }
    if (!$stmt->bind_param("i", $job_id)) {
        http_response_code(500);
        echo json_encode(['error' => 'Bind param failed: ' . $stmt->error]);
        exit;
    }
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    if (!$result) {
        http_response_code(500);
        echo json_encode(['error' => 'Get result failed: ' . $stmt->error]);
        exit;
    }
    $candidates = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($candidates);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid job ID']);
}
?>
