<?php
session_start();
include '../db_connect.php';

header('Content-Type: application/json');

$response = ['success' => false];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($_SESSION['user_id']) || !isset($input['category']) || !isset($input['id'])) {
            throw new Exception('Invalid request');
        }

        $user_id = $_SESSION['user_id'];
        $category = $input['category'];
        $id = $input['id'];

        if ($category === 'careerhistory') {
            $stmt = $conn->prepare("DELETE FROM tbl_emp_careerhistory WHERE id = ? AND user_id = ?");
            $stmt->bind_param('ii', $id, $user_id);
            if ($stmt->execute()) {
                $response['success'] = true;
            } else {
                throw new Exception('Failed to delete entry');
            }
            $stmt->close();
        } else {
            throw new Exception('Invalid category');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

$conn->close();
?>
