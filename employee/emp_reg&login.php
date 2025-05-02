<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
  />
  <link rel="stylesheet" href="../assets/css/shared/styles.css" />
  <style>
    body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('../fortest/images/SPC_wide.jpg'); /* Ensure the correct file extension */
            background-size: cover;
            background-position: center;
            background-color: #e2e2e2; /* Fallback background color */
            flex-direction: column;
        }
    .Home{
      color: white;
      padding-top: 20px;
    }
  </style>
  <title>Login Page | Caged Coder</title>
</head>
<body>

  <div class="container" id="container">
    <!-- Log In Form --> 
    <div class="form-container sign-in">
      <form>
        <h1 style="padding: 20px 0;">Log In</h1>
        <input type="email" placeholder="Email" />
        <input type="password" placeholder="Password" />
        <a href="#">Forget Your Password?</a>
        <button>Sign In</button>
        <span>or use your email password</span>
        <div class="social-icons">
          <a href="../google_login.php" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a> <!-- Existing Facebook icon -->
          <a href="" class="icon"><i class="fa-solid fa-phone"></i></a> <!-- Added phone number icon -->
          <a href="https://github.com/login/oauth/authorize?client_id=Ov23liTtetwXNvGYQ8yx&redirect_uri=http://localhost/github-callback.php&scope=user:email" class="icon"><i class="fa-brands fa-github"></i></a>
        </div>
        <p>Don't have an account? <a href="#" id="register" class="highlight">Sign Up here</a></p>
        
      </form>
    </div>

    <!-- Sign Up Form -->
    <div class="form-container sign-up">
      <form action="emp_c.php" method="POST">
        <h1 style="padding: 20px 0;">Create Account</h1>
        <input type="email" placeholder="Email" />
        <input type="password" placeholder="Password" />
        <input type="password" placeholder="Confirm Password" />
        <button type="submit">Sign Up</button>
        <span>or use your email for registration</span>
        <div class="social-icons">
          <a href="../google_login.php" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a> <!-- Existing Facebook icon -->
          <a href="" class="icon"><i class="fa-solid fa-phone"></i></a> <!-- Added phone number icon -->
          <a href="https://github.com/login/oauth/authorize?client_id=Ov23liTtetwXNvGYQ8yx&redirect_uri=http://localhost/github-callback.php&scope=user:email" class="icon"><i class="fa-brands fa-github"></i></a>
        </div>
        <p>Already have an account? <a href="#" id="loginBtnSignUp" class="highlight">Sign In here</a></p>
      </form>
    </div>

    <!-- Toggle Panel -->
    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <img src="../assets/images/peso.png" alt="PESO Logo" style=" width: 150px; height: auto; margin-bottom: 20px;" >
          <h1>Hello,<br> San Pableños!</h1>
        </div>
        <div class="toggle-panel toggle-right">
        <img src="../assets/images/peso.png" alt="PESO Logo" style=" width: 150px; height: auto; margin-bottom: 20px;">
          <h1>Welcome Back,<br> San Pableños!</h1>
        </div>
      </div>
    </div>
  </div>
  <p class="Home"><a href="../index.php" id="register" class="Home">Home</a></p>
  <p class="Home text-center">Copyright © 2025 Public Employment Service Office. All rights reserved.</p>
  <script>
   document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("container");
    const registerBtn = document.getElementById("register");
    const loginBtnSignUp = document.getElementById("loginBtnSignUp");
    const signInForm = document.querySelector(".sign-in");
    const signUpForm = document.querySelector(".sign-up");

    function switchToSignUp() {
        if (window.innerWidth <= 767) {
            // For mobile: Hide sign-in and show sign-up
            signInForm.style.display = "none";
            signUpForm.style.display = "flex";
        } else {
            // For desktop: Apply original animation
            container.classList.add("active");
        }
    }

    function switchToSignIn() {
        if (window.innerWidth <= 767) {
            // For mobile: Hide sign-up and show sign-in
            signUpForm.style.display = "none";
            signInForm.style.display = "flex";
        } else {
            // For desktop: Apply original animation
            container.classList.remove("active");
        }
    }

    registerBtn.addEventListener("click", (e) => {
        e.preventDefault();
        switchToSignUp();
    });

    loginBtnSignUp.addEventListener("click", (e) => {
        e.preventDefault();
        switchToSignIn();
    });

    // Ensure login form is visible by default on mobile
    if (window.innerWidth <= 767) {
        switchToSignIn();
    }
});

  </script>
</body>
</html>
