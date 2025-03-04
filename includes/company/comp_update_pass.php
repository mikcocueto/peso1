<?php
require "db_connect.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emailAddress']);
    $new_password = trim($_POST['new_password']);
    $confirm_new_password = trim($_POST['confirm_new_password']);

    // Basic validation
    if (empty($email) || empty($new_password) || empty($confirm_new_password)) {
        $updateMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $updateMessage = "Invalid email format.";
    } elseif ($new_password !== $confirm_new_password) {
        $updateMessage = "Passwords do not match.";
    } else {
        // Check if email exists in `tbl_logincompany`
        $checkStmt = $conn->prepare("SELECT id FROM tbl_logincompany WHERE emailAddress = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $checkStmt->close();

            // Generate a new salt and hash the new password
            $salt = bin2hex(random_bytes(16)); // Generate a random 16-character salt
            $hashedPassword = password_hash($new_password . $salt, PASSWORD_BCRYPT);

            // Update the password in `tbl_logincompany`
            $stmt = $conn->prepare("UPDATE tbl_logincompany SET password = ?, salt = ? WHERE emailAddress = ?");
            $stmt->bind_param("sss", $hashedPassword, $salt, $email);

            if ($stmt->execute()) {
                $updateMessage = "Password updated successfully!";
                header("Location: ../../company/comp_login.php");
                exit();
            } else {
                $updateMessage = "Error updating password: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $updateMessage = "No account found with that email.";
            $checkStmt->close();
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
</head>
<body>
    <h2>Update Password</h2>
    <?php if (isset($updateMessage)): ?>
        <p><?php echo $updateMessage; ?></p>
    <?php endif; ?>
</body>
</html>
