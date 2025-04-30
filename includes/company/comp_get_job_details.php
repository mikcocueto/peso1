<?php
require "../db_connect.php";

if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);

    $query = "SELECT job_id, title, description, requirements, employment_type, location, salary_min, salary_max, currency, category_id, expiry_date, job_cover_img 
              FROM tbl_job_listing 
              WHERE job_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $job = $result->fetch_assoc();
        echo json_encode($job);
    } else {
        echo json_encode(['error' => 'Job not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid job ID']);
}

$conn->close();
?>
