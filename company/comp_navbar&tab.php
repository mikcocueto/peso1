<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/db_connect.php'; // Use __DIR__ for an absolute path relative to this file

// Check if the user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    header("Location: comp_login.php");
    exit();
}

$company_id = $_SESSION['company_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="../includes/company/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Company Dashboard Navbar -->
    <nav class="c_dash_navbar">
        <div class="c_dash_navbar-brand">
            <img src="../fortest/images/peso_icons.png" alt="PESO Logo">
            <div>
                <span class="navbar-title-large" style="font-size: 1.5rem; font-weight: bold; color: white;">Public Employment Service Office</span>
                <span class="navbar-title-large" style="font-size: 1.5rem; font-weight: bold; padding-left: px; color: white;"> for Company</span>
                <span class="navbar-title-small d-md-none" style="font-size: 1.5rem; font-weight: bold; color: white;">PESO for Company</span>
            </div>
        </div>
        <div class="c_dash_navbar-icons">
            <span id="currentTime" class="current-time" style="color: white; margin-right: 20px;"></span>
            <div class="notification-dropdown">
                <i class="bx bx-bell" onclick="toggleNotification()"></i>
                <span class="notification-badge">3</span>
                <div class="notification-content" id="notificationContent">
                    <div class="notification-header">
                        <h5>Notifications</h5>
                        <button class="mark-all-read">Mark all as read</button>
                    </div>
                    <div class="notification-list">
                        <div class="notification-item unread">
                            <div class="notification-icon">
                                <i class="bx bx-user-plus"></i>
                            </div>
                            <div class="notification-details">
                                <p>New application received for Software Engineer position</p>
                                <span class="notification-time">2 minutes ago</span>
                            </div>
                        </div>
                        <div class="notification-item unread">
                            <div class="notification-icon">
                                <i class="bx bx-check-circle"></i>
                            </div>
                            <div class="notification-details">
                                <p>Your job posting has been approved</p>
                                <span class="notification-time">1 hour ago</span>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon">
                                <i class="bx bx-info-circle"></i>
                            </div>
                            <div class="notification-details">
                                <p>Your company profile has been updated</p>
                                <span class="notification-time">2 hours ago</span>
                            </div>
                        </div>
                    </div>
                    <div class="notification-footer">
                        <a href="#" class="view-all">View all notifications</a>
                    </div>
                </div>
            </div>
            <div class="message-dropdown">
                <i class="bx bx-chat" onclick="toggleMessage()"></i>
                <span class="message-badge">2</span>
                <div class="message-content" id="messageContent">
                    <div class="message-header">
                        <h5>Messages</h5>
                        <button class="mark-all-read">Mark all as read</button>
                    </div>
                    <div class="message-list">
                        <div class="message-item unread">
                            <div class="message-avatar">
                                <img src="../fortest/images/person_2.jpg" alt="User Avatar">
                            </div>
                            <div class="message-details">
                                <div class="message-sender">John Doe</div>
                                <p>Thank you for considering my application...</p>
                                <span class="message-time">2 minutes ago</span>
                            </div>
                        </div>
                        <div class="message-item unread">
                            <div class="message-avatar">
                                <img src="../fortest/images/person_1.jpg" alt="User Avatar">
                            </div>
                            <div class="message-details">
                                <div class="message-sender">Jane Smith</div>
                                <p>I would like to schedule an interview...</p>
                                <span class="message-time">1 hour ago</span>
                            </div>
                        </div>
                        <div class="message-item">
                            <div class="message-avatar">
                                <img src="../fortest/images/person_3.jpg" alt="User Avatar">
                            </div>
                            <div class="message-details">
                                <div class="message-sender">Mike Johnson</div>
                                <p>Thank you for the opportunity...</p>
                                <span class="message-time">2 hours ago</span>
                            </div>
                        </div>
                    </div>
                    <div class="message-footer">
                        <a href="comp_messagePage.php" class="view-all">View all messages</a>
                    </div>
                </div>
            </div>
            <div class="dropdown">
                <i class="bx bx-user" onclick="toggleDropdown()"></i>
                <div class="dropdown-menu">
                    <a href="comp_profile.php">Profile</a>
                    <a href="../includes/company/comp_logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Navigation Tabs -->
    <nav class="tabs">
        <button class="hamburger-menu d-md-none" onclick="toggleHamburgerMenu()">☰</button>
        <div id="tabsContainer" class="tabs-container d-none d-md-flex">
            <button class="tab active" data-tab="dashboard" onclick="switchTab('dashboard')">Dashboard</button>
            <button class="tab" data-tab="jobs" onclick="switchTab('jobs')">Jobs</button>
            <button class="tab" data-tab="candidates" onclick="switchTab('candidates')">Candidates</button>
            <button class="tab" data-tab="post-job" onclick="switchTab('post-job')">Post a Job</button>
        </div>
    </nav>

    <style>
        .c_dash_navbar {
            background: #6c63ff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            color: white;
        }

        .c_dash_navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .c_dash_navbar-brand img {
            width: 120px;
        }

        .c_dash_navbar-icons {
            display: flex;
            gap: 20px;
            font-size: 24px;
            margin-left: auto;
        }

        .c_dash_navbar-icons i {
            cursor: pointer;
            color: white;
        }

        .tabs {
            background: #e0e0e0;
            padding: 10px;
            display: flex;
            border-bottom: 2px solid #ccc;
        }

        .tab {
            background: none;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            color: #333;
        }

        .tab:hover {
            color: #6c63ff;
        }

        .tab.active {
            color: #6c63ff;
            border-bottom: 2px solid #6c63ff;
        }

        .hamburger-menu {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            margin: 10px;
            color: #333;
        }

        @media (max-width: 768px) {
            .tabs-container {
                flex-direction: column;
                background-color: #e0e0e0;
                padding: 10px;
            }
        }

        .notification-dropdown, .message-dropdown {
            position: relative;
            display: inline-block;
        }

        .notification-badge, .message-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #ff4444;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
            font-weight: bold;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-content, .message-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 300px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            z-index: 1000;
            margin-top: 10px;
        }

        .notification-content.show, .message-content.show {
            display: block;
        }

        .notification-header, .message-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h5, .message-header h5 {
            margin: 0;
            font-size: 1rem;
            color: #333;
            font-weight: 600;
        }

        .notification-details p, .message-details p {
            margin: 0;
            font-size: 0.9rem;
            color: #333;
            line-height: 1.4;
        }

        .notification-time, .message-time {
            font-size: 0.8rem;
            color: #666;
            margin-top: 4px;
        }

        .message-sender {
            font-weight: 600;
            margin-bottom: 4px;
            color: #333;
        }

        .notification-icon i {
            color: #6c63ff;
            font-size: 1.2rem;
        }

        .notification-item.unread .notification-details p,
        .message-item.unread .message-details p {
            color: #000;
            font-weight: 500;
        }

        .notification-item.unread .message-sender {
            color: #000;
        }

        .notification-list, .message-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item, .message-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: flex-start;
            cursor: pointer;
        }

        .notification-item:hover, .message-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread, .message-item.unread {
            background-color: #f0f7ff;
        }

        .notification-footer, .message-footer {
            padding: 10px 15px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .view-all {
            color: #6c63ff;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .view-all:hover {
            text-decoration: underline;
            color: #5a52e0;
        }

        .message-avatar img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .message-details {
            flex: 1;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 4px;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-menu a:hover {
            background-color: #f8f9fa;
            color: #6c63ff;
        }

        .mark-all-read {
            background: none;
            border: none;
            color: #6c63ff;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .mark-all-read:hover {
            text-decoration: underline;
            color: #5a52e0;
        }

        @media (max-width: 768px) {
            .navbar-title-large {
                display: none !important;
            }
            .navbar-title-small {
                display: inline !important;
            }
            .current-time {
                display: none !important;
            }
        }
    </style>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            document.getElementById('currentTime').textContent = timeString;
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Toggle dropdowns
        function toggleDropdown() {
            document.querySelector('.dropdown-menu').classList.toggle('show');
        }

        function toggleNotification() {
            document.getElementById('notificationContent').classList.toggle('show');
        }

        function toggleMessage() {
            document.getElementById('messageContent').classList.toggle('show');
        }

        // Close dropdowns when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.bx-user')) {
                const dropdowns = document.getElementsByClassName('dropdown-menu');
                for (let dropdown of dropdowns) {
                    if (dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                    }
                }
            }
            if (!event.target.matches('.bx-bell')) {
                const notificationContent = document.getElementById('notificationContent');
                if (notificationContent.classList.contains('show')) {
                    notificationContent.classList.remove('show');
                }
            }
            if (!event.target.matches('.bx-chat')) {
                const messageContent = document.getElementById('messageContent');
                if (messageContent.classList.contains('show')) {
                    messageContent.classList.remove('show');
                }
            }
        }

        // Toggle hamburger menu
        function toggleHamburgerMenu() {
            const tabsContainer = document.getElementById('tabsContainer');
            tabsContainer.classList.toggle('d-none');
        }

        // Switch tabs
        function switchTab(tabName) {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
                if (tab.dataset.tab === tabName) {
                    tab.classList.add('active');
                }
            });
        }
    </script>
</body>
</html>
