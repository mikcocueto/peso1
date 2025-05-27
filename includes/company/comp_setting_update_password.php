<?php
session_start();
include '../../includes/db_connect.php';

// Check if user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

// Get and sanitize input
$current_password = trim($_POST['current_password']);
$new_password = trim($_POST['new_password']);
$confirm_password = trim($_POST['confirm_password']);

// Validate input
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit();
}

if ($new_password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'New password and confirm password do not match']);
    exit();
}

if (strlen($new_password) < 8) {
    echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long']);
    exit();
}

if (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password) || !preg_match('/[^A-Za-z0-9]/', $new_password)) {
    echo json_encode(['status' => 'error', 'message' => 'Password must include a number, uppercase letter, and symbol']);
    exit();
}

// Get current user's login details
$company_id = $_SESSION['company_id'];
$query = "SELECT password, salt FROM tbl_comp_login WHERE company_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $company_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Verify current password using password_verify
    if (password_verify($current_password . $row['salt'], $row['password'])) {
        // Generate new salt and hash for new password
        $new_salt = bin2hex(random_bytes(16));
        $hashed_new_password = password_hash($new_password . $new_salt, PASSWORD_DEFAULT);
        
        // Update password and salt
        $update_query = "UPDATE tbl_comp_login SET password = ?, salt = ? WHERE company_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ssi", $hashed_new_password, $new_salt, $company_id);
        
        if (mysqli_stmt_execute($update_stmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Password updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update password']);
        }
        
        mysqli_stmt_close($update_stmt);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
