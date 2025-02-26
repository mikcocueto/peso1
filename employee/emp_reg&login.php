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
    <title>Login Page | Caged coder</title>
  </head>
  <body>
    <div class="container" id="container">
      <div class="form-container sign-up">
        <form>
          <h1 style="padding: 20px 0;">Create Account</h1>
          <input type="email" placeholder="Email" />
          <input type="password" placeholder="Password" />
          <button>Sign Up</button>
          <span>or use your email for registration</span>
          <div class="social-icons">
            <a href="#" class="icon"
              ><i class="fa-brands fa-google-plus-g"></i
            ></a>
            <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
          </div>
          <p>Don't you have an account? <a href="#" id="login" class="highlight">Sign In here</a></p>
        </form>
      </div>
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
          <p>Already have an account? <a href="#" id="register" class="highlight">Sign Up here</a></p>
        </form>
      </div>
      <div class="toggle-container">
        <div class="toggle">
          <div class="toggle-panel toggle-left">
            <h1>Welcome Back, San Pableños!</h1>
          </div>
          <div class="toggle-panel toggle-right">
            <h1>Hello,<br> San Pableños!</h1>
            
          </div>
        </div>
      </div>
    </div>

    <script src="script.js"></script>
    <script>
      const container = document.getElementById("container");
      const registerBtn = document.getElementById("register");
      const loginBtn = document.getElementById("login");

      registerBtn.addEventListener("click", () => {
        container.classList.add("active");
      });

      loginBtn.addEventListener("click", () => {
        container.classList.remove("active");
      });
    </script>
  </body>
</html>
