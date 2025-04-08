<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register as an Employer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/comp_reg.css" />
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

  <div class="container mt-5">
    <!-- Register -->
    <div class="card" id="register-container">
      <h3 class="p-3">Register as an Employer</h3>
      <form>
        <div class="mb-3">
          <input type="email" class="form-control" placeholder="Email" required />
        </div>
        <div class="mb-3">
          <input type="password" class="form-control" placeholder="Password" required />
        </div>
        <div class="mb-3">
          <input type="password" class="form-control" placeholder="Confirm Password" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">Register Employer Account</button>
        <div class="text-center mt-3">
          <span>or use your email for registration</span>
          
        </div>
        <div class="text-center mt-3">
          <a href="../google_login.php" class="btn btn-danger">Register with Google</a>
        </div>
      </form>
      <p class="text-muted text-center mt-3">Already have an account? <a href="#" onclick="toggleForms()">Sign In</a></p>
    </div>

    <!-- Sign In -->
    <div class="card hidden mt-4" id="signin-container">
      <h3 class="p-3 text-center">Sign In</h3>
      <form method="POST" action="login-handler.php">
        <div class="mb-3">
          <input type="email" class="form-control" placeholder="Email" required />
        </div>
        <div class="mb-3">
          <input type="password" class="form-control" placeholder="Password" required />
        </div>
        <button type="submit" class="btn btn-success w-100">Sign In</button>
      </form>

      <hr class="my-4" />
      <div class="text-center">
        <p>Or sign in with:</p>
        <a href="../google_login.php">
          <img src="https://developers.google.com/identity/images/btn_google_signin_light_normal_web.png" alt="Sign in with Google">
        </a>
      </div>
      <p class="text-muted text-center mt-3">Don't have an account? <a href="#" onclick="toggleForms()">Register</a></p>
    </div>
  </div>

  <p class="text-center mt-5"><a href="../index.php">Home</a></p>
  <p class="text-center text-muted">Copyright © 2025 Public Employment Service Office.</p>

  <script>
    function toggleForms() {
      const reg = document.getElementById('register-container');
      const sign = document.getElementById('signin-container');
      if (reg.style.display === "none") {
        reg.style.display = "block";
        sign.style.display = "none";
      } else {
        reg.style.display = "none";
        sign.style.display = "block";
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      document.getElementById('register-container').style.display = "block";
      document.getElementById('signin-container').style.display = "none";
    });
  </script>
</body>
</html>
