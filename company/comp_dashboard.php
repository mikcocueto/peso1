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

    <?php include 'comp_navbar&tab.php'; ?>
      
    <!-- Navigation Tabs -->
    
   
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
        .notification-dropdown {
            position: relative;
            display: inline-block;
            margin-right: 10px;
        }

        .notification-badge {
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

        .notification-dropdown i {
            cursor: pointer;
            color: white;
            font-size: 1.5rem;
            position: relative;
        }

        .notification-content {
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

        .notification-content.show {
            display: block;
        }

        .notification-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h5 {
            margin: 0;
            font-size: 1rem;
        }

        .mark-all-read {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: flex-start;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #f0f7ff;
        }

        .notification-icon {
            margin-right: 10px;
            color: #007bff;
        }

        .notification-details {
            flex: 1;
        }

        .notification-details p {
            margin: 0;
            font-size: 0.9rem;
            color: #333;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #666;
        }

        .notification-footer {
            padding: 10px 15px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .view-all {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .view-all:hover {
            text-decoration: underline;
        }

        .message-dropdown {
            position: relative;
            display: inline-block;
            margin-right: 10px;
        }

        .message-badge {
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

        .message-dropdown i {
            cursor: pointer;
            color: white;
            font-size: 1.5rem;
            position: relative;
        }

        .message-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 350px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            z-index: 1000;
            margin-top: 10px;
        }

        .message-content.show {
            display: block;
        }

        .message-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .message-header h5 {
            margin: 0;
            font-size: 1rem;
        }

        .message-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .message-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: flex-start;
            cursor: pointer;
        }

        .message-item:hover {
            background-color: #f8f9fa;
        }

        .message-item.unread {
            background-color: #f0f7ff;
        }

        .message-avatar {
            margin-right: 15px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
        }

        .message-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .message-details {
            flex: 1;
        }

        .message-sender {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .message-details p {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .message-time {
            font-size: 0.8rem;
            color: #999;
        }

        .message-footer {
            padding: 10px 15px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .job-listing {
            width: 100%;
            margin-top: 20px;
        }

        .table-header {
            display: grid;
            grid-template-columns: 400px 200px 150px 100px;
            padding: 15px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
        }

        .table-header .one,
        .table-header .two,
        .table-header .three,
        .table-header .four {
            padding: 0 10px;
        }

        .job-item {
            display: grid;
            grid-template-columns: 400px 200px 150px 100px;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            align-items: start;
        }

        .job-title-column {
            width: 400px;
            overflow: hidden;
            padding: 0 10px;
        }

        .job-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .job-description {
            width: 100%;
            max-height: 3em;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            margin: 5px 0;
            color: #666;
            font-size: 0.9em;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .job-dates {
            font-size: 0.8em;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .candidates-column {
            width: 200px;
            padding: 0 10px;
            white-space: nowrap;
            overflow: visible;
            text-overflow: clip;
        }

        .status-column {
            width: 150px;
            padding: 0 10px;
        }

        .action-column {
            width: 100px;
            padding: 0 10px;
            text-align: center;
        }

        .job-status-dropdown {
            width: 100%;
            padding: 5px;
        }

        .action-btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
        }

        .action-btn:hover {
            background-color: #0056b3;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 1px solid #dee2e6;
            background-color: white;
            color: #495057;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
            overflow: visible;
            text-overflow: clip;
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
        }

        .filter-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .filter-btn.outline-primary {
            color: #007bff;
            border-color: #007bff;
        }

        .filter-btn.outline-primary:hover {
            background-color: #007bff;
            color: white;
        }

        .filter-btn.outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
        }

        .filter-btn.outline-secondary:hover {
            background-color: #6c757d;
            color: white;
        }

        /* Add these styles for status filters and filter controls */
        .status-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .status-link {
            padding: 8px 16px;
            border: 1px solid #dee2e6;
            background-color: white;
            color: #495057;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
            overflow: visible;
            text-overflow: clip;
            transition: all 0.2s ease;
        }

        .status-link:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
        }

        .status-link.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .filter-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .filter-dropdown,
        .sort-dropdown {
            padding: 8px 16px;
            border: 1px solid #dee2e6;
            background-color: white;
            color: #495057;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
            overflow: visible;
            text-overflow: clip;
            min-width: 200px;
        }

        /* Hide scrollbar but keep functionality */
        .status-filters::-webkit-scrollbar,
        .filter-controls::-webkit-scrollbar {
            height: 6px;
        }

        .status-filters::-webkit-scrollbar-track,
        .filter-controls::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .status-filters::-webkit-scrollbar-thumb,
        .filter-controls::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .status-filters::-webkit-scrollbar-thumb:hover,
        .filter-controls::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    <script>
        function toggleHamburgerMenu() {
            const tabsContainer = document.getElementById('tabsContainer');
            tabsContainer.classList.toggle('d-none');
        }
    </script>

<section id="dashboard" class="content active">
    <h1 class="h3 mb-3"><strong>Dashboard Overview</strong></h1>
    <div class="container-fluid p-0">
        <!-- Company Overview and Recent Movement Row -->
        <div class="row mb-4">
            <!-- Company Details Column -->
            <div class="col-xl-6">
                <div class="card border-0 bg-transparent">
                    <div class="card-body position-relative">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="d-flex align-items-center gap-5">
                                <div class="text-center" style="width: 200px;">
                                    <img src="../assets/images/fds.jpg" class="img-fluid" alt="Company Logo" style="max-height: 200px; object-fit: contain;">
                                </div>
                                <div class="company-info">
                                    <h3 class="mb-4 px-3 py-2" style="background-color: #f8f9fa; border-radius: 8px;">Company Information</h3>
                                    <p class="card-text">Company Name: FDS Asya Philippines</p>
                                    <p class="card-text">Country: Philippines</p>
                                    <p class="card-text"><strong>Company Address:</strong> San Pablo City</p>
                                    <p class="card-text"><strong>Company Hotline:</strong> 4444 444</p>
                                    <p class="card-text"><strong>Company Number:</strong> 0912-345-6789</p>
                                    <p class="card-text">Human Resource: John Doe</p>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>

            <!-- Recent Movement Column -->
            <div class="col-xl-6">
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
        </div>

        <!-- Analytics Dashboard Row -->
        <div class="row mt-4">
            <div class="col-12 mb-3">
                <h3 class="px-3 py-2" style="background-color: #f8f9fa; border-radius: 8px; display: inline-block;">Dashboard Analytics</h3>
            </div>
            <div class="col-xl-3">
                <div class="card shadow-sm">
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
                        <h1 class="mt-1 mb-3" style="font-size: 2.5rem; font-weight: 700;">2.382</h1>
                        <div class="mb-0">
                            <span class="text-danger">❌ -3.65%</span>
                            <span class="text-muted">Since last week</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title">Posted Jobs</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat text-primary">
                                    <i class="align-middle" data-feather="dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3" style="font-size: 2.5rem; font-weight: 700;">21.300</h1>
                        <div class="mb-0">
                            <span class="text-success">✅ 6.65%</span>
                            <span class="text-muted">Since last week</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card shadow-sm">
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
                        <h1 class="mt-1 mb-3" style="font-size: 2.5rem; font-weight: 700;">14.212</h1>
                        <div class="mb-0">
                            <span class="text-success">✅ 5.25%</span>
                            <span class="text-muted">Since last week</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card shadow-sm">
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
                        <h1 class="mt-1 mb-3" style="font-size: 2.5rem; font-weight: 700;">64</h1>
                        <div class="mb-0">
                            <span class="text-danger">❌ -2.25%</span>
                            <span class="text-muted">Since last week</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chart Script -->
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

    <!-- Job posted list Tab -->
    <section id="jobs" class="content active">
        <!-- Filters -->
    <section class="filters">
    <div class="container">
        <div class="row mb-3 align-items-center">
             <!-- Filter Buttons -->
            <div class="row mb-3">
                <div class="col-md-6 d-flex">
                    <button class="filter-btn outline-primary me-2">Open and Paused (#)</button>
                    <button class="filter-btn outline-secondary">Closed (#)</button>
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
                    <button class="filter-btn outline-secondary" onclick="toggleSortOrder()">
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
                            <div class="job-title-column">
                                <span class="job-title"><?= htmlspecialchars($job['title']) ?></span>
                                <div class="job-description"><?= htmlspecialchars($job['description']) ?></div>
                                <span class="job-dates">Created: <?= htmlspecialchars(date('Y-m-d', strtotime($job['posted_date']))) ?> - Ends: <?= htmlspecialchars(date('Y-m-d', strtotime($job['expiry_date']))) ?></span>
                            </div>
                            <div class="candidates-column">
                                <span><?= $job['pending_count'] ?> Pending</span> | 
                                <span><?= $job['awaiting_count'] ?> Awaiting</span> | 
                                <span><?= $job['accepted_count'] ?> Accepted</span>
                            </div>
                            <div class="status-column">
                                <select class="form-select job-status-dropdown" data-job-id="<?= $job['job_id'] ?>" onchange="updateJobStatus(this)">
                                    <option value="active" <?= $job['status'] == 'active' ? 'selected' : '' ?> style="color: #28a745;">🟢 Active</option>
                                    <option value="paused" <?= $job['status'] == 'paused' ? 'selected' : '' ?> style="color: #ffc107;">🟡 Paused</option>
                                    <option value="inactive" <?= $job['status'] == 'inactive' ? 'selected' : '' ?> style="color: #dc3545;">🔴 Inactive</option>
                                </select>
                            </div>
                            <div class="action-column">
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
                <span class="status-link">12 Awaiting review</span>
                <span class="status-link">2 Reviewed</span>
                <span class="status-link">2 Contacted</span>
                <span class="status-link">0 Hired</span>
                <span class="status-link">22 Rejected</span>
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
<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="mb-4">Create Job Listing</h2>
                    <?php if (!$is_verified): ?>
                        <div class="alert alert-warning mb-4" role="alert">
                            <i class="bx bx-info-circle"></i> Your company needs to be verified before you can post jobs. 
                            <a href="#" class="alert-link" data-bs-toggle="modal" data-bs-target="#verificationModal">Please submit your business permit for verification</a>.
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success mb-4" role="alert">
                            <?= $_SESSION['success'] ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger mb-4" role="alert">
                            <?= $_SESSION['error'] ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <form action="../includes/company/comp_job_process.php" method="POST">
                        <div class="row g-4">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="jobTitle" class="form-label">Job Title</label>
                                    <input type="text" class="form-control form-control-lg" id="jobTitle" name="title" placeholder="Example: Housekeeping Attendant" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="jobCategory" class="form-label">Job Category</label>
                                    <select class="form-select form-select-lg" id="jobCategory" name="category_id" required>
                                        <option value="">Select Job Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['category_id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label">Employment Type</label><br>
                                    <div class="row g-3">
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
                                
                                <div class="form-group mb-4">
                                    <label for="jobDescription" class="form-label">Job Description</label>
                                    <textarea class="form-control" id="jobDescription" name="description" rows="6" required></textarea>
                                </div>
                            </div>
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control form-control-lg" id="location" name="location" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="rateDetails" class="form-label">Rate Details</label>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="currency" class="form-label">Currency</label>
                                            <input type="text" class="form-control" id="currency" name="currency" placeholder="e.g. PHP/USD" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="salary_min" class="form-label">Min Rate</label>
                                            <input type="number" class="form-control" id="salary_min" name="salary_min" placeholder="Min Rate" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="salary_max" class="form-label">Max Rate</label>
                                            <input type="number" class="form-control" id="salary_max" name="salary_max" placeholder="Max Rate" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control form-control-lg" id="expiry_date" name="expiry_date" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="requirements" class="form-label">Requirements</label>
                                    <textarea class="form-control" id="requirements" name="requirements" rows="6" required></textarea>
                                </div>
                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">Submit</button>
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

            function updateJobStatusColors() {
                const statusDropdowns = document.querySelectorAll('.job-status-dropdown');
                statusDropdowns.forEach(dropdown => {
                    const selectedOption = dropdown.options[dropdown.selectedIndex];
                    const status = selectedOption.value;
                    let color;
                    
                    switch(status) {
                        case 'active':
                            color = '#28a745';
                            break;
                        case 'paused':
                            color = '#ffc107';
                            break;
                        case 'inactive':
                            color = '#dc3545';
                            break;
                    }
                    
                    dropdown.style.color = color;
                    selectedOption.style.color = color;
                });
            }

            // Call the function when the page loads
            document.addEventListener('DOMContentLoaded', updateJobStatusColors);

            // Update the fetchJobs function to maintain colors
            function fetchJobs(sortBy, sortOrder, searchQuery) {
                fetch(`fetch_jobs.php?sort_by=${sortBy}&sort_order=${sortOrder}&search=${searchQuery}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('jobResults').innerHTML = html;
                        // Add a small delay to ensure the DOM is updated
                        setTimeout(updateJobStatusColors, 100);
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Add event listener for search input
            document.getElementById('searchInput').addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    searchJobs();
                }
            });

            // Add event listener for status dropdown changes
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('job-status-dropdown')) {
                    updateJobStatusColors();
                }
            });
        </script>
        <script>
            function toggleNotification() {
                const notificationContent = document.getElementById('notificationContent');
                notificationContent.classList.toggle('show');
            }

            // Close notification dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const notificationDropdown = document.querySelector('.notification-dropdown');
                const notificationContent = document.getElementById('notificationContent');
                
                if (!notificationDropdown.contains(event.target) && notificationContent.classList.contains('show')) {
                    notificationContent.classList.remove('show');
                }
            });

            // Mark all notifications as read
            document.querySelector('.mark-all-read').addEventListener('click', function() {
                const unreadNotifications = document.querySelectorAll('.notification-item.unread');
                unreadNotifications.forEach(notification => {
                    notification.classList.remove('unread');
                });
            });

            // Mark individual notification as read when clicked
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.remove('unread');
                });
            });
        </script>
        <script>
            function toggleMessage() {
                const messageContent = document.getElementById('messageContent');
                messageContent.classList.toggle('show');
            }

            // Close message dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const messageDropdown = document.querySelector('.message-dropdown');
                const messageContent = document.getElementById('messageContent');
                
                if (!messageDropdown.contains(event.target) && messageContent.classList.contains('show')) {
                    messageContent.classList.remove('show');
                }
            });

            // Mark all messages as read
            document.querySelectorAll('.mark-all-read').forEach(button => {
                button.addEventListener('click', function() {
                    const container = this.closest('.message-content, .notification-content');
                    const unreadItems = container.querySelectorAll('.unread');
                    unreadItems.forEach(item => {
                        item.classList.remove('unread');
                    });
                    
                    // Update badge count
                    if (container.classList.contains('message-content')) {
                        const messageBadge = document.querySelector('.message-badge');
                        messageBadge.textContent = '0';
                        messageBadge.style.display = 'none';
                    } else {
                        const notificationBadge = document.querySelector('.notification-badge');
                        notificationBadge.textContent = '0';
                        notificationBadge.style.display = 'none';
                    }
                });
            });

            // Mark individual message as read when clicked
            document.querySelectorAll('.message-item').forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.remove('unread');
                    // Update badge count
                    const messageBadge = document.querySelector('.message-badge');
                    const currentCount = parseInt(messageBadge.textContent);
                    if (currentCount > 1) {
                        messageBadge.textContent = (currentCount - 1).toString();
                    } else {
                        messageBadge.style.display = 'none';
                    }
                });
            });
        </script>
</body>
</html>
    