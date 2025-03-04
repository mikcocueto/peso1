<?php
require "../../includes/db_connect.php"; // Database connection

session_start();
$updateMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $oldPassword = trim($_POST['oldPassword']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Validate input
    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['error_message'] = "All fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $_SESSION['error_message'] = "New passwords do not match.";
    } else {
        // Retrieve the current password and salt
        $stmt = $conn->prepare("SELECT password, salt FROM tbl_loginuser WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword, $salt);
            $stmt->fetch();

            // Verify the old password
            if (password_verify($oldPassword . $salt, $hashedPassword)) {
                // Generate a new salt and hash the new password
                $newSalt = bin2hex(random_bytes(16));
                $newHashedPassword = password_hash($newPassword . $newSalt, PASSWORD_DEFAULT);

                // Update the password in the database
                $updateStmt = $conn->prepare("UPDATE tbl_loginuser SET password = ?, salt = ? WHERE user_id = ?");
                $updateStmt->bind_param("ssi", $newHashedPassword, $newSalt, $user_id);

                if ($updateStmt->execute()) {
                    $_SESSION['success_message'] = "Password updated successfully.";
                } else {
                    $_SESSION['error_message'] = "Error updating password.";
                }
                $updateStmt->close();
            } else {
                $_SESSION['error_message'] = "Old password is incorrect.";
            }
        } else {
            $_SESSION['error_message'] = "User not found.";
        }
        $stmt->close();
    }
}
// Close the connection
$conn->close();

header("Location: ../../employee/emp_dashboard.php");
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ...existing code... -->
</head>
<body>
    <!-- ...existing code... -->
    <?php if (!empty($updateMessage)): ?>
        <p class="message"><?= htmlspecialchars($updateMessage) ?></p>
    <?php endif; ?>
    <!-- ...existing code... -->
</body>
</html>
