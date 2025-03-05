<?php
require "../db_connect.php";

if (isset($_POST['job_id']) && isset($_POST['status'])) {
    $job_id = intval($_POST['job_id']);
    $status = $_POST['status'];

    $query = "UPDATE tbl_job_listing SET status = ? WHERE job_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $job_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Job status updated successfully']);
    } else {
        echo json_encode(['error' => 'Failed to update job status']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid input']);
}

$conn->close();
?>
