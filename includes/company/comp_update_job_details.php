<?php
require "../db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = intval($_POST['job_id']);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $employment_type = $_POST['employment_type'];
    $location = $_POST['location'];
    $salary_min = $_POST['salary_min'];
    $salary_max = $_POST['salary_max'];
    $currency = $_POST['currency'];
    $category_id = intval($_POST['category_id']);
    $expiry_date = $_POST['expiry_date'];

    // Update query
    $query = "UPDATE tbl_job_listing 
              SET title = ?, description = ?, requirements = ?, employment_type = ?, location = ?, salary_min = ?, salary_max = ?, currency = ?, category_id = ?, expiry_date = ?
              WHERE job_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssiisisi", $title, $description, $requirements, $employment_type, $location, $salary_min, $salary_max, $currency, $category_id, $expiry_date, $job_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Job details updated successfully']);
    } else {
        echo json_encode(['error' => 'Failed to update job details: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>
