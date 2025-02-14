<?php
require "includes/db_connect.php"; // Database connection

$registerMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emailAddress']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Validate input
    if (empty($email) || empty($password) || empty($confirmPassword)) {
        $registerMessage = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $registerMessage = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT user_id FROM tbl_loginuser WHERE emailAddress = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $registerMessage = "Email already registered.";
        } else {
            // Generate a salt and hash the password
            $salt = bin2hex(random_bytes(16));
            $hashedPassword = password_hash($password . $salt, PASSWORD_DEFAULT);

            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO tbl_loginuser (emailAddress, password, salt) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $hashedPassword, $salt);
            if ($stmt->execute()) {
                $registerMessage = "Registration successful. You can now log in.";
            } else {
                $registerMessage = "Registration failed. Please try again.";
            }
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
    <title>Employee Registration</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 50px; }
        form { background: white; padding: 20px; border-radius: 8px; width: 300px; margin: auto; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
        .message { color: red; margin-top: 10px; }
        .success { color: green; }
    </style>
</head>
<body>
    <h2>Employee Registration</h2>
    
    <?php if (!empty($registerMessage)): ?>
        <p class="message"><?= htmlspecialchars($registerMessage) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="email" name="emailAddress" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
