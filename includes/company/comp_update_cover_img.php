<?php
require "../db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = intval($_POST['job_id']);
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
    } else {
        echo json_encode(['error' => 'No file uploaded or file upload error.']);
        exit();
    }

    // Update the job_cover_img in the database
    $query = "UPDATE tbl_job_listing SET job_cover_img = ? WHERE job_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $job_cover_img, $job_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Cover image updated successfully']);
    } else {
        echo json_encode(['error' => 'Failed to update cover image: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>
