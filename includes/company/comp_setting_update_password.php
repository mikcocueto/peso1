<?php
session_start();
require_once '../../db_connection.php'; // Adjust path as needed

header('Content-Type: application/json'); // Ensure the response is JSON

// Suppress unexpected output
ob_start();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $company_id = $_SESSION['company_id'] ?? null; // Ensure the user is logged in
        if (!$company_id) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
            exit();
        }

        $current_password = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
        $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
        $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit();
        }

        if ($new_password !== $confirm_password) {
            echo json_encode(['status' => 'error', 'message' => 'New password and confirm password do not match.']);
            exit();
        }

        if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password) || !preg_match('/[^A-Za-z0-9]/', $new_password)) {
            echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters and include a number, uppercase letter, and symbol.']);
            exit();
        }

        // Fetch current password and salt from the database
        $stmt = $conn->prepare("SELECT password, salt FROM tbl_comp_login WHERE company_id = ?");
        if (!$stmt) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
            exit();
        }
        $stmt->bind_param("i", $company_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password, $salt);
        $stmt->fetch();
        $stmt->close();

        // Verify current password
        if (!password_verify($current_password . $salt, $hashed_password)) {
            echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
            exit();
        }

        // Generate new salt and hash the new password
        $new_salt = bin2hex(random_bytes(16));
        $new_hashed_password = password_hash($new_password . $new_salt, PASSWORD_DEFAULT);

        // Update the password in the database
        $update_stmt = $conn->prepare("UPDATE tbl_comp_login SET password = ?, salt = ? WHERE company_id = ?");
        if (!$update_stmt) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
            exit();
        }
        $update_stmt->bind_param("ssi", $new_hashed_password, $new_salt, $company_id);

        if ($update_stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Password updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
        }

        $update_stmt->close();
        $conn->close();
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
}

// Clear any unexpected output
ob_end_clean();
?>
