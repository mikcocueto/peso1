<?php
session_start();
// Check if user is logged in as company
if (!isset($_SESSION['company_id'])) {
    header("Location: comp_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Messages</title>
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
                <i class="bi bi-envelope-fill me-2"></i>PESO Company Messages
            </a>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn btn-link text-dark text-decoration-none dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="comp_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Back to Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="comp_login.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
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
                    <h5 class="mb-0">Job Listings</h5>
                    <div class="dropdown float-end">
                                    <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-archive me-2"></i>Archived Chats</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-question-circle me-2"></i>Help</a></li>
                                    </ul>
                                </div>
                </div>
                <div class="job-listings-container">
                    <div id="jobListingsContainer">
                        <!-- Empty state for job listings -->
                        <div class="text-center p-4 d-none" id="noJobListings">
                            <i class="bi bi-briefcase fs-1 text-muted mb-3"></i>
                            <h6 class="text-muted">No Job Listings Found</h6>
                            <p class="text-muted small">You haven't posted any job listings yet.</p>
                            <a href="comp_post_job.php" class="btn btn-primary btn-sm mt-2">
                                <i class="bi bi-plus me-1"></i>Post a New Job
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="col-md-8 p-0 message-content">
                <!-- No Message Selected State -->
                <div class="d-none" id="noMessageSelected">
                    <div class="text-center">
                        <i class="bi bi-envelope"></i>
                        <h5>Select a Job Listing</h5>
                        <p>Choose a job listing from the sidebar to view messages</p>
                    </div>
                </div>

                <!-- Message Content -->
                <div id="messageContent" class="d-none">
                    <div class="p-3 bg-white border-bottom">
                        <h5 class="mb-0" id="currentJobTitle"></h5>
                    </div>
                    <div class="message-list" id="messagesContainer">
                        <!-- Messages will be loaded here -->
                    </div>
                    <div class="text-center p-5" id="noMessages" style="display: none;">
                        <i class="bi bi-chat-dots fs-1 text-muted mb-3"></i>
                        <h6 class="text-muted">No Messages Yet</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to format date
        function formatDate(dateString) {
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }

        // Function to load job listings
        function loadJobListings() {
            fetch('../includes/company/comp_messages_fetch.php')
                .then(response => response.json())
                .then(data => {
                    const jobListingsContainer = document.getElementById('jobListingsContainer');
                    const noJobListings = document.getElementById('noJobListings');
                    const noMessageSelected = document.getElementById('noMessageSelected');
                    const messageContent = document.getElementById('messageContent');
                    
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    // Show no message selected state
                    noMessageSelected.classList.remove('d-none');
                    messageContent.classList.add('d-none');

                    if (!jobListingsContainer || !noJobListings) {
                        console.error('Required elements not found');
                        return;
                    }

                    if (data.job_listings.length === 0) {
                        noJobListings.classList.remove('d-none');
                        jobListingsContainer.innerHTML = '';
                        return;
                    }

                    noJobListings.classList.add('d-none');
                    jobListingsContainer.innerHTML = data.job_listings.map(job => `
                        <div class="job-listing-item" data-job-id="${job.job_id}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-3">
                                        <i class="bi bi-briefcase text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">${job.title}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>${formatDate(job.posted_date)}
                                        </small>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit Job</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Delete Job</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-archive me-2"></i>Archive</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    // Add click event listeners to job listings
                    document.querySelectorAll('.job-listing-item').forEach(item => {
                        item.addEventListener('click', function() {
                            const jobId = this.dataset.jobId;
                            loadMessages(jobId);
                            
                            // Update active state
                            document.querySelectorAll('.job-listing-item').forEach(i => i.classList.remove('active'));
                            this.classList.add('active');

                            // Show message content
                            noMessageSelected.classList.add('d-none');
                            messageContent.classList.remove('d-none');
                        });
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Function to load messages for a specific job
        function loadMessages(jobId) {
            fetch(`../includes/company/comp_messages_fetch.php?job_id=${jobId}`)
                .then(response => response.json())
                .then(data => {
                    const messagesContainer = document.getElementById('messagesContainer');
                    const currentJobTitle = document.getElementById('currentJobTitle');
                    const noMessages = document.getElementById('noMessages');
                    
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    // Update job title
                    if (currentJobTitle) {
                        const selectedJob = data.job_listings.find(job => job.job_id == jobId);
                        if (selectedJob) {
                            currentJobTitle.textContent = selectedJob.title;
                        }
                    }

                    // Show no messages state if there are no messages
                    if (data.messages.length === 0) {
                        if (messagesContainer) {
                            messagesContainer.innerHTML = '';
                        }
                        if (noMessages) {
                            noMessages.style.display = 'block';
                        }
                        return;
                    }

                    // Hide no messages state and show messages
                    if (noMessages) {
                        noMessages.style.display = 'none';
                    }

                    // Add new messages
                    if (messagesContainer && data.messages.length > 0) {
                        messagesContainer.innerHTML = data.messages.map(message => `
                            <div class="chat-bubble ${message.is_sent ? 'sent' : 'received'}">
                                <div class="card ${message.is_sent ? 'bg-primary text-white' : 'bg-light'} shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <strong>${message.is_sent ? 'You' : message.sender_name}</strong>
                                                <small class="message-time d-block">${formatDate(message.timestamp)}</small>
                                            </div>
                                            
                                        </div>
                                        <p class="mb-0">${message.message}</p>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Handle message form submission
        document.getElementById('messageForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (message) {
                // TODO: Implement message sending functionality
                messageInput.value = '';
            }
        });

        // Load job listings when the page loads
        document.addEventListener('DOMContentLoaded', loadJobListings);
    </script>
</body>
</html>
