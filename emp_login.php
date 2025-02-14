<?php
require "includes/db_connect.php"; // Database connection

$loginMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emailAddress']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($email) || empty($password)) {
        $loginMessage = "All fields are required.";
    } else {
        // Retrieve user credentials from database
        $stmt = $conn->prepare("SELECT user_id, password, salt FROM tbl_loginuser WHERE emailAddress = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashedPassword, $salt);
            $stmt->fetch();

            // Hash the entered password with the retrieved salt
            if (password_verify($password . $salt, $hashedPassword)) {
                // Successful login
                session_start();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;

                header("Location: emp_dashboard.php"); // Redirect to a dashboard or home page
                exit();
            } else {
                $loginMessage = "Invalid password.";
            }
        } else {
            $loginMessage = "No account found with that email.";
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
    <title>Employee Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 50px; }
        form { background: white; padding: 20px; border-radius: 8px; width: 300px; margin: auto; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
        .message { color: red; margin-top: 10px; }
        .success { color: green; }
    </style>
</head>
<body>
    <h2>Employee Login</h2>
    
    <?php if (!empty($loginMessage)): ?>
        <p class="message"><?= htmlspecialchars($loginMessage) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="email" name="emailAddress" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p><a href="emp_update_pass.php">Forgot Password?</a></p>
</body>
</html>
