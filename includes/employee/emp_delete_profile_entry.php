<?php
require '../db_connect.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$category = $data['category'];
$id = $data['id'];

$table_map = [
    'careerhistory' => 'tbl_career_history',
    'education' => 'tbl_education',
    'languages' => 'tbl_languages',
    'certification' => 'tbl_certifications'
];

if (!isset($table_map[$category])) {
    echo json_encode(['success' => false, 'message' => 'Invalid category']);
    exit;
}

$table = $table_map[$category];
$query = "DELETE FROM $table WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete entry']);
}

$stmt->close();
$conn->close();
?>
