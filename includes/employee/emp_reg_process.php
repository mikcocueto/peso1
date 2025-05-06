<?php
session_start();
require "../db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['temp_email'] ?? '';
    $password = $_SESSION['temp_password'] ?? '';
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $gender = trim($_POST['gender']);
    $mobileNumber = trim($_POST['mobile_number']);
    $address = trim($_POST['address']);
    $birthDate = trim($_POST['dob']);
    $age = trim($_POST['age']);
    $highestEdu = trim($_POST['education']);
    $yearsOfExperience = trim($_POST['experience']);
    $jobCategories = isset($_POST['job_category']) ? explode(", ", $_POST['job_category']) : [];

    // Check for missing fields
    $missingFields = [];
    if (empty($email)) $missingFields[] = "Email";
    if (empty($password)) $missingFields[] = "Password";
    if (empty($firstName)) $missingFields[] = "First Name";
    if (empty($lastName)) $missingFields[] = "Last Name";
    if (empty($gender)) $missingFields[] = "Gender";
    if (empty($mobileNumber)) $missingFields[] = "Mobile Number";
    if (empty($address)) $missingFields[] = "Address";
    if (empty($birthDate)) $missingFields[] = "Date of Birth";
    if (empty($age)) $missingFields[] = "Age";
    if (empty($highestEdu)) $missingFields[] = "Highest Education";
    if (empty($yearsOfExperience)) $missingFields[] = "Years of Experience";

    if (!empty($missingFields)) {
        die("The following fields are missing: " . implode(", ", $missingFields));
    }

    // Generate a salt and hash the password
    $salt = bin2hex(random_bytes(16));
    $hashedPassword = password_hash($password . $salt, PASSWORD_BCRYPT);

    // Handle resume upload
    $resume_file_name = null;
    $resume_dir = '../../db/pdf/emp_cv/'; // Updated directory
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['resume'];
        $original_name = basename($file['name']);
        $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
        $random_prefix = bin2hex(random_bytes(8));
        $resume_file_name = $random_prefix . '_' . $original_name;
        $target_file = $resume_dir . $resume_file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Ensure the upload directory exists
        if (!is_dir($resume_dir)) {
            if (!mkdir($resume_dir, 0777, true)) {
                die("Error: Failed to create the upload directory.");
            }
        }

        // Check if file is a PDF
        if ($file_type != 'pdf') {
            die("Error: Only PDF files are allowed for the resume.");
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            die("Error: Resume file already exists.");
        }

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($file['tmp_name'], $target_file)) {
            die("Error: Failed to upload the resume.");
        }
    } else {
        die("Error: Resume file is required.");
    }

    // Insert into tbl_emp_info
    $stmt1 = $conn->prepare("INSERT INTO tbl_emp_info (firstName, lastName, emailAddress, gender, mobileNumber, address, birth_date, age, highest_edu, years_of_experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt1->bind_param("sssssssisi", $firstName, $lastName, $email, $gender, $mobileNumber, $address, $birthDate, $age, $highestEdu, $yearsOfExperience);

    if ($stmt1->execute()) {
        $user_id = $conn->insert_id;

        // Insert into tbl_emp_login
        $stmt2 = $conn->prepare("INSERT INTO tbl_emp_login (user_id, emailAddress, password, salt) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("isss", $user_id, $email, $hashedPassword, $salt);

        if ($stmt2->execute()) {
            // Insert resume details into tbl_emp_cv
            $stmt3 = $conn->prepare("INSERT INTO tbl_emp_cv (emp_id, cv_file_name, cv_name, cv_dir, upload_timestamp) VALUES (?, ?, ?, ?, NOW())");
            $cv_name = "Resume"; // Default name for the resume
            $stmt3->bind_param("isss", $user_id, $resume_file_name, $cv_name, $resume_dir);
            if (!$stmt3->execute()) {
                die("Error inserting resume details: " . $stmt3->error);
            }

            // Insert categories into tbl_emp_category_preferences
            if (!empty($jobCategories)) {
                $stmt4 = $conn->prepare("INSERT INTO tbl_emp_category_preferences (emp_id, category_id) VALUES (?, ?)");
                foreach ($jobCategories as $category_id) {
                    $stmt4->bind_param("ii", $user_id, $category_id);
                    if (!$stmt4->execute()) {
                        die("Error inserting category preference: " . $stmt4->error);
                    }
                }
                $stmt4->close();
            }

            unset($_SESSION['temp_email'], $_SESSION['temp_password']);
            echo "<script>
                    alert('Registration successful! Redirecting to login page...');
                    setTimeout(() => {
                        window.location.href = '../../employee/emp_reg&login.php';
                    }, 2000);
                  </script>";
            exit();
        } else {
            die("Error inserting into tbl_emp_login: " . $stmt2->error);
        }
    } else {
        die("Error inserting into tbl_emp_info: " . $stmt1->error);
    }
}
?>
