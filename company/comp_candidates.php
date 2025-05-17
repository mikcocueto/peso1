<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .candidate-card {
            transition: transform 0.2s;
            border: 1px solid #e0e0e0;
            margin-bottom: 1rem;
        }
        .candidate-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 600;
        }
        .job-selector {
            max-width: 300px;
        }
        .candidate-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 0.35em 0.65em;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Candidate Management</h2>
            <div class="job-selector">
                <select class="form-select" id="jobSelector">
                    <option selected disabled>Select a job posting</option>
                    <option value="1">Senior Software Developer</option>
                    <option value="2">UI/UX Designer</option>
                    <option value="3">Project Manager</option>
                </select>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="candidateTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                    <i class="fas fa-user-check me-2"></i>Active
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="awaiting-tab" data-bs-toggle="tab" data-bs-target="#awaiting" type="button" role="tab">
                    <i class="fas fa-clock me-2"></i>Awaiting Review
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviewed-tab" data-bs-toggle="tab" data-bs-target="#reviewed" type="button" role="tab">
                    <i class="fas fa-eye me-2"></i>Reviewed
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contacted-tab" data-bs-toggle="tab" data-bs-target="#contacted" type="button" role="tab">
                    <i class="fas fa-envelope me-2"></i>Contacted
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="hired-tab" data-bs-toggle="tab" data-bs-target="#hired" type="button" role="tab">
                    <i class="fas fa-check-circle me-2"></i>Hired
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="candidateTabsContent">
            <!-- Active Candidates Tab -->
            <div class="tab-pane fade show active" id="active" role="tabpanel">
                <div class="row">
                    <!-- Sample Candidate Card -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card candidate-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://via.placeholder.com/50" alt="Candidate" class="candidate-avatar me-3">
                                    <div>
                                        <h5 class="card-title mb-0">John Doe</h5>
                                        <p class="text-muted mb-0">Senior Developer</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success status-badge">Active</span>
                                    <button class="btn btn-sm btn-outline-primary">View Profile</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add more candidate cards here -->
                </div>
            </div>

            <!-- Awaiting Review Tab -->
            <div class="tab-pane fade" id="awaiting" role="tabpanel">
                <div class="row">
                    <!-- Sample content for awaiting review -->
                </div>
            </div>

            <!-- Reviewed Tab -->
            <div class="tab-pane fade" id="reviewed" role="tabpanel">
                <div class="row">
                    <!-- Sample content for reviewed -->
                </div>
            </div>

            <!-- Contacted Tab -->
            <div class="tab-pane fade" id="contacted" role="tabpanel">
                <div class="row">
                    <!-- Sample content for contacted -->
                </div>
            </div>

            <!-- Hired Tab -->
            <div class="tab-pane fade" id="hired" role="tabpanel">
                <div class="row">
                    <!-- Sample content for hired -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
