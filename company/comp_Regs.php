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
            background-color:rgba(201, 214, 255, 0.8);
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            flex-direction: column;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: transparent;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .container {
            max-width: 500px;
            margin-top: 80px; /* Adjust to avoid overlap with navbar */
            
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
    </style>
</head>
<body>
<nav class="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="logo.png" alt="Logo" width="40" height="40" class="d-inline-block align-top">
            <strong>PESO for Company</strong>
        </a>
    </div>
</nav>
<div class="container">
    <div class="card">
        <h3>Register as an employer</h3>
        <form>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-register">Register employer Account</button>
        </form>
        <p class="text-muted">Already have an account? <a href="#" class="text-white">Sign In</a></p>
    </div>
    <div class="container mt-4">
    <div class="card">
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
        <p class="text-muted">Don't have an account? <a href="#" class="text-white">Register</a></p>
    </div>
</div>
</body>
</html>
