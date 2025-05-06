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

    // Insert into tbl_emp_info
    $stmt1 = $conn->prepare("INSERT INTO tbl_emp_info (firstName, lastName, emailAddress, gender, mobileNumber, address, birth_date, age, highest_edu, years_of_experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt1->bind_param("sssssssisi", $firstName, $lastName, $email, $gender, $mobileNumber, $address, $birthDate, $age, $highestEdu, $yearsOfExperience);

    // Insert into tbl_emp_login
    $stmt2 = $conn->prepare("INSERT INTO tbl_emp_login (user_id, emailAddress, password, salt) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("isss", $user_id, $email, $hashedPassword, $salt);

    if ($stmt1->execute()) {
        $user_id = $conn->insert_id;

        // Insert into tbl_emp_login
        if ($stmt2->execute()) {
            // Insert categories into tbl_emp_category_preferences
            if (!empty($jobCategories)) {
                $stmt3 = $conn->prepare("INSERT INTO tbl_emp_category_preferences (emp_id, category_id) VALUES (?, ?)");
                foreach ($jobCategories as $category_id) {
                    $stmt3->bind_param("ii", $user_id, $category_id);
                    if (!$stmt3->execute()) {
                        die("Error inserting category preference: " . $stmt3->error);
                    }
                }
                $stmt3->close();
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
