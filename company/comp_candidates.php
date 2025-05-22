<?php
session_start();
require "../includes/db_connect.php";

// Check if the user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    header("Location: comp_login.php");
    exit();
}

$company_id = $_SESSION['company_id'];

// Fetch company information
$company_query = "SELECT companyName FROM tbl_comp_info WHERE company_id = ?";
$stmt = $conn->prepare($company_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company_result = $stmt->get_result();
$company_info = $company_result->fetch_assoc();
$stmt->close();

// Fetch active job listings for this company
$jobs_query = "SELECT job_id, title, status FROM tbl_job_listing 
               WHERE employer_id = ? AND status != 'inactive' 
               ORDER BY posted_date DESC";
$stmt = $conn->prepare($jobs_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$jobs_result = $stmt->get_result();
$job_listings = $jobs_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Function to fetch candidates for a specific job and status
function fetchCandidates($conn, $job_id, $status) {
    $query = "SELECT ja.id as application_id, ja.application_time, ja.status,
                     ei.firstName, ei.lastName, ei.emailAddress, ei.contactNumber,
                     (SELECT COUNT(*) FROM tbl_job_application_files jaf WHERE jaf.application_id = ja.id) as file_count
              FROM tbl_job_application ja
              JOIN tbl_emp_info ei ON ja.emp_id = ei.emp_id
              WHERE ja.job_id = ? AND ja.status = ?
              ORDER BY ja.application_time DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $job_id, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $candidates = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $candidates;
}

// Function to generate candidate card HTML
function generateCandidateCard($candidate) {
    $statusColors = [
        'applied' => 'success',
        'awaiting' => 'primary',
        'reviewed' => 'secondary',
        'contacted' => 'info',
        'hired' => 'warning',
        'rejected' => 'danger'
    ];
    
    $statusColor = $statusColors[$candidate['status']] ?? 'secondary';
    
    return '
    <div class="col-md-6 col-lg-4">
        <div class="card candidate-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="candidate-avatar me-3">
                        <i class="fas fa-user-circle" style="font-size: 2.5rem; color: #6c757d;"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">' . htmlspecialchars($candidate['firstName'] . ' ' . $candidate['lastName']) . '</h5>
                        <p class="text-muted mb-0">' . htmlspecialchars($candidate['emailAddress']) . '</p>
                        <small class="text-muted">Applied: ' . date('M d, Y', strtotime($candidate['application_time'])) . '</small>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-' . $statusColor . ' status-badge">' . ucfirst($candidate['status']) . '</span>
                    <div>
                        <span class="badge bg-light text-dark me-2">
                            <i class="fas fa-file-alt me-1"></i>' . $candidate['file_count'] . ' Files
                        </span>
                        <button class="btn btn-sm btn-outline-primary" onclick="viewCandidateProfile(' . $candidate['application_id'] . ')">
                            View Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}
?>
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
        .user-indicator {
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        .user-indicator .company-name {
            font-weight: 600;
            color: #0d6efd;
        }
        .user-indicator .logout-btn {
            color: #dc3545;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .user-indicator .logout-btn:hover {
            text-decoration: underline;
        }
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }
        .profile-btn {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            cursor: pointer;
            color: #495057;
        }
        .profile-btn:hover {
            color: #0d6efd;
        }
        .profile-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 200px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            border-radius: 0.25rem;
            z-index: 1000;
        }
        .profile-dropdown-content.show {
            display: block;
        }
        .profile-dropdown-content a {
            color: #495057;
            padding: 0.75rem 1rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .profile-dropdown-content a:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }
        .profile-dropdown-content .divider {
            height: 1px;
            background-color: #dee2e6;
            margin: 0.25rem 0;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .job-selector {
            min-width: 250px;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- User Indicator -->
        <div class="user-indicator">
            <div class="profile-dropdown">
                <button class="profile-btn" onclick="toggleProfileDropdown()">
                    <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                    <span class="company-name"><?php echo htmlspecialchars($company_info['companyName']); ?></span>
                    <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                </button>
                <div class="profile-dropdown-content" id="profileDropdown">
                    <a href="comp_dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        Return to Dashboard
                    </a>
                    <div class="divider"></div>
                    <a href="../includes/company/comp_logout.php" style="color: #dc3545;">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="header-section">
            <div class="header-left">
                <h2 class="mb-0">Candidate Management</h2>
                <div class="job-selector">
                    <select class="form-select" id="jobSelector" onchange="handleJobSelection(this.value)">
                        <option value="" selected disabled>Select a job posting</option>
                        <?php foreach ($job_listings as $job): ?>
                            <option value="<?php echo htmlspecialchars($job['job_id']); ?>">
                                <?php echo htmlspecialchars($job['title']); ?>
                                <?php echo $job['status'] === 'paused' ? ' (Paused)' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="candidateTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                    <i class="fas fa-list me-2"></i>All
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="applied-tab" data-bs-toggle="tab" data-bs-target="#applied" type="button" role="tab">
                    <i class="fas fa-user-check me-2"></i>Applied
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
            <!-- All Candidates Tab -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <div id="allList">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Job Title</th>
                                    <th>Candidate Name</th>
                                    <th>Application Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="allCandidatesTable">
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                                        <h4 class="text-muted">Select a job posting to view candidates</h4>
                                        <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Applied Candidates Tab -->
            <div class="tab-pane fade" id="applied" role="tabpanel">
                <div id="appliedList" class="row">
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                        <h4 class="text-muted">Select a job posting to view candidates</h4>
                        <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
                    </div>
                </div>
            </div>

            <!-- Awaiting Review Tab -->
            <div class="tab-pane fade" id="awaiting" role="tabpanel">
                <div id="awaitingList" class="row">
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                        <h4 class="text-muted">Select a job posting to view candidates</h4>
                        <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
                    </div>
                </div>
            </div>

            <!-- Reviewed Tab -->
            <div class="tab-pane fade" id="reviewed" role="tabpanel">
                <div id="reviewedList" class="row">
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                        <h4 class="text-muted">Select a job posting to view candidates</h4>
                        <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
                    </div>
                </div>
            </div>

            <!-- Contacted Tab -->
            <div class="tab-pane fade" id="contacted" role="tabpanel">
                <div id="contactedList" class="row">
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                        <h4 class="text-muted">Select a job posting to view candidates</h4>
                        <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
                    </div>
                </div>
            </div>

            <!-- Hired Tab -->
            <div class="tab-pane fade" id="hired" role="tabpanel">
                <div id="hiredList" class="row">
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                        <h4 class="text-muted">Select a job posting to view candidates</h4>
                        <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="../includes/company/js/comp_candidates_script.js"></script>

    <!-- Candidate Profile Modal -->
    <div class="modal fade" id="candidateProfileModal" tabindex="-1" aria-labelledby="candidateProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="candidateProfileModalLabel">Candidate Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading Spinner -->
                    <div id="profileLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading candidate information...</p>
                    </div>

                    <!-- Profile Content -->
                    <div id="profileContent" style="display: none;">
                        <!-- Status Management Section -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Application Status</h5>
                                <div class="d-flex gap-2">
                                    <select class="form-select form-select-sm" id="applicationStatus" style="width: auto;">
                                        <option value="applied">Applied</option>
                                        <option value="awaiting">Awaiting Review</option>
                                        <option value="reviewed">Reviewed</option>
                                        <option value="contacted">Contacted</option>
                                        <option value="hired">Hired</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary" onclick="updateApplicationStatus()">
                                        Update Status
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex gap-2 flex-wrap" id="quickActions">
                                    <!-- Quick action buttons will be dynamically added here -->
                                </div>
                            </div>
                        </div>

                        <!-- Message Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Send Message to Candidate</h5>
                            </div>
                            <div class="card-body">
                                <form id="messageForm" onsubmit="sendMessage(event)">
                                    <div class="mb-3">
                                        <label for="messageSubject" class="form-label">Subject</label>
                                        <input type="text" class="form-control" id="messageSubject" maxlength="64" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="messageContent" class="form-label">Message</label>
                                        <textarea class="form-control" id="messageContent" rows="4" required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-2"></i>Send Message
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="candidate-avatar mb-3">
                                    <i class="fas fa-user-circle" style="font-size: 5rem; color: #6c757d;"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h4 id="candidateName" class="mb-2"></h4>
                                <p id="candidateEmail" class="text-muted mb-1"></p>
                                <p id="candidatePhone" class="text-muted mb-1"></p>
                                <p id="candidateAddress" class="text-muted mb-1"></p>
                                <div class="mt-2">
                                    <span id="candidateAge" class="badge bg-light text-dark me-2"></span>
                                    <span id="candidateGender" class="badge bg-light text-dark me-2"></span>
                                    <span id="candidateEducation" class="badge bg-light text-dark me-2"></span>
                                    <span id="candidateExperience" class="badge bg-light text-dark"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Resume Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Submitted Resumes</h5>
                            </div>
                            <div class="card-body">
                                <div id="resumeList" class="list-group">
                                    <!-- Resumes will be listed here -->
                                </div>
                            </div>
                        </div>

                        <!-- Education Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Education</h5>
                            </div>
                            <div class="card-body">
                                <div id="educationList">
                                    <!-- Education history will be listed here -->
                                </div>
                            </div>
                        </div>

                        <!-- Work Experience Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Work Experience</h5>
                            </div>
                            <div class="card-body">
                                <div id="experienceList">
                                    <!-- Work experience will be listed here -->
                                </div>
                            </div>
                        </div>

                        <!-- Skills Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Skills</h5>
                            </div>
                            <div class="card-body">
                                <div id="skillsList">
                                    <!-- Skills will be listed here -->
                                </div>
                            </div>
                        </div>

                        <!-- Languages Section -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Languages</h5>
                            </div>
                            <div class="card-body">
                                <div id="languagesList">
                                    <!-- Languages will be listed here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Resume Preview Modal -->
    <div class="modal fade" id="resumePreviewModal" tabindex="-1" aria-labelledby="resumePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resumePreviewModalLabel">Resume Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="resumePreviewLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading resume preview...</p>
                    </div>
                    <iframe id="resumePreviewFrame" style="width: 100%; height: 80vh; border: none; display: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
