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
        <form>
            <div class="mb-3">
                <label class="form-label"></label>
                <input type="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label class="form-label"></label>
                <input type="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="mb-3">
                <label class="form-label"></label>
                <input type="password" class="form-control" placeholder="Password" required>
            </div>
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
