<?php require "includes/db_connect.php"; ?>
<?php require "includes/nav.php"; ?>
<?php
// START SESSION
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emailAddress']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        // FETCH COMPANY LOGIN DETAILS
        $stmt = $conn->prepare("SELECT company_id, password, salt FROM tbl_logincompany WHERE emailAddress = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($company_id, $hashedPassword, $salt);
            $stmt->fetch();

            // CHECK PASSWORD
            if (password_verify($password . $salt, $hashedPassword)) {
                // LOGIN SUCCESSFUL - SET SESSION
                $_SESSION['company_id'] = $company_id;
                $_SESSION['email'] = $email;
                echo "Login successful! Redirecting...";
                header("refresh:2; url=dashboard.php"); // Redirect to dashboard
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "Company not found.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Login</title>
</head>
<body>
    <h2>Company Login</h2>
    <form method="POST" action="">
        <input type="email" name="emailAddress" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
