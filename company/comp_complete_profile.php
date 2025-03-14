<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Account</title>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: Arial, sans-serif;
            background-image: url('../fortest/images/create.png'); /* Added background image */
            background-size: cover; /* Ensure the image covers the container */
            background-position: center; /* Center the image */
            position: relative; /* Added for overlay */
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Added overlay with 50% opacity */
            z-index: -1; /* Ensure it is behind the content */
        }
        .header {
            background-color: #6267FF;
            color: white;
            border-radius: 5px;
            padding: 5px;
            display: flex;
            justify-content: flex-start; /* Changed to flex-start */
            align-items: center;
        }
        .header img {
            height: 50px;
            margin-right: 10px; /* Added margin-right */
        }
        .header .brand-text {
            display: flex;
            flex-direction: column;
        }
        .header .brand-text span {
            font-size: 24px;
            font-weight: bold;
        }
        .header .brand-text .sub-text {
            padding-left: 30px;
        }
        .container {
            display: flex;
            justify-content: center;
            margin-top: 32px;
        }
        .form-container {
            background-color: #6267FF;
            color: white;
            padding: 32px;
            border-radius: 8px;
            width: 100%;
            max-width: 600px;
           
        }
        .form-container h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 16px;
        }
        .form-container p {
            margin-bottom: 16px;
        }
        .form-container h3 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .form-container h4 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .form-container form {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .form-container form .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-container form .form-row {
            display: flex;
            gap: 16px;
        }
        .form-container form .form-row .form-group {
            flex: 1;
        }
        .form-container form label {
            margin-bottom: 4px;
        }
        .form-container form input,
        .form-container form select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            color: black;
        }
        .form-container form input::placeholder {
            opacity: 0.6; /* Adjusted opacity to make the placeholder text faded */
        }
        .form-container form .phone-group {
            display: flex;
        }
        .form-container form .phone-group select {
            border-radius: 4px 0 0 4px;
        }
        .form-container form .phone-group input {
            border-radius: 0 4px 4px 0;
            flex: 1;
        }
        .form-container form button {
            background-color: black;
            color: white;
            padding: 8px; /* Adjusted padding to make the button smaller */
            border-radius: 4px;
            border: none;
            cursor: pointer;
            width: 150px; /* Adjusted width to make the button smaller */
            margin: 0 auto; /* Center the button */
            display: block; /* Center the button */
        }
        .footer {
            display: flex;
            justify-content: flex-start; /* Align footer to the left */
            margin-top: 16px;
            padding-left: 16px; /* Add padding to the left */
        }
        .footer p {
            color: white
        }
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px;
            }
            .header img {
                height: 40px;
                margin-bottom: 10px;
            }
            .header .brand-text span {
                font-size: 20px;
            }
            .header .brand-text .sub-text {
                padding-left: 0;
            }
            .container {
                margin-top: 16px;
                padding: 0 10px;
            }
            .form-container {
                padding: 16px;
                background-color: rgba(98, 103, 255, 0.9); /* Adjusted background color for better readability */
            }
            .form-container h2 {
                font-size: 20px;
            }
            .form-container h3, .form-container h4 {
                font-size: 18px;
            }
            .form-container form {
                gap: 8px;
            }
            .form-container form .form-row {
                flex-direction: column;
            }
            .form-container form .form-row .form-group {
                width: 100%;
            }
            .form-container form button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php" class="d-flex align-items-center text-decoration-none">
            <img src="../fortest/images/peso_icons.png" alt="PESO Logo" style="width: 120px; height: auto; margin-right: 10px;">
        </a>
        <div class="brand-text">
            <span>PESO</span>
            <span class="sub-text">for Company</span>
        </div>
    </div>
    <div class="container">
        <div class="form-container">
            <h2>Your Employer account</h2>
            <p>You're almost done! We need some details about your business to verify your account. We won't share your details with anyone.</p>

            <h3>Your Details</h3>
            <p>We need a real name to verify your account</p>

            <h4>Email</h4>
            <p>Exampleaccount@gmail.com</p>
            
            <form>
                <div class="form-row">
                    <div class="form-group">
                        <label for="given-name">Given name</label>
                        <input type="text" id="given-name"  placeholder="Juan" required>
                    </div>
                    <div class="form-group">
                        <label for="family-name">Family name</label>
                        <input type="text" id="family-name"  placeholder="Dela Cruz" required>
                    </div>
                </div>
                <h4>Business Details</h4>
                <p><strong>Business Name</strong> <br>
                We need your registered business name to verify your account.</p>
                <div class="form-group">
                    <label for="business-name">Business name</label>
                    <input type="text" id="business-name"  placeholder="Your registered business name" required>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <select id="country">
                        <option>Philippines</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone-number">Phone Number</label>
                    <div class="phone-group">
                        <select>
                            <option>Philippines (+63)</option>
                        </select>
                        <input type="text" id="phone-number"  placeholder="+63 9123456789" required>
                    </div>
                </div>
                <button type="submit">Create new account</button>
            </form>
        </div>
    </div>
    <div class="footer">
        <p>Looking for a job? Visit PESO Job Listing here</p>
    </div>
</body>
</html>
