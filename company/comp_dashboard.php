<?php
session_start();
require "../includes/db_connect.php";

// Check if the user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    header("Location: comp_login.php");
    exit();
}

$company_id = $_SESSION['company_id'];

// Fetch job categories from the database
$categories_result = $conn->query("SELECT category_id, category_name FROM tbl_job_category");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch company verification status from tbl_comp_info
$verification_query = "SELECT company_verified FROM tbl_comp_info WHERE company_id = ?";
$stmt = $conn->prepare($verification_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$verification_result = $stmt->get_result();
$verification_status = $verification_result->fetch_assoc();
$is_verified = $verification_status && $verification_status['company_verified'] == 1;
$stmt->close();

// Handle job search
$search_query = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'posted_date';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'desc';

$query = "SELECT jl.job_id, jl.title, jl.description, jl.posted_date, jl.expiry_date, jl.status,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'pending') AS pending_count,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'awaiting') AS awaiting_count,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'accepted') AS accepted_count
          FROM tbl_job_listing jl 
          WHERE jl.employer_id = ? AND jl.title LIKE ?";

// Add sorting based on the sort_by parameter
switch($sort_by) {
    case 'title':
        $query .= " ORDER BY jl.title " . ($sort_order === 'asc' ? 'ASC' : 'DESC');
        break;
    case 'posted_date':
        $query .= " ORDER BY jl.posted_date " . ($sort_order === 'asc' ? 'ASC' : 'DESC');
        break;
    case 'expiry_date':
        $query .= " ORDER BY jl.expiry_date " . ($sort_order === 'asc' ? 'ASC' : 'DESC');
        break;
    case 'pending_count':
        $query .= " ORDER BY pending_count " . ($sort_order === 'asc' ? 'ASC' : 'DESC');
        break;
    default:
        $query .= " ORDER BY jl.posted_date DESC";
}

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $company_id, $search_query);
$stmt->execute();
$result = $stmt->get_result();
$jobs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch jobs posted by the logged-in company for the dropdown
$jobs_dropdown_query = "SELECT job_id, title FROM tbl_job_listing WHERE employer_id = ?";
$stmt = $conn->prepare($jobs_dropdown_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$jobs_dropdown_result = $stmt->get_result();
$jobs_dropdown = $jobs_dropdown_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
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
    <link rel="canonical" href="https://demo-basic.adminkit.io/charts-chartjs.html" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- c_dash_navbar -->
    <nav class="c_dash_navbar">
        <div class="c_dash_navbar-brand">
            <img src="../fortest/images/peso_icons.png" alt="PESO Logo">
            <div>
                <span style="font-size: 1.5rem; font-weight: bold; color: white;">PESO</span>
                <span style="font-size: 1.5rem; font-weight: bold; padding-left: 35px; color: white;"> for Company</span>
            </div>
        </div>
        <div class="c_dash_navbar-icons">
            <span id="currentTime" style="color: white; margin-right: 20px;"></span>
            <i class="bx bx-bell"></i>
            <i class="bx bx-chat"></i>
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
        .hamburger-menu {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            margin: 10px;
        }
        .tabs-container {
            flex-direction: column;
            background-color: #f8f9fa;
            padding: 10px;
        }
        .tabs-container.d-md-flex {
            flex-direction: row;
            background-color: transparent;
            padding: 0;
        }
    </style>

    <script>
        function toggleHamburgerMenu() {
            const tabsContainer = document.getElementById('tabsContainer');
            tabsContainer.classList.toggle('d-none');
        }
    </script>

<section id="dashboard" class="content active">
				<h1 class="h3 mb-3"><strong>Company Details</strong></h1>
				<div class="container-fluid p-0">
					<!-- New container for company details -->
					<div class="row mb-3">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<div class="d-flex justify-content-between align-items-center">
                                    <img src="../assets/images/fds.jpg" class="img-fluid" alt="Company Logo" style="max-height: 200px;">
										<div>
											<h3>Company Information</h3>
											<p class="card-text">Company Name: XYZ Corp</p>
											<p class="card-text">Country: Philippines</p>
											<p class="card-text">Company Address: San Pablo City</p>
											<p class="card-text">Company Hotline: 4444 444</p>
											<p class="card-text">Company Number: 0912-345-6789 </p>
											<p class="card-text">Human Resource: John Doe</p>
										</div>
										
									</div>
									<div class="d-flex justify-content-end mt-3">
										<button class="btn btn-primary" onclick="editCompanyDetails()">Edit</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End of new container -->

					<h1 class="h3 mb-3"><strong>Analytics Dashboard</strong></h1>

					<div class="row">
						<div class="col-xl-6 col-xxl-5 d-flex">
							<div class="w-100">
								<div class="row">
									<div class="col-sm-6">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Applicants</h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															<i class="align-middle" data-feather="truck"></i>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3">2.382</h1>
												<div class="mb-0">
													<span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> -3.65% </span>
													<span class="text-muted">Since last week</span>
												</div>
											</div>
										</div>
                                        <div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Visitors</h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															<i class="align-middle" data-feather="users"></i>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3">14.212</h1>
												<div class="mb-0">
													<span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> 5.25% </span>
													<span class="text-muted">Since last week</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Posted Jobs  </h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															<i class="align-middle" data-feather="dollar-sign"></i>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3">$21.300</h1>
												<div class="mb-0">
													<span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> 6.65% </span>
													<span class="text-muted">Since last week</span>
												</div>
											</div>
										</div>
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Hired Applicants</h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															<i class="align-middle" data-feather="shopping-cart"></i>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3">64</h1>
												<div class="mb-0">
													<span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> -2.25% </span>
													<span class="text-muted">Since last week</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
                        <div class="col-xl-6 col-xxl-7">
        <div class="card flex-fill w-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Movement</h5>
            </div>
            <div class="card-body py-3">
                <div class="chart chart-sm">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const ctx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Monthly Data',
                    data: [10, 20, 15, 25, 30, 35, 40, 38, 32, 28, 22, 18],
                    borderColor: 'blue',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }
                }
            }
        });
    </script>
				</section>

    <!-- Job posted list Tab -->
    <section id="jobs" class="content active">
        <!-- Filters -->
    <section class="filters">
    <div class="container">
        <div class="row mb-3 align-items-center">
             <!-- Filter Buttons -->
            <div class="row mb-3">
                <div class="col-md-6 d-flex">
                    <button class="btn btn-outline-primary me-2">Open and Paused (#)</button>
                    <button class="btn btn-outline-secondary">Closed (#)</button>
                </div>
            </div>
            <!-- Search Bar -->
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search job title">
                    <button type="button" class="btn btn-primary" onclick="searchJobs()">Search</button>
                </div>
            </div>
            <!-- Sorting Options -->
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <select class="form-select me-2" id="sortBy" onchange="updateSort()">
                        <option value="posted_date" <?= $sort_by === 'posted_date' ? 'selected' : '' ?>>Sort by Post Date</option>
                        <option value="title" <?= $sort_by === 'title' ? 'selected' : '' ?>>Sort by A-Z</option>
                        <option value="expiry_date" <?= $sort_by === 'expiry_date' ? 'selected' : '' ?>>Sort by Expiry Date</option>
                        <option value="pending_count" <?= $sort_by === 'pending_count' ? 'selected' : '' ?>>Sort by Pending Applicants</option>
                    </select>
                    <button class="btn btn-outline-secondary" onclick="toggleSortOrder()">
                        <i class="bx bx-sort"></i>
                        <span id="sortOrderIndicator"><?= $sort_order === 'desc' ? 'Descending' : 'Ascending' ?></span>
                    </button>
                </div>
            </div>
        </div>
       
    </div>
</section>
        <div class="job-listing">
            <div class="table-header">
                <div class="one">Job Title</div>
                <div class="two">Candidates</div>
                <div class="three">Job Status</div>
                <div class="four">Action</div>
            </div>
            <div id="jobResults">
                <?php if (empty($jobs)): ?>
                    <div class="job-item">
                        <div colspan="4" class="text-center">No jobs found. Create your first listing now!</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($jobs as $job): ?>
                        <div class="job-item">
                            <div>
                                <strong><?= htmlspecialchars($job['title']) ?></strong><br>
                                <small><?= htmlspecialchars($job['description']) ?></small><br>
                                <small>Created: <?= htmlspecialchars(date('Y-m-d', strtotime($job['posted_date']))) ?> - Ends: <?= htmlspecialchars(date('Y-m-d', strtotime($job['expiry_date']))) ?></small>
                            </div>
                            <div>
                                <span><?= $job['pending_count'] ?> Pending</span> | 
                                <span><?= $job['awaiting_count'] ?> Awaiting</span> | 
                                <span><?= $job['accepted_count'] ?> Accepted</span>
                            </div>
                            <div>
                                <select class="form-select job-status-dropdown" data-job-id="<?= $job['job_id'] ?>" onchange="updateJobStatus(this)">
                                    <option value="active" <?= $job['status'] == 'active' ? 'selected' : '' ?>>● Active</option>
                                    <option value="paused" <?= $job['status'] == 'paused' ? 'selected' : '' ?>>● Paused</option>
                                    <option value="inactive" <?= $job['status'] == 'inactive' ? 'selected' : '' ?>>● Inactive</option>
                                </select>
                            </div>
                            <div>
                            <button class="action-btn" data-bs-toggle="modal" data-bs-target="#editJobModal" data-job-id="<?= $job['job_id'] ?>">Edit</button>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

<!-- Candidates Tab -->
<section id="candidates" class="content hidden">
        <!-- Job Filters Section -->
        <section id="job-filters" class="job-filters hidden">
            <!-- add functionality later nlng -->
            <div class="filter-tabs" hidden>
                <button class="tab-btn active">Applicants (17)</button>
                <button class="tab-btn">Matched Applicant</button>
            </div>

            <!-- Candidates Table -->
            <div class="status-filters">
                <span class="status-link active">17 Active</span>
                <span>12 Awaiting review</span>
                <span>2 Reviewed</span>
                <span>2 Contacted</span>
                <span>0 Hired</span>
                <span>22 Rejected</span>
            </div>
                    <!-- Dynamic Job Dropdown -->
                <select id="jobDropdown" class="job-position" onchange="fetchCandidates(this.value)">
                    <option value="">-- Select a Job --</option>
                    <?php foreach ($jobs_dropdown as $job): ?>
                    <option value="<?= $job['job_id'] ?>"><?= htmlspecialchars($job['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            <div class="filter-controls">
                <select class="filter-dropdown">
                    <option>Screener questions: Any</option>
                    <option>Answered</option>
                    <option>Not Answered</option>
                </select>

                <select class="filter-dropdown">
                    <option>Assessment: Any</option>
                    <option>Passed</option>
                    <option>Failed</option>
                </select>

                <select class="sort-dropdown">
                    <option>Sort: Apply date (Newest)</option>
                    <option>Sort: Apply date (Oldest)</option>
                    <option>Sort: Relevance</option>
                </select>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Candidate Name</th>
                        <th>Email</th>
                        <th>Application Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="candidatesTableBody">
                    <tr>
                        <td colspan="4" class="text-center">Select a job to view candidates.</td>
                    </tr>
                </tbody>
            </table>

            
        </section>
    </section>


<!-- Post a Job Tab-->
<section id="post-job" class="content hidden">
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <h2>Create Job Listing</h2>
                    <?php if (!$is_verified): ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="bx bx-info-circle"></i> Your company needs to be verified before you can post jobs. 
                            <a href="#" class="alert-link" data-bs-toggle="modal" data-bs-target="#verificationModal">Please submit your business permit for verification</a>.
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $_SESSION['success'] ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error'] ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <form action="../includes/company/comp_job_process.php" method="POST">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jobTitle">Job Title</label>
                                    <input type="text" class="form-control" id="jobTitle" name="title" placeholder="Example: Housekeeping Attendant" required>
                                </div>
                                <div class="form-group">
                                    <label for="jobCategory">Job Category</label>
                                    <select class="form-control" id="jobCategory" name="category_id" required>
                                        <option value="">Select Job Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['category_id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Employment Type</label><br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="employment_type" value="Part-Time" checked>
                                                <label class="form-check-label">Part-time</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="employment_type" value="Full-Time">
                                                <label class="form-check-label">Full-time</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="employment_type" value="Internship">
                                                <label class="form-check-label">Internship</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="employment_type" value="Contract">
                                                <label class="form-check-label">Contract</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="jobDescription">Job Description</label>
                                    <textarea class="form-control" id="jobDescription" name="description" rows="5" required></textarea>
                                </div>
                            </div>
                            <!-- Right Column -->
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" required>
                                </div>
                                <div class="form-group">
                                    <label for="rateDetails">Rate Details</label>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <label for="currency">Currency</label>
                                            <input type="text" class="form-control" id="currency" name="currency" placeholder="e.g. PHP/USD" required>
                                        </div>
                                        <div class="mr-2">
                                            <label for="salary_min">Min Rate</label>
                                            <input type="number" class="form-control" id="salary_min" name="salary_min" placeholder="Min Rate" required>
                                        </div>
                                        <div>
                                            <label for="salary_max">Max Rate</label>
                                            <input type="number" class="form-control" id="salary_max" name="salary_max" placeholder="Max Rate" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="expiry_date">Expiry Date</label>
                                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="requirements">Requirements</label>
                                    <textarea class="form-control" id="requirements" name="requirements" rows="5" required></textarea>
                                </div>
                                <div class="form-group text-end" style="padding-top: 20px;">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<!-- Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verificationModalLabel">Verification Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are currently not verified to post jobs. Please upload your business permit for verification.</p>
                <form action="../includes/company/comp_verification_process.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="business_permit" class="form-label">Business Permit (PDF)</label>
                        <input type="file" class="form-control" id="business_permit" name="business_permit" accept="application/pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit for Verification</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function handleJobPost(event) {
        event.preventDefault();
        <?php if (!$is_verified): ?>
            // Show verification modal if company is not verified
            var verificationModal = new bootstrap.Modal(document.getElementById('verificationModal'));
            verificationModal.show();
            return;
        <?php endif; ?>
        
        // If verified, proceed with job post
        const alertBox = document.getElementById('jobPostAlert');
        alertBox.classList.remove('d-none');
        setTimeout(() => {
            alertBox.classList.add('d-none');
        }, 3000);
    }
</script>

    <!-- Edit Job Modal -->
    <div class="modal fade" id="editJobModal" tabindex="-1" aria-labelledby="editJobModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editJobModalLabel">Edit Job Listing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editJobForm">
                        <input type="hidden" name="job_id" id="editJobId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editJobTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="editJobTitle" name="title" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editJobType" class="form-label">Employment Type</label>
                                <select class="form-select" id="editJobType" name="employment_type" required>
                                    <option value="Part-Time">Part-Time</option>
                                    <option value="Full-Time">Full-Time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Temporary">Temporary</option>
                                    <option value="Internship">Internship</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editJobLocation" class="form-label">Location</label>
                                <input type="text" class="form-control" id="editJobLocation" name="location" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editJobCategory" class="form-label">Category</label>
                                <select class="form-select" id="editJobCategory" name="category_id" required>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editJobSalaryMin" class="form-label">Salary Min</label>
                                <input type="number" class="form-control" id="editJobSalaryMin" name="salary_min" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editJobSalaryMax" class="form-label">Salary Max</label>
                                <input type="number" class="form-control" id="editJobSalaryMax" name="salary_max" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editJobCurrency" class="form-label">Currency</label>
                                <input type="text" class="form-control" id="editJobCurrency" name="currency" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editJobExpiryDate" class="form-label">Expiry Date</label>
                                <input type="date" class="form-control" id="editJobExpiryDate" name="expiry_date" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editJobDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editJobDescription" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editJobRequirements" class="form-label">Requirements</label>
                            <textarea class="form-control" id="editJobRequirements" name="requirements" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../fortest/js/jquery.min.js"></script>
        <script src="script/script.js"></script>   
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function updateTime() {
                const options = { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                const currentTime = new Date().toLocaleTimeString('en-US', options);
                document.getElementById('currentTime').textContent = currentTime;
            }
            setInterval(updateTime, 1000);
            updateTime();
        </script>
        <script>
            let currentSortOrder = '<?= $sort_order ?>';
            let currentSearchQuery = '<?= isset($_GET['search']) ? $_GET['search'] : '' ?>';

            function updateSort() {
                const sortBy = document.getElementById('sortBy').value;
                fetchJobs(sortBy, currentSortOrder, currentSearchQuery);
            }

            function toggleSortOrder() {
                currentSortOrder = currentSortOrder === 'desc' ? 'asc' : 'desc';
                document.getElementById('sortOrderIndicator').textContent = currentSortOrder === 'desc' ? 'Descending' : 'Ascending';
                fetchJobs(document.getElementById('sortBy').value, currentSortOrder, currentSearchQuery);
            }

            function searchJobs() {
                const searchInput = document.getElementById('searchInput');
                currentSearchQuery = searchInput.value;
                fetchJobs(document.getElementById('sortBy').value, currentSortOrder, currentSearchQuery);
            }

            function fetchJobs(sortBy, sortOrder, searchQuery) {
                fetch(`fetch_jobs.php?sort_by=${sortBy}&sort_order=${sortOrder}&search=${searchQuery}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('jobResults').innerHTML = html;
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Add event listener for search input
            document.getElementById('searchInput').addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    searchJobs();
                }
            });
        </script>
</body>
</html>
