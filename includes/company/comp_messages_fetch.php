<?php
session_start();
require_once '../db_connect.php';

// Check if user is logged in as company
if (!isset($_SESSION['company_id'])) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

$company_id = $_SESSION['company_id'];

try {
    // Get all job listings for this company
    $job_listings_query = "SELECT j.job_id, j.title, j.posted_date 
                          FROM tbl_job_listing j 
                          WHERE j.employer_id = ? 
                          ORDER BY j.posted_date DESC";
    
    $job_stmt = $conn->prepare($job_listings_query);
    if (!$job_stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $job_stmt->bind_param("i", $company_id);
    if (!$job_stmt->execute()) {
        throw new Exception("Execute failed: " . $job_stmt->error);
    }
    
    $job_result = $job_stmt->get_result();
    
    $job_listings = [];
    while ($job = $job_result->fetch_assoc()) {
        $job_listings[] = $job;
    }
    
    // If a specific job_id is provided, get messages for that job
    if (isset($_GET['job_id'])) {
        $job_id = $_GET['job_id'];
        
        // Verify that the job belongs to this company
        $verify_job = "SELECT job_id FROM tbl_job_listing 
                      WHERE job_id = ? AND employer_id = ?";
        $verify_stmt = $conn->prepare($verify_job);
        if (!$verify_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $verify_stmt->bind_param("ii", $job_id, $company_id);
        if (!$verify_stmt->execute()) {
            throw new Exception("Execute failed: " . $verify_stmt->error);
        }
        
        $verify_result = $verify_stmt->get_result();
        
        if ($verify_result->num_rows === 0) {
            echo json_encode(['error' => 'Invalid job ID']);
            exit();
        }
        
        // Get messages for this job
        $messages_query = "SELECT m.*, e.firstName, e.lastName, j.title as job_title
                          FROM tbl_job_message m
                          JOIN tbl_emp_info e ON m.emp_id = e.user_id
                          JOIN tbl_job_application a ON m.application_id = a.id
                          JOIN tbl_job_listing j ON a.job_id = j.job_id
                          WHERE j.job_id = ? AND m.comp_id = ?
                          ORDER BY m.timestamp DESC";
        
        $msg_stmt = $conn->prepare($messages_query);
        if (!$msg_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $msg_stmt->bind_param("ii", $job_id, $company_id);
        if (!$msg_stmt->execute()) {
            throw new Exception("Execute failed: " . $msg_stmt->error);
        }
        
        $msg_result = $msg_stmt->get_result();
        
        $messages = [];
        while ($message = $msg_result->fetch_assoc()) {
            $messages[] = [
                'id' => $message['id'],
                'subject' => $message['subject'],
                'message' => $message['message'],
                'timestamp' => $message['timestamp'],
                'recipient' => $message['firstName'] . ' ' . $message['lastName'],
                'job_title' => $message['job_title']
            ];
        }
        
        echo json_encode([
            'job_listings' => $job_listings,
            'messages' => $messages
        ]);
    } else {
        // If no job_id provided, just return the job listings
        echo json_encode([
            'job_listings' => $job_listings,
            'messages' => []
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

// Close all statements
if (isset($job_stmt)) $job_stmt->close();
if (isset($verify_stmt)) $verify_stmt->close();
if (isset($msg_stmt)) $msg_stmt->close();
$conn->close();
?>
