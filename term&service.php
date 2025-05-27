<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Terms and Services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="includes/company/style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            margin: 0;
            padding: 0;
        }
        .terms-container {
            max-width: 900px;
            margin: 48px auto 48px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(108,99,255,0.10), 0 1.5px 6px rgba(0,0,0,0.04);
            padding: 48px 48px 40px 48px;
            position: relative;
            overflow: hidden;
        }
        .terms-container::before {
            content: "";
            position: absolute;
            top: -60px;
            right: -60px;
            width: 180px;
            height: 180px;
            background: linear-gradient(135deg, #6c63ff 60%, #b3baff 100%);
            opacity: 0.12;
            border-radius: 50%;
            z-index: 0;
        }
        .terms-container::after {
            content: "";
            position: absolute;
            bottom: -40px;
            left: -40px;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #6c63ff 60%, #b3baff 100%);
            opacity: 0.10;
            border-radius: 50%;
            z-index: 0;
        }
        .terms-content {
            position: relative;
            z-index: 1;
        }
        h1 {
            color: #6c63ff;
            margin-bottom: 24px;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        h2 {
            color: #333;
            margin-top: 36px;
            margin-bottom: 12px;
            font-size: 1.35rem;
            font-weight: 600;
        }
        p, li {
            color: #444;
            line-height: 1.8;
            font-size: 1.08rem;
        }
        ul {
            margin-left: 24px;
            margin-bottom: 0;
        }
        a {
            color: #6c63ff;
            text-decoration: underline;
            transition: color 0.2s;
        }
        a:hover {
            color: #4b47b5;
        }
        .divider {
            height: 2px;
            background: linear-gradient(90deg, #6c63ff 0%, #b3baff 100%);
            border: none;
            margin: 32px 0 24px 0;
            opacity: 0.18;
        }
        .last-updated {
            margin-top: 48px;
            color: #888;
            font-size: 1em;
            text-align: right;
            font-style: italic;
        }
        @media (max-width: 600px) {
            .terms-container {
                padding: 24px 10px 24px 10px;
            }
            h1 {
                font-size: 2rem;
            }
            h2 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="terms-container">
        <div class="terms-content">
            <h1>Terms and Services</h1>
            <p>Welcome to our Public Employment Service Office (PESO) web portal. By accessing or using this website, you agree to comply with and be bound by the following terms and conditions. Please read them carefully.</p>
            
            <hr class="divider">

            <h2>1. Acceptance of Terms</h2>
            <p>By using this site, you acknowledge that you have read, understood, and agree to be bound by these Terms and Services and all applicable laws and regulations.</p>
            
            <h2>2. User Responsibilities</h2>
            <ul>
                <li>You agree to provide accurate, current, and complete information during registration and when using any part of the site.</li>
                <li>You are responsible for maintaining the confidentiality of your account and password.</li>
                <li>You agree not to use the site for any unlawful or prohibited activities.</li>
            </ul>
            
            <h2>3. Privacy Policy</h2>
            <p>Your privacy is important to us. Please review our <a href="privacy_policy.php">Privacy Policy</a> to understand how we collect, use, and safeguard your information.</p>
            
            <h2>4. Intellectual Property</h2>
            <ul>
                <li>All content, trademarks, logos, and data on this site are the property of their respective owners and protected by applicable copyright and intellectual property laws.</li>
                <li>You may not reproduce, distribute, or create derivative works from any content on this site without explicit permission.</li>
            </ul>
            
            <h2>5. Limitation of Liability</h2>
            <p>PESO and its affiliates are not liable for any direct, indirect, incidental, or consequential damages arising from your use of this site or any linked external sites.</p>
            
            <h2>6. Account Termination</h2>
            <p>We reserve the right to suspend or terminate your account at any time if you violate these terms or engage in any activity that may harm the site or its users.</p>
            
            <h2>7. Changes to Terms</h2>
            <p>We may update these Terms and Services from time to time. Continued use of the site after changes constitutes your acceptance of the new terms.</p>
            
            <h2>8. Contact Us</h2>
            <p>If you have any questions about these Terms and Services, please contact us at <a href="mailto:support@peso.com">support@peso.com</a>.</p>
            
            <div class="last-updated">Last updated: <?php echo date('F d, Y'); ?></div>
        </div>
    </div>
</body>
</html>