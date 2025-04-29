<?php
session_start(); // Start the session to store user data

// Database connection
$conn = new mysqli("localhost", "root", "", "pesodb"); // Update with your database credentials
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin'])) {
    $email = trim($_POST['emailAddress']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($email) || empty($password)) {
        $signin_error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signin_error = "Invalid email format.";
    } else {
        // Retrieve company credentials from database
        $stmt = $conn->prepare("SELECT company_id, password, salt FROM tbl_comp_login WHERE emailAddress = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($company_id, $hashedPassword, $salt);
            $stmt->fetch();

            // Hash the entered password with the retrieved salt
            if (password_verify($password . $salt, $hashedPassword)) {
                // Successful login
                $_SESSION['company_id'] = $company_id;
                $_SESSION['email'] = $email;

                header("Location: comp_dashboard.php"); // Redirect to a dashboard or home page
                exit();
            } else {
                $signin_error = "Invalid password.";
            }
        } else {
            $signin_error = "No account found with that email.";
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
            <div class="mb-3 position-relative">
                <label class="form-label"></label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <span class="position-absolute top-50 end-0 translate-middle-y pe-3" onclick="togglePasswordVisibility('password', this)" style="cursor: pointer;">
                    <img src="../fortest/images/hide.png" alt="Toggle Password" id="password-eye" style="width: 20px; filter: grayscale(100%); opacity: 0.6;">
                </span>
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label"></label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
                <span class="position-absolute top-50 end-0 translate-middle-y pe-3" onclick="togglePasswordVisibility('confirm_password', this)" style="cursor: pointer;">
                    <img src="../fortest/images/hide.png" alt="Toggle Password" id="confirm-password-eye" style="width: 20px; filter: grayscale(100%); opacity: 0.6;">
                </span>
            </div>
            <div class="mb-3" style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox">
                <p style="margin: 0; padding-bottom: 2px; font-size: 0.9rem; ">I agree to the <a href="">Terms & Conditions</a></p>
            </div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <button type="submit" name="register" class="btn btn-register">Register employer Account</button>
            <div class="mb-3 d-flex align-items-center">
                <hr class="flex-grow-1 text-grey">
                <span class="mx-2 text-muted">or</span>
                <hr class="flex-grow-1 text-grey">
            </div>
            <div class="mt-2" style="display: flex; justify-content: center; gap: 10px;">
                <a href="../google_login.php" class="btn btn-light d-flex align-items-center justify-content-center" style="border: 1px solid #ddd; width: 40px; height: 40px; border-radius: 50%;">
                    <img src="../fortest/images/google_icon.png" alt="Google Icon" style="width: 20px; height: 20px;">
                </a>
                <a href="../facebook_login.php" class="btn btn-light d-flex align-items-center justify-content-center" style="border: 1px solid #ddd; width: 40px; height: 40px; border-radius: 50%;">
                    <img src="../fortest/images/facebook_icon.png" alt="Facebook Icon" style="width: 20px; height: 20px;">
                </a>
                <a href="../github_login.php" class="btn btn-light d-flex align-items-center justify-content-center" style="border: 1px solid #ddd; width: 40px; height: 40px; border-radius: 50%;">
                    <img src="../fortest/images/github-icon.png" alt="GitHub Icon" style="width: 20px; height: 20px;">
                </a>
            </div>
        </form>
        <p class="text-muted text-blue text-center">Already have an account? <a href="#" class="text-blue" onclick="toggleForms()">Log In</a></p>
    </div>
    <div class="container mt-4">
    <div class="card hidden" id="signin-container">
        <h3 class="text-center">Log In</h3>
        <form method="POST">
            <div class="mb-3">
                <input type="email" name="emailAddress" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" name="password" id="signin_password" class="form-control" placeholder="Password" required>
                <span class="position-absolute top-50 end-0 translate-middle-y pe-3" onclick="togglePasswordVisibility('signin_password', this)" style="cursor: pointer;">
                    <img src="../fortest/images/hide.png" alt="Toggle Password" id="signin-password-eye" style="width: 20px; filter: grayscale(100%); opacity: 0.6;">
                </span>
            </div>
            <div class="mb-3">
                <a href="../forgot_password.php" class="text-blue d-block text-start" style="text-decoration: underline; font-size: 0.9rem;">Forgot Password?</a>
            </div>
            <?php if (!empty($signin_error)): ?>
                <div class="alert alert-danger"><?php echo $signin_error; ?></div>
            <?php endif; ?>
            <button type="submit" name="signin" class="btn btn-signin">Sign In</button>
            <div class="mb-3">
                <hr class="text-grey">
            </div>
            <div class="mt-2" style="display: flex; justify-content: center; gap: 10px;">
                <a href="../google_login.php" class="btn btn-light  justify-content-center" style="border: 1px solid #ddd; width: 40px; height: 40px; border-radius: 50%;">
                    <img src="../fortest/images/google_icon.png" alt="Google Icon" style="width: 20px; height: 20px;">
                </a>
                <a href="../facebook_login.php" class="btn btn-light  justify-content-center" style="border: 1px solid #ddd; width: 40px; height: 40px; border-radius: 50%;">
                    <img src="../fortest/images/facebook_icon.png" alt="Facebook Icon" style="width: 20px; height: 20px;">
                </a>
                <a href="../github_login.php" class="btn btn-light  justify-content-center" style="border: 1px solid #ddd; width: 40px; height: 40px; border-radius: 50%;">
                    <img src="../fortest/images/github-icon.png" alt="GitHub Icon" style="width: 20px; height: 20px;">
                </a>
            </div>
        </form>
        <p class="text-muted text-blue text-center">Don't have an account? <a href="#" class="text-blue" onclick="toggleForms()">Register</a></p>
    </div>
</div>
<p class="Home"><a href="../index.php" id="register" class="Home text-center">Home</a></p>
  <p class="Home text-center">Copyright © 2025 Public Employment Service Office. All rights reserved.</p>

<script>
    function togglePasswordVisibility(fieldId, iconElement) {
        const passwordField = document.getElementById(fieldId);
        const eyeIcon = iconElement.querySelector('img');
        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.src = "../fortest/images/view.png"; // Change to open eye icon
        } else {
            passwordField.type = "password";
            eyeIcon.src = "../fortest/images/hide.png"; // Change to closed eye icon
        }
    }

    function toggleForms() {
        var registerContainer = document.getElementById('register-container');
        var signinContainer = document.getElementById('signin-container');

        // Toggle visibility
        if (registerContainer.style.display === "none" || registerContainer.style.display === "") {
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
