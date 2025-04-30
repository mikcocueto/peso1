<?php
require "../db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = intval($_POST['job_id']);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $employment_type = $_POST['employment_type'];
    $location = $_POST['location'];
    $salary_min = $_POST['salary_min'];
    $salary_max = $_POST['salary_max'];
    $currency = $_POST['currency'];
    $category_id = intval($_POST['category_id']);
    $expiry_date = $_POST['expiry_date'];
    $job_cover_img = null;

    // Handle file upload
    if (isset($_FILES['job_photo']) && $_FILES['job_photo']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../../db/images/job_listing/';
        $file_tmp = $_FILES['job_photo']['tmp_name'];
        $file_name = $_FILES['job_photo']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validate file extension
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_ext, $allowed_extensions)) {
            echo json_encode(['error' => 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.']);
            exit();
        }

        // Generate a unique file name
        $random_string = bin2hex(random_bytes(8));
        $new_file_name = $random_string . '_' . time() . '.' . $file_ext;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
            $job_cover_img = $new_file_name; // Save only the file name
        } else {
            echo json_encode(['error' => 'Failed to upload the file.']);
            exit();
        }
    } elseif (isset($_FILES['job_photo']) && $_FILES['job_photo']['error'] != UPLOAD_ERR_NO_FILE) {
        echo json_encode(['error' => 'File upload error: ' . $_FILES['job_photo']['error']]);
        exit();
    }

    // Update query
    $query = "UPDATE tbl_job_listing 
              SET title = ?, description = ?, requirements = ?, employment_type = ?, location = ?, salary_min = ?, salary_max = ?, currency = ?, category_id = ?, expiry_date = ?";

    // Include job_cover_img in the query if a new image was uploaded
    if ($job_cover_img) {
        $query .= ", job_cover_img = ?";
    }

    $query .= " WHERE job_id = ?";

    $stmt = $conn->prepare($query);

    if ($job_cover_img) {
        $stmt->bind_param("sssssiisisis", $title, $description, $requirements, $employment_type, $location, $salary_min, $salary_max, $currency, $category_id, $expiry_date, $job_cover_img, $job_id);
    } else {
        $stmt->bind_param("sssssiisisi", $title, $description, $requirements, $employment_type, $location, $salary_min, $salary_max, $currency, $category_id, $expiry_date, $job_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Job details updated successfully']);
    } else {
        echo json_encode(['error' => 'Failed to update job details: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>
