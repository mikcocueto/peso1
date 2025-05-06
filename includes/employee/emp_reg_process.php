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

    if (empty($email) || empty($password) || empty($firstName) || empty($lastName) || empty($gender) || empty($mobileNumber) || empty($address) || empty($birthDate) || empty($age)) {
        die("All fields are required.");
    }

    // Generate a salt and hash the password
    $salt = bin2hex(random_bytes(16));
    $hashedPassword = password_hash($password . $salt, PASSWORD_BCRYPT);

    // Insert into tbl_emp_info
    $stmt1 = $conn->prepare("INSERT INTO tbl_emp_info (firstName, lastName, emailAddress, gender, mobileNumber, address, birth_date, age) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt1->bind_param("sssssssi", $firstName, $lastName, $email, $gender, $mobileNumber, $address, $birthDate, $age);

    if ($stmt1->execute()) {
        $user_id = $conn->insert_id;

        // Insert into tbl_emp_login
        $stmt2 = $conn->prepare("INSERT INTO tbl_emp_login (user_id, emailAddress, password, salt) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("isss", $user_id, $email, $hashedPassword, $salt);

        if ($stmt2->execute()) {
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
