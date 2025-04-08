<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as an Employer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('../fortest/images/SPC_old_city_hall.jpg'); /* Ensure the correct file extension */
            background-size: cover;
            background-position: center;
            background-color: #e2e2e2; /* Fallback background color */
            flex-direction: column;
        }
        .navbar { 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            /*background-color: #6267FF; /* Changed background color to blue */
            padding: 10px;
            /*box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);*/
            z-index: 1000;
            display: flex; 
            align-items: center; /* Align items vertically */
        }
        .navbar .navbar-brand {
            display: flex;
            align-items: center; /* Align items vertically */
        }
        .navbar .navbar-brand img {
            margin-right: 10px; /* Add space beside logo */
        }
        .navbar span {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Add shadow */
        }
        .container {
            max-width: 500px;
            margin-top: 50px; /* Adjusted to avoid overlap with navbar and logo */
        }
        @media (max-width: 768px) {
            .container {
                margin-top: 150px; /* Further adjust margin for smaller screens */
                padding: 0 20px; /* Add padding to avoid edge touching */
            }
            .navbar {
                flex-direction: row; /* Keep navbar items in a row on small screens */
                justify-content: center; /* Center items horizontally */
                align-items: center; /* Center items vertically */
                text-align: center; /* Center text on small screens */
            }
            .navbar img {
                width: 80px; /* Adjust logo size for small screens */
                margin-right: 10px; /* Add space beside logo */
            }
            .navbar span {
                font-size: 1.2rem; /* Adjust text size for small screens */
                margin-right: 0; /* Remove right margin */
            }
        }
        .card {
            background-color: #5c6bc0;
            color: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-register, .btn-signin {
            background-color: black;
            color: white;
            border-radius: 5px;
            width: 100%;
            padding: 10px;
            font-weight: bold;
        }
        .text-muted {
            font color:white;
            text-align: left;
            font-size: 0.9rem;
            margin-top: 10px;
        }
        .form-label {
            text-align: left;
            display: block;
            
        }
        .hidden {
            display: none;
        }
        .Home{
        color: white;
        text-align: center;
        }
    </style>
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
