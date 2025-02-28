<?php
require "db_connect.php";

$job_id = intval($_GET['job_id']);

$query = "SELECT e.firstName, e.lastName, e.emailAddress, e.mobileNumber, ja.application_time 
          FROM tbl_job_application ja 
          JOIN tbl_employee e ON ja.emp_id = e.user_id 
          WHERE ja.job_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$applicants = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode($applicants);

$conn->close();
?>
