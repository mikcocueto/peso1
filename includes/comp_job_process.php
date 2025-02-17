<?php
session_start();
if (!isset($_SESSION["company_id"])) {
    header("Location: ../comp_dashboard.php");
    exit();
}

require "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employer_id = $_SESSION["company_id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $requirements = $_POST["requirements"];
    $employment_type = $_POST["employment_type"];
    $location = $_POST["location"];
    $salary_min = $_POST["salary_min"];
    $salary_max = $_POST["salary_max"];
    $currency = $_POST["currency"];
    $category_id = $_POST["category_id"];
    $expiry_date = $_POST["expiry_date"];
    $posted_date = date("Y-m-d");

    $stmt = $conn->prepare("INSERT INTO tbl_job_listing (employer_id, title, description, requirements, employment_type, location, salary_min, salary_max, currency, category_id, posted_date, expiry_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("isssssiissss", $employer_id, $title, $description, $requirements, $employment_type, $location, $salary_min, $salary_max, $currency, $category_id, $posted_date, $expiry_date);

    if ($stmt->execute()) {
        $_SESSION["success_message"] = "Job listing created successfully.";
    } else {
        $_SESSION["error_message"] = "Failed to create job listing.";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../comp_dashboard.php");
    exit();
}
?>
