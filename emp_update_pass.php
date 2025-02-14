<?php
require "includes/db_connect.php"; // Database connection
require "includes/nav.php";
$updateMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emailAddress']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Validate input
    if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
        $updateMessage = "All fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $updateMessage = "Passwords do not match.";
    } else {
        // Check if the email exists
        $stmt = $conn->prepare("SELECT user_id FROM tbl_loginuser WHERE emailAddress = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Generate a new salt and hash the new password
            $salt = bin2hex(random_bytes(16));
            $hashedPassword = password_hash($newPassword . $salt, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateStmt = $conn->prepare("UPDATE tbl_loginuser SET password = ?, salt = ? WHERE user_id = ?");
            $updateStmt->bind_param("ssi", $hashedPassword, $salt, $user_id);

            if ($updateStmt->execute()) {
                $updateMessage = "Password updated successfully.";
            } else {
                $updateMessage = "Error updating password.";
            }
            $updateStmt->close();
        } else {
            $updateMessage = "No account found with that email.";
        }
        $stmt->close();
    }
}
// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 50px; }
        form { background: white; padding: 20px; border-radius: 8px; width: 300px; margin: auto; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
        .message { color: red; margin-top: 10px; }
        .success { color: green; }
    </style>
</head>
<body>
    <h2>Update Password</h2>
    
    <?php if (!empty($updateMessage)): ?>
        <p class="message"><?= htmlspecialchars($updateMessage) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="email" name="emailAddress" placeholder="Email Address" required>
        <input type="password" name="newPassword" placeholder="New Password" required>
        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
        <button type="submit">Update Password</button>
    </form>
</body>
</html>
