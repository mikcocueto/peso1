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
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: calc(100vh - 56px);
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
        }
        .message-list {
            height: calc(100vh - 56px);
            overflow-y: auto;
        }
        .job-listing-item {
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            transition: background-color 0.2s;
        }
        .job-listing-item:hover {
            background-color: #e9ecef;
        }
        .job-listing-item.active {
            background-color: #e9ecef;
            border-left: 4px solid #0d6efd;
        }
        .message-item {
            cursor: pointer;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            transition: background-color 0.2s;
        }
        .message-item:hover {
            background-color: #f8f9fa;
        }
        .profile-dropdown {
            min-width: 200px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Company Messages</a>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn btn-link text-dark text-decoration-none dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fa-lg"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end profile-dropdown" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="comp_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Back to Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="comp_login.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar with Job Listings -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Job Listings</h5>
                </div>
                <div id="jobListingsContainer" class="job-listings">
                    <!-- Empty state for job listings -->
                    <div class="text-center p-4 d-none" id="noJobListings">
                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No Job Listings</h6>
                        <p class="text-muted small">You haven't posted any job listings yet.</p>
                    </div>
                    <!-- Job listings will be loaded here -->
                </div>
            </div>

            <!-- Message List -->
            <div class="col-md-9 col-lg-10 message-list p-0">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0" id="currentJobTitle">Select a job listing to view messages</h5>
                </div>
                <div id="messagesContainer" class="messages">
                    <!-- Empty state for messages -->
                    <div class="text-center p-5 d-none" id="noMessages">
                        <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No Messages</h6>
                        <p class="text-muted small">There are no messages for this job listing.</p>
                    </div>
                    <!-- Messages will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>To:</strong> <span id="modalRecipient"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Subject:</strong> <span id="modalSubject"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Date:</strong> <span id="modalDate"></span>
                    </div>
                    <hr>
                    <div class="message-content" id="modalMessage">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    if (!jobListingsContainer || !noJobListings) {
                        console.error('Required elements not found');
                        return;
                    }

                    if (data.job_listings.length === 0) {
                        noJobListings.classList.remove('d-none');
                        return;
                    }

                    noJobListings.classList.add('d-none');
                    jobListingsContainer.innerHTML = data.job_listings.map(job => `
                        <div class="job-listing-item" data-job-id="${job.job_id}">
                            <h6 class="mb-1">${job.title}</h6>
                            <small class="text-muted">Posted: ${formatDate(job.posted_date)}</small>
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
                    const noMessages = document.getElementById('noMessages');
                    const currentJobTitle = document.getElementById('currentJobTitle');
                    
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    if (!messagesContainer || !noMessages || !currentJobTitle) {
                        console.error('Required elements not found');
                        return;
                    }

                    // Update job title
                    const selectedJob = data.job_listings.find(job => job.job_id == jobId);
                    if (selectedJob) {
                        currentJobTitle.textContent = `Messages - ${selectedJob.title}`;
                    }

                    if (data.messages.length === 0) {
                        noMessages.classList.remove('d-none');
                        messagesContainer.innerHTML = '';
                        return;
                    }

                    noMessages.classList.add('d-none');
                    messagesContainer.innerHTML = data.messages.map(message => `
                        <div class="message-item" data-bs-toggle="modal" data-bs-target="#messageModal"
                             data-subject="${message.subject}"
                             data-recipient="${message.recipient}"
                             data-date="${message.timestamp}"
                             data-message="${message.message}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">${message.subject}</h6>
                                    <p class="mb-1 text-muted">To: ${message.recipient}</p>
                                </div>
                                <small class="text-muted">${formatDate(message.timestamp)}</small>
                            </div>
                        </div>
                    `).join('');

                    // Add click event listeners to messages
                    document.querySelectorAll('.message-item').forEach(item => {
                        item.addEventListener('click', function() {
                            const modal = document.getElementById('messageModal');
                            if (!modal) {
                                console.error('Message modal not found');
                                return;
                            }

                            const modalLabel = modal.querySelector('#messageModalLabel');
                            const modalRecipient = modal.querySelector('#modalRecipient');
                            const modalSubject = modal.querySelector('#modalSubject');
                            const modalDate = modal.querySelector('#modalDate');
                            const modalMessage = modal.querySelector('#modalMessage');

                            if (modalLabel) modalLabel.textContent = this.dataset.subject;
                            if (modalRecipient) modalRecipient.textContent = this.dataset.recipient;
                            if (modalSubject) modalSubject.textContent = this.dataset.subject;
                            if (modalDate) modalDate.textContent = formatDate(this.dataset.timestamp);
                            if (modalMessage) modalMessage.innerHTML = this.dataset.message.replace(/\n/g, '<br>');
                        });
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Load job listings when the page loads
        document.addEventListener('DOMContentLoaded', loadJobListings);
    </script>
</body>
</html>
