<?php
session_start();
require "../db_connect.php";

if (!isset($_SESSION['company_id'])) {
    header("Location: ../../company/comp_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_id = $_SESSION['company_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $employment_type = $_POST['employment_type'];
    $location = $_POST['location'];
    $salary_min = $_POST['salary_min'];
    $salary_max = $_POST['salary_max'];
    $currency = $_POST['currency'];
    $category_id = $_POST['category_id'];
    $expiry_date = $_POST['expiry_date'];
    $posted_date = date('Y-m-d');

    // Check if company is verified
    $verification_query = "SELECT company_verified FROM tbl_comp_info WHERE company_id = ?";
    $stmt = $conn->prepare($verification_query);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $verification_result = $stmt->get_result();
    $verification_status = $verification_result->fetch_assoc();
    $is_verified = $verification_status && $verification_status['company_verified'] == 1;
    $stmt->close();

    if (!$is_verified) {
        $_SESSION['error'] = "Your company needs to be verified before posting jobs.";
        header("Location: ../../company/comp_dashboard.php");
        exit();
    }

    // Insert job listing
    $query = "INSERT INTO tbl_job_listing (employer_id, title, description, requirements, employment_type, 
              location, salary_min, salary_max, currency, category_id, posted_date, expiry_date, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssddssss", $company_id, $title, $description, $requirements, $employment_type, 
                      $location, $salary_min, $salary_max, $currency, $category_id, $posted_date, $expiry_date);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Job posted successfully!";
    } else {
        $_SESSION['error'] = "Error posting job: " . $conn->error;
    }
    
    $stmt->close();
    header("Location: ../../company/comp_dashboard.php");
    exit();
}
?>
