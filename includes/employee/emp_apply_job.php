<?php
require "../db_connect.php";
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 in production

// Start output buffering to prevent unintended output
ob_start();

$response = ['success' => false, 'error' => 'Unknown error occurred'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $job_id = intval($data['job_id']);
    $user_id = $_SESSION['user_id'];
    $selected_files = $data['selected_files']; // Array of selected file paths (directories + filenames)

    // Insert job application
    $query = "INSERT INTO tbl_job_application (emp_id, job_id, application_time, status) VALUES (?, ?, NOW(), 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $job_id);

    if ($stmt->execute()) {
        $application_id = $stmt->insert_id;

        // Insert notification for the company
        $notification_query = "INSERT INTO tbl_job_notifications (company_id, job_id, message, is_read, created_at) 
                               VALUES (?, ?, ?, 0, NOW())";
        $notification_stmt = $conn->prepare($notification_query);
        $message = "A new application has been submitted for your job listing.";
        $notification_stmt->bind_param("iis", $job_id, $job_id, $message);
        $notification_stmt->execute();
        $notification_stmt->close();

        // Copy selected files and insert their paths into tbl_job_application_files
        $destination_dir = "../../db/pdf/application_files/";
        $file_insert_errors = [];
        foreach ($selected_files as $file) {
            $source_path = $file['cv_dir'] . $file['cv_file_name']; // Combine directory and file name
            $destination_path = $destination_dir . $file['cv_file_name'];

            if (file_exists($source_path)) {
                if (copy($source_path, $destination_path)) {
                    $insert_file_query = "INSERT INTO tbl_job_application_files (application_id, file_inserted_dir) VALUES (?, ?)";
                    $file_stmt = $conn->prepare($insert_file_query);
                    $file_stmt->bind_param("is", $application_id, $destination_path);
                    if (!$file_stmt->execute()) {
                        $file_insert_errors[] = "Failed to insert file path into database: $destination_path";
                    }
                    $file_stmt->close();
                } else {
                    $file_insert_errors[] = "Failed to copy file from $source_path to $destination_path";
                }
            } else {
                $file_insert_errors[] = "Source file does not exist: $source_path";
            }
        }

        if (empty($file_insert_errors)) {
            $response = ['success' => true, 'message' => 'Application submitted successfully'];
        } else {
            $response = ['success' => false, 'error' => 'Some files could not be processed', 'details' => $file_insert_errors];
        }
    } else {
        $response = ['success' => false, 'error' => 'Failed to submit application'];
    }

    $stmt->close();
} else {
    $response = ['success' => false, 'error' => 'Invalid request method'];
}

// Flush output buffer to ensure only JSON is sent
ob_end_clean();
echo json_encode($response);
$conn->close();
?>
