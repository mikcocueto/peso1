<?php
require "includes/db_connect.php"; // Ensure this file correctly connects to your database.

$registrationMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['emailAddress']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $registrationMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registrationMessage = "Invalid email format.";
    } else {
        // Check if email already exists in `tbl_loginuser`
        $checkStmt = $conn->prepare("SELECT id FROM tbl_loginuser WHERE emailAddress = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $registrationMessage = "Email is already registered.";
            $checkStmt->close();
        } else {
            $checkStmt->close();

            // Generate a salt and hash the password
            $salt = bin2hex(random_bytes(16)); // Generate a random 16-character salt
            $hashedPassword = password_hash($password . $salt, PASSWORD_BCRYPT);

            // Insert Employee Details into `tbl_employee`
            $stmt1 = $conn->prepare("INSERT INTO tbl_employee (firstName, lastName, emailAddress) VALUES (?, ?, ?)");
            $stmt1->bind_param("sss", $firstName, $lastName, $email);

            if ($stmt1->execute()) {
                // Get the last inserted user_id
                $user_id = $conn->insert_id;
                
                // Insert Login Credentials into `tbl_loginuser`
                $stmt2 = $conn->prepare("INSERT INTO tbl_loginuser (user_id, emailAddress, password, salt) VALUES (?, ?, ?, ?)");
                $stmt2->bind_param("isss", $user_id, $email, $hashedPassword, $salt);

                if ($stmt2->execute()) {
                    $registrationMessage = "Registration successful!";
                } else {
                    $registrationMessage = "Error inserting into login table: " . $stmt2->error;
                }
                $stmt2->close();
            } else {
                $registrationMessage = "Error inserting into employee table: " . $stmt1->error;
            }
            $stmt1->close();
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
    
    <?php if (!empty($registrationMessage)): ?>
        <p class="message <?= (strpos($registrationMessage, 'successful') !== false) ? 'success' : '' ?>">
            <?= htmlspecialchars($registrationMessage) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="firstName" placeholder="First Name" required>
        <input type="text" name="lastName" placeholder="Last Name" required>
        <input type="email" name="emailAddress" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
