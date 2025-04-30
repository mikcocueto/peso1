<?php
$conn = new mysqli("localhost", "root", "", "pesodb");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $location = $conn->real_escape_string($_POST['location']);
  $latitude = $conn->real_escape_string($_POST['latitude']);
  $longitude = $conn->real_escape_string($_POST['longitude']);

  // Insert into tbl_0_test_environment
  $conn->query("INSERT INTO tbl_0_test_environment (location) VALUES ('$location')");
  $jobId = $conn->insert_id;

  // Insert into tbl_job_coordinates
  $conn->query("INSERT INTO tbl_job_coordinates (job_id, latitude, longitude) VALUES ($jobId, '$latitude', '$longitude')");

  header("Location: location_test.php");
  exit;
}
?>
