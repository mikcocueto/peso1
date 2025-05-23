<?php

include '../includes/db_connect.php';
// Check if user is logged in as employee
if (!isset($_SESSION['employee_id'])) {
    header("Location: emp_reg&login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Messages</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            --hover-gradient: linear-gradient(135deg, #818cf8 0%, #6366f1 100%);
            --sidebar-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        body {
            overflow: hidden;
            background-color: #f1f5f9;
        }

        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: var(--card-shadow);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
        }

        .navbar .btn-link {
            color: white !important;
        }

        .message-container {
            height: 100vh;
            background-color: #f1f5f9;
            overflow: hidden;
        }

        .sidebar {
            height: 100%;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            box-shadow: var(--card-shadow);
        }

        .job-listings-container {
            flex-grow: 1;
            overflow-y: auto;
            height: calc(100vh - 56px - 57px);
            background-color: white;
        }

        .job-listing-item {
            cursor: pointer;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            position: relative;
        }

        .job-listing-item:hover {
            background-color: #f8fafc;
            transform: translateX(4px);
        }

        .job-listing-item.active {
            background-color: #f8fafc;
            border-left: 4px solid #4f46e5;
        }

        .job-listing-item .dropdown {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .job-listing-item:hover .dropdown {
            opacity: 1;
        }

        .message-content {
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            background-color: white;
        }

        .message-list {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.5rem;
            padding-bottom: 80px;
            height: calc(100vh - 56px - 57px - 80px);
            position: relative;
            background-color: #f8fafc;
        }

        .chat-bubble {
            max-width: 80%;
            margin-bottom: 1.5rem;
        }

        .chat-bubble.sent {
            margin-left: auto;
        }

        .chat-bubble.received {
            margin-right: auto;
        }

        .chat-bubble .card {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
        }

        .chat-bubble.sent .card {
            background: var(--primary-gradient);
        }

        .chat-bubble.received .card {
            background: white;
        }

        .message-time {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .message-input {
            position: fixed;
            bottom: 0;
            right: 0;
            width: 66.666667%;
            background-color: white;
            border-top: 1px solid #e2e8f0;
            padding: 1rem;
            box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.1);
            z-index: 1000;
        }

        .message-input .form-control {
            border-radius: 1.5rem;
            padding: 0.75rem 1.25rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .message-input .form-control:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
        }

        .message-input .btn-primary {
            border-radius: 1.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--primary-gradient);
            border: none;
            transition: all 0.2s ease;
        }

        .message-input .btn-primary:hover {
            background: var(--hover-gradient);
            transform: translateY(-1px);
        }

        .btn-light {
            background-color: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .btn-light:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 0.75rem;
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8fafc;
        }

        .dropdown-item.text-danger:hover {
            background-color: #fee2e2;
        }

        #noMessages {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            text-align: center;
            color: #94a3b8;
        }

        #noMessages i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #cbd5e1;
        }

        @media (max-width: 767.98px) {
            .message-input {
                width: 100%;
            }
        }

        #noMessageSelected {
            height: ;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
        }

        #noMessageSelected i {
            font-size: 3rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        #noMessageSelected h5 {
            color: #475569;
            margin-bottom: 0.5rem;
        }

        #noMessageSelected p {
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-envelope-fill me-2"></i>PESO Employee Messages
            </a>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn btn-link text-dark text-decoration-none dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="emp_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Back to Dashboard</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid message-container p-0">
        <div class="row g-0">
            <!-- Left Sidebar -->
            <div class="col-md-4 p-0 sidebar">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Job Applications</h5>
                </div>
                <div class="job-listings-container" id="jobListings">
                    <!-- Job listings will be dynamically populated here -->
                </div>
            </div>

            <!-- Right Panel -->
            <div class="col-md-8 p-0 message-content">
                <!-- No Message Selected State -->
                <div class="d-flex flex-column align-items-center justify-content-center h-100" id="noMessageSelected">
                    <i class="bi bi-chat-dots fs-1 text-muted"></i>
                    <h5 class="text-muted">No Message Selected</h5>
                    <p class="text-muted">Select a job application to view messages.</p>
                </div>
                <div class="message-list d-none" id="messageList">
                    <!-- Messages will be dynamically populated here -->
                </div>
                <div class="message-input d-none" id="messageInputContainer">
                    <form id="messageForm">
                        <div class="input-group">
                            <input type="text" class="form-control" id="messageInput" placeholder="Type a message...">
                            <button class="btn btn-primary" type="submit">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const employeeId = <?php echo json_encode($employee_id); ?>;

        // Function to load job applications
        function loadJobApplications() {
            fetch(`../includes/employee/emp_job_applications_fetch.php?emp_id=${employeeId}`)
                .then(response => response.json())
                .then(data => {
                    const jobListings = document.getElementById('jobListings');
                    jobListings.innerHTML = '';
                    data.forEach(application => {
                        const jobItem = document.createElement('div');
                        jobItem.className = 'job-listing-item';
                        jobItem.textContent = application.job_title;
                        jobItem.addEventListener('click', () => loadMessages(application.application_id));
                        jobListings.appendChild(jobItem);
                    });
                });
        }

        // Function to load messages for a specific application
        function loadMessages(applicationId) {
            fetch(`../includes/employee/emp_messages_fetch.php?application_id=${applicationId}`)
                .then(response => response.json())
                .then(data => {
                    const messageList = document.getElementById('messageList');
                    const noMessageSelected = document.getElementById('noMessageSelected');
                    const messageInputContainer = document.getElementById('messageInputContainer');

                    noMessageSelected.classList.add('d-none');
                    messageList.classList.remove('d-none');
                    messageInputContainer.classList.remove('d-none');

                    messageList.innerHTML = '';
                    data.forEach(message => {
                        const chatBubble = document.createElement('div');
                        chatBubble.className = `chat-bubble ${message.sender === 'employee' ? 'sent' : 'received'}`;

                        const card = document.createElement('div');
                        card.className = 'card p-3';
                        card.textContent = message.message;

                        chatBubble.appendChild(card);
                        messageList.appendChild(chatBubble);
                    });
                });
        }

        // Handle message form submission
        document.getElementById('messageForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();

            if (message) {
                // Send message to the server
                fetch('../includes/employee/emp_send_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        emp_id: employeeId,
                        message
                    })
                }).then(() => {
                    messageInput.value = '';
                    // Reload messages
                    loadMessages(currentApplicationId);
                });
            }
        });

        // Load job applications when the page loads
        document.addEventListener('DOMContentLoaded', loadJobApplications);
    </script>
</body>
</html>
