<?php
session_start();
require "../db_connect.php"; // Database connection

if (isset($_SESSION['user_id']) && isset($_POST['job_id']) && isset($_POST['action'])) {
    $user_id = $_SESSION['user_id'];
    $job_id = $_POST['job_id'];
    $action = $_POST['action'];

    if ($action === 'save') {
        // Check if the job is already saved
        $check_query = $conn->prepare("SELECT * FROM tbl_emp_saved_jobs WHERE user_id = ? AND job_id = ?");
        $check_query->bind_param("ii", $user_id, $job_id);
        $check_query->execute();
        $result = $check_query->get_result();

        if ($result->num_rows == 0) {
            // Save the job
            $save_query = $conn->prepare("INSERT INTO tbl_emp_saved_jobs (user_id, job_id) VALUES (?, ?)");
            $save_query->bind_param("ii", $user_id, $job_id);
            if ($save_query->execute()) {
                $_SESSION['message'] = "Job saved successfully!";
            } else {
                $_SESSION['message'] = "Failed to save the job.";
            }
            $save_query->close();
        } else {
            $_SESSION['message'] = "Job is already saved.";
        }

        $check_query->close();
    } elseif ($action === 'unsave') {
        // Unsave the job
        $unsave_query = $conn->prepare("DELETE FROM tbl_emp_saved_jobs WHERE user_id = ? AND job_id = ?");
        $unsave_query->bind_param("ii", $user_id, $job_id);
        if ($unsave_query->execute()) {
            $_SESSION['message'] = "Job unsaved successfully!";
        } else {
            $_SESSION['message'] = "Failed to unsave the job.";
        }
        $unsave_query->close();
    } else {
        $_SESSION['message'] = "Invalid action.";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
}

$conn->close();
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
