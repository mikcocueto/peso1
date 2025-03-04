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
          <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
        <p>Don't have an account? <a href="#" id="register" class="highlight">Sign Up here</a></p>
      </form>
    </div>

    <!-- Sign Up Form -->
    <div class="form-container sign-up">
      <form>
        <h1 style="padding: 20px 0;">Create Account</h1>
        <input type="email" placeholder="Email" />
        <input type="password" placeholder="Password" />
        <input type="password" placeholder="Confirm Password" />
        <button>Sign Up</button>
        <span>or use your email for registration</span>
        <div class="social-icons">
          <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
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

  <scrijs"></script>
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
