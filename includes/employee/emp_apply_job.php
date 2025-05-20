<?php
require "../db_connect.php";
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1); // Temporarily set to 1 for debugging

$response = ['success' => false, 'error' => 'Unknown error occurred'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $job_id = intval($data['job_id']);
    $user_id = $_SESSION['user_id'];
    $selected_files = $data['selected_files']; // Array of selected file paths (directories + filenames)

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Fetch company_id and job title for the job
        $company_query = "SELECT employer_id, title FROM tbl_job_listing WHERE job_id = ?";
        $company_stmt = $conn->prepare($company_query);
        $company_stmt->bind_param("i", $job_id);
        $company_stmt->execute();
        $company_result = $company_stmt->get_result();
        if ($company_result->num_rows === 0) {
            throw new Exception("Invalid job ID: $job_id");
        }
        $job_data = $company_result->fetch_assoc();
        $company_id = $job_data['employer_id'];
        $job_title = $job_data['title'];
        $company_stmt->close();

        // Insert job application
        $query = "INSERT INTO tbl_job_application (emp_id, job_id, application_time, status) VALUES (?, ?, NOW(), 'applied')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $job_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert job application: " . $stmt->error);
        }
        $application_id = $stmt->insert_id;
        $stmt->close();

        // Insert notification for the company
        $notification_query = "INSERT INTO tbl_job_notifications (company_id, job_id, message, is_read, created_at) 
                               VALUES (?, ?, ?, 0, NOW())";
        $notification_stmt = $conn->prepare($notification_query);
        $message = "A new application has been submitted for your job listing: $job_title.";
        $notification_stmt->bind_param("iis", $company_id, $job_id, $message);
        if (!$notification_stmt->execute()) {
            throw new Exception("Failed to insert notification: " . $notification_stmt->error);
        }
        $notification_stmt->close();

        // Copy selected files and insert their paths into tbl_job_application_files
        $destination_dir = "../../db/pdf/application_files/";
        $file_insert_errors = [];
        foreach ($selected_files as $file) {
            $source_path = $file['cv_dir'] . $file['cv_file_name']; // Combine directory and file name
            $destination_path = $destination_dir . $file['cv_file_name'];

            if (file_exists($source_path)) {
                if (copy($source_path, $destination_path)) {
                    // Only insert the file name, not the full path
                    $insert_file_query = "INSERT INTO tbl_job_application_files (application_id, file_inserted) VALUES (?, ?)";
                    $file_stmt = $conn->prepare($insert_file_query);
                    $file_name_only = $file['cv_file_name'];
                    $file_stmt->bind_param("is", $application_id, $file_name_only);
                    if (!$file_stmt->execute()) {
                        $file_insert_errors[] = "Failed to insert file name into database: $file_name_only";
                    }
                    $file_stmt->close();
                } else {
                    $file_insert_errors[] = "Failed to copy file from $source_path to $destination_path";
                }
            } else {
                $file_insert_errors[] = "Source file does not exist: $source_path";
            }
        }

        if (!empty($file_insert_errors)) {
            throw new Exception("File processing errors: " . implode(", ", $file_insert_errors));
        }

        // Commit the transaction
        $conn->commit();
        $response = ['success' => true, 'message' => 'Application submitted successfully'];
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        $response = ['success' => false, 'error' => $e->getMessage()];
    }
} else {
    $response = ['success' => false, 'error' => 'Invalid request method'];
}

echo json_encode($response);
$conn->close();
?>
