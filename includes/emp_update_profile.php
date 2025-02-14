<?php
session_start();
require "db_connect.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    switch ($category) {
        case 'personal':
            $email = trim($_POST['emailAddress']);
            $address = trim($_POST['address']);
            $gender = trim($_POST['gender']);
            $mobileNumber = trim($_POST['mobileNumber']);
            $relationship_status = trim($_POST['relationship_status']);

            $stmt = $conn->prepare("UPDATE tbl_employee SET emailAddress = ?, address = ?, gender = ?, mobileNumber = ?, relationship_status = ? WHERE user_id = ?");
            $stmt->bind_param("sssssi", $email, $address, $gender, $mobileNumber, $relationship_status, $user_id);
            break;

        case 'careerhistory':
            $job_title = trim($_POST['job_title']);
            $company_name = trim($_POST['company_name']);
            $start_date = trim($_POST['start_date']);
            $end_date = trim($_POST['end_date']);
            $still_in_role = isset($_POST['still_in_role']) ? 1 : 0;
            $description = trim($_POST['description']);

            if ($id) {
                $stmt = $conn->prepare("UPDATE tbl_careerhistory SET job_title = ?, company_name = ?, start_date = ?, end_date = ?, still_in_role = ?, description = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param("ssssisis", $job_title, $company_name, $start_date, $end_date, $still_in_role, $description, $id, $user_id);
            } else {
                $stmt = $conn->prepare("INSERT INTO tbl_careerhistory (user_id, job_title, company_name, start_date, end_date, still_in_role, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssis", $user_id, $job_title, $company_name, $start_date, $end_date, $still_in_role, $description);
            }
            break;

        case 'education':
            $course = trim($_POST['course']);
            $institution = trim($_POST['institution']);
            $ending_date = trim($_POST['ending_date']);
            $course_highlights = trim($_POST['course_highlights']);

            if ($id) {
                $stmt = $conn->prepare("UPDATE tbl_educback SET course = ?, institution = ?, ending_date = ?, course_highlights = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param("ssssii", $course, $institution, $ending_date, $course_highlights, $id, $user_id);
            } else {
                $stmt = $conn->prepare("INSERT INTO tbl_educback (user_id, course, institution, ending_date, course_highlights) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $user_id, $course, $institution, $ending_date, $course_highlights);
            }
            break;

        case 'languages':
            $language_name = trim($_POST['language_name']);

            if ($id) {
                $stmt = $conn->prepare("UPDATE tbl_language SET language_name = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param("sii", $language_name, $id, $user_id);
            } else {
                $stmt = $conn->prepare("INSERT INTO tbl_language (user_id, language_name) VALUES (?, ?)");
                $stmt->bind_param("is", $user_id, $language_name);
            }
            break;
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully.";
    } else {
        $_SESSION['error_message'] = "Profile update failed. Please try again.";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../emp_dashboard.php");
    exit();
}
?>
