<?php

include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../employee/emp_login.php");
    die();
}

$user_id = $_SESSION['user_id'];

// Fetch employee details
$query = "SELECT firstName, lastName, emailAddress, address, gender, mobileNumber, relationship_status FROM tbl_emp_info WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

// Fetch career history
$query = "SELECT id, job_title, company_name, start_date, end_date, still_in_role, description AS JDescription FROM tbl_emp_careerhistory WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$career_history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch educational background
$query = "SELECT id, course, institution, ending_date, course_highlights FROM tbl_emp_educback WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$education = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch languages
$query = "SELECT id, language_name FROM tbl_emp_language WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$languages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch certifications
$query = "SELECT id, licence_name, issuing_organization, issue_date, expiry_date, description FROM tbl_emp_certification WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$certifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch account creation date
$query = "SELECT create_timestamp FROM tbl_emp_info WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$account_creation_date = $result->fetch_assoc()['create_timestamp'];
$stmt->close();

// Fetch CVs
$query = "SELECT id, cv_file_name, cv_dir FROM tbl_emp_cv WHERE emp_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cvs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Check for success or error messages
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Close the connection
$conn->close();
?>
