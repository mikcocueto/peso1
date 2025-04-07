<?php
require "../db_connect.php";
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 in production

// Start output buffering to prevent unintended output
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $job_id = intval($data['job_id']);
    $user_id = $_SESSION['user_id'];
    $selected_files = $data['selected_files']; // Array of selected file paths

    // Insert job application
    $query = "INSERT INTO tbl_job_application (emp_id, job_id, application_time, status) VALUES (?, ?, NOW(), 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $job_id);

    if ($stmt->execute()) {
        $application_id = $stmt->insert_id;

        // Copy selected files and insert their paths into tbl_job_application_files
        $destination_dir = "../../db/pdf/application_files/";
        foreach ($selected_files as $file_path) {
            $file_name = basename($file_path);
            $destination_path = $destination_dir . $file_name;

            if (copy($file_path, $destination_path)) {
                $insert_file_query = "INSERT INTO tbl_job_application_files (application_id, file_inserted_dir) VALUES (?, ?)";
                $file_stmt = $conn->prepare($insert_file_query);
                $file_stmt->bind_param("is", $application_id, $destination_path);
                $file_stmt->execute();
                $file_stmt->close();
            }
        }

        echo json_encode(['success' => 'Application submitted successfully']);
    } else {
        echo json_encode(['error' => 'Failed to submit application']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

// Flush output buffer to ensure only JSON is sent
ob_end_clean();
$conn->close();
?>
