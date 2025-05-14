<?php
require "../db_connect.php";

if (isset($_GET['application_id'])) {
    $application_id = intval($_GET['application_id']);

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

    // Fetch CV file names for this application
    $files_query = "SELECT file_inserted FROM tbl_job_application_files WHERE application_id = ?";
    $files_stmt = $conn->prepare($files_query);
    $files_stmt->bind_param("i", $application_id);
    $files_stmt->execute();
    $files_result = $files_stmt->get_result();
    $files = [];
    while ($row = $files_result->fetch_assoc()) {
        $files[] = $row['file_inserted'];
    }
    $files_stmt->close();

    header('Content-Type: application/json');
    echo json_encode([
        'info' => $info,
        'files' => $files
    ]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid application ID']);
}
?>
