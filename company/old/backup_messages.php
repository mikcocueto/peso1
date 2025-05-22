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
                <div class="job-listings">
                    <!-- Empty state for job listings -->
                    <div class="text-center p-4 d-none" id="noJobListings">
                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No Job Listings</h6>
                        <p class="text-muted small">You haven't posted any job listings yet.</p>
                    </div>
                    <!-- Mock Job Listings -->
                    <div class="job-listing-item active">
                        <h6 class="mb-1">Senior Software Developer</h6>
                        <small class="text-muted">Posted: 2024-03-15</small>
                    </div>
                    <div class="job-listing-item">
                        <h6 class="mb-1">UI/UX Designer</h6>
                        <small class="text-muted">Posted: 2024-03-10</small>
                    </div>
                    <div class="job-listing-item">
                        <h6 class="mb-1">Project Manager</h6>
                        <small class="text-muted">Posted: 2024-03-05</small>
                    </div>
                </div>
            </div>

            <!-- Message List -->
            <div class="col-md-9 col-lg-10 message-list p-0">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Messages - Senior Software Developer</h5>
                </div>
                <div class="messages">
                    <!-- Empty state for messages -->
                    <div class="text-center p-5 d-none" id="noMessages">
                        <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No Messages</h6>
                        <p class="text-muted small">There are no messages for this job listing.</p>
                    </div>
                    <!-- Mock Messages -->
                    <div class="message-item" data-bs-toggle="modal" data-bs-target="#messageModal">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Interview Invitation</h6>
                                <p class="mb-1 text-muted">To: John Doe</p>
                            </div>
                            <small class="text-muted">2024-03-16 14:30</small>
                        </div>
                    </div>
                    <div class="message-item" data-bs-toggle="modal" data-bs-target="#messageModal">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Application Status Update</h6>
                                <p class="mb-1 text-muted">To: Jane Smith</p>
                            </div>
                            <small class="text-muted">2024-03-15 10:15</small>
                        </div>
                    </div>
                    <div class="message-item" data-bs-toggle="modal" data-bs-target="#messageModal">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Technical Assessment Details</h6>
                                <p class="mb-1 text-muted">To: Mike Johnson</p>
                            </div>
                            <small class="text-muted">2024-03-14 16:45</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Interview Invitation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>To:</strong> John Doe
                    </div>
                    <div class="mb-3">
                        <strong>Subject:</strong> Interview Invitation for Senior Software Developer Position
                    </div>
                    <div class="mb-3">
                        <strong>Date:</strong> March 16, 2024 14:30
                    </div>
                    <hr>
                    <div class="message-content">
                        <p>Dear John,</p>
                        <p>Thank you for your interest in the Senior Software Developer position at our company. We were impressed with your application and would like to invite you for an interview.</p>
                        <p>The interview is scheduled for March 20, 2024, at 10:00 AM. It will be conducted via Zoom, and we will send you the meeting link one hour before the scheduled time.</p>
                        <p>Please confirm your availability by replying to this message.</p>
                        <p>Best regards,<br>HR Team</p>
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
        // Add click event listeners to job listings
        document.querySelectorAll('.job-listing-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                document.querySelectorAll('.job-listing-item').forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');
                // Here you would typically load the messages for this job listing
            });
        });

        // Function to show/hide empty states
        function toggleEmptyStates() {
            const jobListings = document.querySelectorAll('.job-listing-item');
            const messages = document.querySelectorAll('.message-item');
            const noJobListings = document.getElementById('noJobListings');
            const noMessages = document.getElementById('noMessages');

            // Toggle job listings empty state
            if (jobListings.length === 0) {
                noJobListings.classList.remove('d-none');
            } else {
                noJobListings.classList.add('d-none');
            }

            // Toggle messages empty state
            if (messages.length === 0) {
                noMessages.classList.remove('d-none');
            } else {
                noMessages.classList.add('d-none');
            }
        }

        // Call the function when the page loads
        document.addEventListener('DOMContentLoaded', toggleEmptyStates);
    </script>
</body>
</html>
