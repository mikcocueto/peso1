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

// Fetch posted jobs by the logged-in company along with candidate counts
$query = "SELECT jl.job_id, jl.title, jl.description, jl.posted_date, jl.expiry_date, jl.status,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'pending') AS pending_count,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'awaiting') AS awaiting_count,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'accepted') AS accepted_count
          FROM tbl_job_listing jl 
          WHERE jl.employer_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
$jobs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="canonical" href="https://demo-basic.adminkit.io/charts-chartjs.html" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        /* Navbar */
        .navbar {
            background: #6c63ff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            color: white;

        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            width: 120px;
        }

        .navbar-icons {
            display: flex;
            gap: 20px;
            font-size: 24px;
            margin-left: auto; /* Align to the right */
        }

        .navbar-icons i {
            cursor: pointer;
        }

        /* Tabs */
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
        }

        .tab.active {
            border-bottom: 2px solid black;
        }

        /* Filters Job tab */
        .filters {
            background: white;
            padding: 15px;
            display: flex;
            justify-content: space-between; /* Separate left and right sections */
            align-items: center;
            border-bottom: 2px solid #ddd;
        }

        .filters .left-filters,
        .filters .right-filters {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-btn {
            background: #eee;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }

        .filter-btn.active {
            font-weight: bold;
        }


        /* Content Sections */
        .content {
            display: none;
            padding: 15px;
        }

        .content.active {
            display: block;
        }

        /* Job Listing */
        .job-listing {
            background: white;
            margin: 15px;
            padding: 15px;
            border-radius: 5px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Center content vertically */
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            padding: 10px 0;
        }
        .two{
            padding-left: 250px;
        }
        .three{
            padding-left: 100px;
        }

        .job-item {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Center content vertically */
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .job-status select {
            padding: 5px;
            border-radius: 4px;
        }

        .action-btn {
            background: #666;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .action-btn:hover {
            background: #444;
        }

        /* Applicants Table */
        .applicant-table {
            width: 100%;
            background: white;
            margin: 15px 0;
            border-radius: 5px;
            overflow: hidden;
            border-collapse: collapse;
        }

        .applicant-table th, .applicant-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .applicant-table th {
            background: #ddd;
            font-weight: bold;
        }

        .icon-actions {
            display: flex;
            gap: 10px;
        }

        .icon-btn {
            border: none;
            background: none;
            font-size: 18px;
            cursor: pointer;
        }
        /* Job Filters Section */
.job-filters {
    background: #ffffff;
    padding: 15px;
    margin: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Job Dropdown */
.job-position {
    font-size: 16px;
    padding: 8px;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.tab-btn {
    padding: 8px 12px;
    border: 1px solid #ccc;
    background: #f4f4f4;
    border-radius: 4px;
    cursor: pointer;
}

.tab-btn.active {
    background: #d1d1d1;
    font-weight: bold;
}

/* Status Filters */
.status-filters {
    display: flex;
    gap: 15px;
    margin-top: 10px;
    font-size: 14px;
}

.status-filters span {
    cursor: pointer;
    color: #444;
}

.status-filters .status-link {
    color: blue;
    text-decoration: underline;
}

.status-filters .status-link.active {
    font-weight: bold;
}

/* Filters & Sorting */
.filter-controls {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

.filter-dropdown,
.sort-dropdown {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

        .icon-btn.check { color: green; }
        .icon-btn.question { color: gray; }
        .icon-btn.close { color: red; }

        /* Hide job-filters by default */
        .job-filters.hidden {
            display: none;
        }
        /* Navbar Styling */
.navbar2 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #333;
    padding: 10px 20px;
    color: white;
}

/* Align right icons */
.nav-right {
    display: flex;
    gap: 20px; /* Space between icons */
}

.icon {
    color: white;
    font-size: 20px;
    text-decoration: none;
    transition: 0.3s;
}

.icon:hover {
    color: #f0a500;
}

/* Column layout for PESO for Company */
.navbar-brand div {
    display: flex;
    flex-direction: column;
}


        .job-status-dropdown option[value="active"] {
            color: green;
        }
        .job-status-dropdown option[value="paused"] {
            color: orange;
        }
        .job-status-dropdown option[value="inactive"] {
            color: red;
        }
        .job-status-dropdown {
            color: inherit; 
        }
        .sort-btn, .order-btn{
            background: #eee;
            border: 1px solid #ccc; /* Add stroke line */
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px; /* Add border radius */
            transition: transform 0.2s; /* Add transition for pop-out effect */
        }

        .sort-btn:hover, .order-btn:hover {
            transform: scale(1.05); /* Pop-out effect on hover */
        }

        
        
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <img src="../fortest/images/peso_icons.png" alt="PESO Logo">
            <div>
                <span style="font-size: 1.5rem; font-weight: bold; color: white;">PESO</span>
                <span style="font-size: 1.5rem; font-weight: bold; padding-left: 35px; color: white;"> for Company</span>
            </div>
        </div>
        <div class="navbar-icons">
            <i class="bx bx-bell"></i>
            <i class="bx bx-chat"></i>
            <i class="bx bx-user"></i>
        </div>
    </nav>

    <!-- Navigation Tabs -->
    <nav class="tabs">
        <button class="tab active" data-tab="dashboard" onclick="switchTab('dashboard')">Dashboard</button>
        <button class="tab" data-tab="jobs" onclick="switchTab('jobs')">Jobs</button>
        <button class="tab" data-tab="candidates" onclick="switchTab('candidates')">Candidates</button>
    </nav>
    <!-- Candidates tab-->
    <!-- Job Filters Section (Placed After Tabs) -->
    <section id="job-filters" class="job-filters hidden">
        <!-- This section contains job filters and should be hidden when in the dashboard tab -->
        <select class="job-position">
            <option>Customer Service Representative</option>
            <option>IT Support Specialist</option>
            <option>Sales Associate</option>
        </select>

        <div class="filter-tabs">
            <button class="tab-btn active">Applicants (17)</button>
            <button class="tab-btn">Matched Applicant</button>
        </div>

        <div class="status-filters">
            <span class="status-link active">17 Active</span>
            <span>12 Awaiting review</span>
            <span>2 Reviewed</span>
            <span>2 Contacted</span>
            <span>0 Hired</span>
            <span>22 Rejected</span>
        </div>

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
    </section>

<!-- Dashboard Tab -->
<section >
    <div class="dashboard-content">
        <h2>Welcome to the Company Dashboard</h2>
        <p>Here you can manage your job postings, view candidates, and more.</p>
        <!-- Add more dashboard-specific content here -->
        
    </div>
</section>

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
					</div>


                    <div style="margin-top: 20px;"></div> <!-- Added space -->
					<div class="row">
						<div class="col-12 col-lg-8 col-xxl-9 d-flex">
							<div class="card flex-fill">
								<div class="card-header">

									<h5 class="card-title mb-0">Latest Projects</h5>
								</div>
								<table class="table table-hover my-0">
									<thead>
										<tr>
											<th>Name</th>
											<th class="d-none d-xl-table-cell">Start Date</th>
											<th class="d-none d-xl-table-cell">End Date</th>
											<th>Status</th>
											<th class="d-none d-md-table-cell">Assignee</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Project Apollo</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Vanessa Tucker</td>
										</tr>
										<tr>
											<td>Project Fireball</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-danger">Cancelled</span></td>
											<td class="d-none d-md-table-cell">William Harris</td>
										</tr>
										<tr>
											<td>Project Hades</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Sharon Lessman</td>
										</tr>
										<tr>
											<td>Project Nitro</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-warning">In progress</span></td>
											<td class="d-none d-md-table-cell">Vanessa Tucker</td>
										</tr>
										<tr>
											<td>Project Phoenix</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">William Harris</td>
										</tr>
										<tr>
											<td>Project X</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Sharon Lessman</td>
										</tr>
										<tr>
											<td>Project Romeo</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Christina Mason</td>
										</tr>
										<tr>
											<td>Project Wombat</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-warning">In progress</span></td>
											<td class="d-none d-md-table-cell">William Harris</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
                        <div class="col-12 col-lg-4 col-xxl-3 d-flex">
							<div class="card flex-fill w-100">
								<div class="card-header">

									<h5 class="card-title mb-0">Monthly Applicants</h5>
								</div>
								<div class="card-body d-flex w-100">
									<div class="align-self-center chart chart-lg">
										<canvas id="chartjs-dashboard-bar"></canvas>
									</div>
								</div>
							</div>
						</div>
										<!-- Other cards remain unchanged -->
									</div>
								</div>
							</div>
						</div>

						<!-- Other sections remain unchanged -->

					</div>
				</section>


    <!-- Jobs Tab -->
    <section id="jobs" class="content active">
        <!-- Filters -->
    <section class="filters">
        <div class="left-filters">
            <button class="filter-btn OP">Open and Paused (#)</button>
            <button class="filter-btn">Closed (#)</button>
            <input type="text" placeholder="Search job title">
            <input type="text" placeholder="Search location">
        </div>
        <div class="right-filters">
            <button class="sort-btn">Sort by Posting Date</button>
            <button class="order-btn">Order: Descending</button>
        </div>
    </section>
        <div class="job-listing">
            <div class="table-header">
                <div class="one">Job Title</div>
                <div class="two">Candidates</div>
                <div class="three">Job Status</div>
                <div class="four">Action</div>
            </div>
            <?php if (empty($jobs)): ?>
                <div class="job-item">
                    <div colspan="4" class="text-center">No jobs posted yet.</div>
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
    </section>

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

    <!-- Candidates Tab -->
<section id="candidates" class="content active">
    <div class="candidates-content">
        <h2>Candidate Management</h2>
        <p>Here you can view and manage candidates who have applied for your job postings.</p>
        <!-- Add more candidates-specific content here -->
    </div>
</section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fortest/js/jquery.min.js"></script>
    <script>
        
        $('#editJobModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var jobId = button.data('job-id');

            // Fetch job details using AJAX
            $.ajax({
                url: '../includes/company/comp_get_job_details.php',
                type: 'GET',
                data: { job_id: jobId },
                success: function (data) {
                    var job = JSON.parse(data);
                    $('#editJobId').val(job.job_id);
                    $('#editJobTitle').val(job.title);
                    $('#editJobDescription').val(job.description);
                    $('#editJobRequirements').val(job.requirements);
                    $('#editJobType').val(job.employment_type);
                    $('#editJobLocation').val(job.location);
                    $('#editJobSalaryMin').val(job.salary_min);
                    $('#editJobSalaryMax').val(job.salary_max);
                    $('#editJobCurrency').val(job.currency);
                    $('#editJobCategory').val(job.category_id);
                    $('#editJobExpiryDate').val(job.expiry_date);

                    // Set the selected employment type
                    $('#editJobType').val(job.employment_type);

                    // Set the expiry date in the correct format
                    $('#editJobExpiryDate').val(new Date(job.expiry_date).toISOString().split('T')[0]);
                }
            });
        });

        $('#editJobForm').on('submit', function (event) {
            event.preventDefault();

            // Update job details using AJAX
            $.ajax({
                url: '../includes/company/comp_update_job_details.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert('Job details updated successfully!');
                    location.reload();
                }
            });
        });

        function updateJobStatus(selectElement) {
            var jobId = $(selectElement).data('job-id');
            var status = $(selectElement).val();

            $.ajax({
                url: '../includes/company/comp_update_job_status.php',
                type: 'POST',
                data: { job_id: jobId, status: status },
                success: function (response) {
                    alert('Job status updated successfully!');
                    location.reload();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = document.querySelector('.tab.active').getAttribute('data-tab');
            switchTab(activeTab);
        });

        function switchTab(tabId) {
            document.querySelectorAll('.content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(button => button.classList.remove('active'));

            var targetTab = document.getElementById(tabId);
            if (targetTab) {
                targetTab.classList.add('active');
                document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');
            }

            // Hide job-filters section when in Dashboard or Job Tab
            if (tabId === 'dashboard' || tabId === 'jobs') {
                document.getElementById('job-filters').classList.add('hidden');
            } else {
                document.getElementById('job-filters').classList.remove('hidden');
            }
        }
    </script>   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
