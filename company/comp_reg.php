<?php
session_start(); // Start the session to store user data

// Database connection
$conn = new mysqli("localhost", "root", "", "pesodb"); // Update with your database credentials
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic validation
    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email is already registered
        $stmt = $conn->prepare("SELECT id FROM tbl_comp_login WHERE emailAddress = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "This email is already registered.";
        } else {
            // Temporarily store email and password in session
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;

            // Redirect to the next page
            header("Location: comp_reg_complete.php");
            exit();
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
    <title>Register as an Employer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/comp_reg.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">
        <img src="../fortest/images/peso_icons.png" alt="PESO Logo" style="width: 120px; height: auto;">
        <div class="d-flex flex-column">
        <span style="color: white; font-size: 1.5rem; font-weight: bold;">PESO</span>
        <span style="color: white; font-size: 1.5rem; font-weight: bold; padding-left:30px;">for Company</span> <!-- Adjusted text beside logo -->
        </div>
    </div>
</nav>
<div class="container">
    <div class="card" id="register-container">
        <h3 class="p-3">Register as an employer</h3>
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label"></label>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label class="form-label"></label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="mb-3">
                <label class="form-label"></label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
            </div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-register">Register employer Account</button>
            <div class="mb-3">
                <span>or use your email for registration</span>
            </div>
        </form>
        <p class="text-muted text-grey">Already have an account? <a href="#" class="text-white" onclick="toggleForms()">Sign In</a></p>
    </div>
    <div class="container mt-4">
    <div class="card hidden" id="signin-container">
        <h3 class="text-center">Sign In</h3>
        <form>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-signin">Sign In</button>
        </form>
        <p class="text-muted text-grey">Don't have an account? <a href="#" class="text-white" onclick="toggleForms()">Register</a></p>
    </div>
</div>
<p class="Home"><a href="../index.php" id="register" class="Home text-center">Home</a></p>
  <p class="Home text-center">Copyright © 2025 Public Employment Service Office. All rights reserved.</p>

<script>
    function toggleForms() {
        var registerContainer = document.getElementById('register-container');
        var signinContainer = document.getElementById('signin-container');

        // Toggle visibility
        if (registerContainer.style.display === "none") {
            registerContainer.style.display = "block";
            signinContainer.style.display = "none";
        } else {
            registerContainer.style.display = "none";
            signinContainer.style.display = "block";
        }
    }

    // Ensure the correct one is shown on page load
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('register-container').style.display = "block"; 
        document.getElementById('signin-container').style.display = "none";  
    });
</script>
</body>
</html>
