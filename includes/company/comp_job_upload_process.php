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
    $posted_date = date('Y-m-d H:i:s'); // Include hour, minutes, and seconds
    $job_cover_img = null;
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Handle file upload
    if (isset($_FILES['job_photo']) && $_FILES['job_photo']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../../db/images/job_listing/';
        $file_tmp = $_FILES['job_photo']['tmp_name'];
        $file_name = $_FILES['job_photo']['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        // Generate a unique file name
        $random_string = bin2hex(random_bytes(8));
        $new_file_name = $random_string . '_' . time() . '.' . $file_ext;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
            $job_cover_img = $new_file_name; // Save only the file name
        }
    }

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

    // Insert coordinates into tbl_job_coordinates
    $coordinate_id = null;
    if (!empty($latitude) && !empty($longitude)) {
        $coordinates_query = "INSERT INTO tbl_job_coordinates (coordinates) VALUES (ST_GeomFromText(?))";
        $stmt = $conn->prepare($coordinates_query);
        $point = "POINT($longitude $latitude)";
        $stmt->bind_param("s", $point);
        if ($stmt->execute()) {
            $coordinate_id = $stmt->insert_id;
        }
        $stmt->close();
    }

    // Insert job listing
    $query = "INSERT INTO tbl_job_listing (employer_id, title, description, requirements, employment_type, 
              location, coordinate_id, salary_min, salary_max, currency, category_id, posted_date, expiry_date, job_cover_img, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssiddsssss", $company_id, $title, $description, $requirements, $employment_type, 
                      $location, $coordinate_id, $salary_min, $salary_max, $currency, $category_id, $posted_date, $expiry_date, $job_cover_img);
    
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
