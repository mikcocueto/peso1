<?php
require "../db_connect.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$job_id = intval($_GET['job_id']);

$query = "SELECT COUNT(*) as count FROM tbl_job_application WHERE emp_id = ? AND job_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $job_id);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc()['count'];
$stmt->close();

echo json_encode(['applied' => $count > 0]);

$conn->close();
?>
