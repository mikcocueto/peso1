<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../employee/emp_login.php");
    die();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cv_id'])) {
    $cv_id = $_POST['cv_id'];

    // Fetch the file details from the database
    $query = "SELECT cv_file_name, cv_dir FROM tbl_emp_cv WHERE id = ? AND emp_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $cv_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cv = $result->fetch_assoc();
    $stmt->close();

    if ($cv) {
        $file_path = $cv['cv_dir'] . $cv['cv_file_name'];

        // Delete the file from the directory
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete the file details from the database
        $query = "DELETE FROM tbl_emp_cv WHERE id = ? AND emp_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $cv_id, $user_id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'CV deleted successfully.';
        } else {
            $_SESSION['error_message'] = 'Failed to delete CV.';
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = 'CV not found.';
    }
} else {
    $_SESSION['error_message'] = 'Invalid request.';
}

header("Location: ../../employee/emp_dashboard.php");
exit();
?>
