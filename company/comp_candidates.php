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
        'active' => 'success',
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
                    <a href="comp_logout.php" style="color: #dc3545;">
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
                <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
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

            <!-- Active Candidates Tab -->
            <div class="tab-pane fade" id="active" role="tabpanel">
                <div id="candidateList" class="row">
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                        <h4 class="text-muted">Select a job posting to view candidates</h4>
                        <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
                    </div>
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
    <script>
    function handleJobSelection(jobId) {
        if (!jobId) {
            resetCandidateList();
            return;
        }

        // Fetch all candidates first
        fetchAllCandidates(jobId);

        // Then fetch candidates for each status
        const statuses = ['active', 'awaiting', 'reviewed', 'contacted', 'hired'];
        statuses.forEach(status => {
            fetchCandidatesForStatus(jobId, status);
        });
    }

    function resetCandidateList() {
        // Reset all tab
        document.getElementById('allCandidatesTable').innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-5">
                    <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                    <h4 class="text-muted">Select a job posting to view candidates</h4>
                    <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
                </td>
            </tr>
        `;

        // Reset other tabs
        const statuses = ['active', 'awaiting', 'reviewed', 'contacted', 'hired'];
        statuses.forEach(status => {
            const tabId = status === 'active' ? 'active' : status;
            document.getElementById(`${tabId}List`).innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                    <h4 class="text-muted">Select a job posting to view candidates</h4>
                    <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
                </div>
            `;
        });
    }

    function fetchAllCandidates(jobId) {
        fetch(`../includes/company/comp_get_candidates.php?job_id=${jobId}&status=all`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('allCandidatesTable');
                
                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-users mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                                <h4 class="text-muted">No candidates found</h4>
                                <p class="text-muted">There are no candidates for this job posting</p>
                            </td>
                        </tr>
                    `;
                } else {
                    tableBody.innerHTML = data.map(candidate => `
                        <tr>
                            <td>${candidate.job_title}</td>
                            <td>${candidate.firstName} ${candidate.lastName}</td>
                            <td>${new Date(candidate.application_time).toLocaleString()}</td>
                            <td><span class="badge bg-${getStatusColor(candidate.status)}">${capitalizeFirst(candidate.status)}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewCandidateProfile(${candidate.application_id})">
                                    View Profile
                                </button>
                            </td>
                        </tr>
                    `).join('');
                }
            })
            .catch(error => {
                console.error('Error fetching all candidates:', error);
                document.getElementById('allCandidatesTable').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-exclamation-circle mb-3" style="font-size: 3rem; color: #dc3545;"></i>
                            <h4 class="text-danger">Error loading candidates</h4>
                            <p class="text-muted">Please try again later</p>
                        </td>
                    </tr>
                `;
            });
    }

    function fetchCandidatesForStatus(jobId, status) {
        fetch(`../includes/company/comp_get_candidates.php?job_id=${jobId}&status=${status}`)
            .then(response => response.json())
            .then(data => {
                const tabId = status === 'active' ? 'active' : status;
                const candidateList = document.getElementById(`${tabId}List`);
                
                if (data.length === 0) {
                    candidateList.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-users mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="text-muted">No ${status} candidates</h4>
                            <p class="text-muted">There are no candidates in this status</p>
                        </div>
                    `;
                } else {
                    candidateList.innerHTML = data.map(candidate => `
                        <div class="col-md-6 col-lg-4">
                            <div class="card candidate-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="candidate-avatar me-3">
                                            <i class="fas fa-user-circle" style="font-size: 2.5rem; color: #6c757d;"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-0">${candidate.firstName} ${candidate.lastName}</h5>
                                            <p class="text-muted mb-0">${candidate.emailAddress}</p>
                                            <small class="text-muted">Applied: ${new Date(candidate.application_time).toLocaleDateString()}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-${getStatusColor(candidate.status)} status-badge">${capitalizeFirst(candidate.status)}</span>
                                        <div>
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="fas fa-file-alt me-1"></i>${candidate.file_count} Files
                                            </span>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewCandidateProfile(${candidate.application_id})">
                                                View Profile
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
            })
            .catch(error => {
                console.error('Error fetching candidates:', error);
                const tabId = status === 'active' ? 'active' : status;
                document.getElementById(`${tabId}List`).innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-exclamation-circle mb-3" style="font-size: 3rem; color: #dc3545;"></i>
                        <h4 class="text-danger">Error loading candidates</h4>
                        <p class="text-muted">Please try again later</p>
                    </div>
                `;
            });
    }

    function getStatusColor(status) {
        const colors = {
            'active': 'success',
            'awaiting': 'primary',
            'reviewed': 'secondary',
            'contacted': 'info',
            'hired': 'warning',
            'rejected': 'danger'
        };
        return colors[status] || 'secondary';
    }

    function capitalizeFirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function viewCandidateProfile(applicationId) {
        // This will be implemented when you add the candidate profile modal
        console.log('Viewing profile for application:', applicationId);
    }

    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('show');
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.profile-btn') && !event.target.matches('.profile-btn *')) {
            const dropdowns = document.getElementsByClassName('profile-dropdown-content');
            for (let dropdown of dropdowns) {
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    }
    </script>
</body>
</html>
