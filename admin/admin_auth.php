<?php
session_start();
include "../includes/db_connect.php"; // Changed from config.php to db_connect.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailAddress = $_POST["emailAddress"];
    $password = $_POST["password"];

    // Debugging: Check the email address
    error_log("Email Address: " . $emailAddress);

    $stmt = $conn->prepare("SELECT admin_id, password FROM tbl_loginadmin WHERE emailAddress = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        header("Location: admin_login.php?error=Database error");
        exit();
    }

    $stmt->bind_param("s", $emailAddress);
    $stmt->execute();
    $stmt->store_result();

    // Debugging: Check the number of rows
    error_log("Number of rows: " . $stmt->num_rows);

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $stored_password);
        $stmt->fetch();

        // Debugging: Check the stored password
        error_log("Stored Password: " . $stored_password);

        if ($password === $stored_password) {
            // Debugging: Password verified
            error_log("Password verified successfully");
            $_SESSION["admin_id"] = $admin_id;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Debugging: Invalid password
            error_log("Invalid password");
            header("Location: admin_login.php?error=Invalid password");
            exit();
        }
    } else {
        // Debugging: Invalid email address
        error_log("Invalid email address");
        header("Location: admin_login.php?error=Invalid email address");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
