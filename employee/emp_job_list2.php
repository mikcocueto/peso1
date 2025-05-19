<?php
require "../includes/db_connect.php";

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: emp_reg&login.php");
    die();
}

$user_id = $_SESSION['user_id'];

// Fetch job categories from the database
$categories_result = $conn->query("SELECT category_id, category_name FROM tbl_job_category");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row;
}

// Handle search
$search_title = isset($_GET['search_title']) ? $_GET['search_title'] : '';
$search_category = isset($_GET['search_category']) ? $_GET['search_category'] : [];
$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : '';

$query = "SELECT jl.job_id, jl.title, jl.employment_type, c.companyName, c.comp_logo_dir 
          FROM tbl_job_listing jl 
          JOIN tbl_comp_info c ON jl.employer_id = c.company_id 
          WHERE jl.status = 'active'";

if ($search_title) {
    $query .= " AND (jl.title LIKE '%$search_title%' OR c.companyName LIKE '%$search_title%')";
}

if (!empty($search_category)) {
    $category_ids = implode(',', array_map('intval', $search_category));
    $query .= " AND jl.category_id IN ($category_ids)";
}

if ($search_type) {
    $query .= " AND jl.employment_type = '$search_type'";
}

$jobs = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fortest/style2/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../fortest/style2/custom-bs.css">
    <link rel="stylesheet" href="../fortest/style2/jquery.fancybox.min.css">
    <link rel="stylesheet" href="../fortest/style2/bootstrap-select.min.css">
    <link rel="stylesheet" href="../fortest/fonts/icomoon/style.css">
    <link rel="stylesheet" href="../fortest/fonts/line-icons/style.css">
    <link rel="stylesheet" href="../fortest/style2/owl.carousel.min.css">
    <link rel="stylesheet" href="../fortest/style2/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body {
            background-image: url('../fortest/images/7lakes.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .site-navbar {
            position: relative;
            z-index: 1000;            
            padding: 20px 0;
        }

        .site-navbar .container-fluid {
            padding: 0 60px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .site-navbar .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .site-navbar .site-logo {
            flex: 0 0 250px;
        }

        .site-navbar .site-logo img {
            width: 120px;
            height: auto;
            margin-right: 15px;
            object-fit: contain;
        }

        .site-navbar .site-logo span {
            font-size: 24px;
            font-weight: 600;
            color: #fff;
        }

        .site-navbar .site-navigation {
            flex: 1;
            display: flex;
            justify-content: center;
            margin: 0 40px;
        }

        .site-navbar .site-menu {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
            list-style: none;
            gap: 20px;
        }

        .site-navbar .site-menu li {
            margin: 0;
        }

        .site-navbar .site-menu a {
            color: #fff;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
            white-space: nowrap;
        }

        .site-navbar .site-menu a:hover {
            color: #e3f2fd;
        }

        .site-navbar .site-menu .active a {
            color: #e3f2fd;
        }

        .site-navbar .right-cta-menu {
            flex: 0 0 300px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 15px;
        }

        .site-navbar .btn-outline-white {
            color: #fff;
            border-color: #fff;
            padding: 10px 25px;
            font-weight: 500;
            font-size: 14px;
            white-space: nowrap;
        }

        .site-navbar .btn-outline-white:hover {
            background: #fff;
            color: #1a237e;
        }

        .site-navbar .btn-primary {
            background: #fff;
            border-color: #fff;
            color: #1a237e;
            padding: 10px 25px;
            font-weight: 500;
            font-size: 14px;
            white-space: nowrap;
        }

        .site-navbar .btn-primary:hover {
            background: #e3f2fd;
            border-color: #e3f2fd;
        }

        .site-navbar .dropdown-menu {
            background: #fff;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 10px 0;
            min-width: 200px;
            display: none;
            position: absolute;
            z-index: 1000;
        }

        .site-navbar .dropdown-menu.show {
            display: block;
        }

        .site-navbar .dropdown-item {
            color: #1a237e;
            padding: 10px 25px;
            font-size: 14px;
            display: block;
            width: 100%;
            text-align: left;
            background: transparent;
            border: none;
            transition: all 0.3s ease;
        }

        .site-navbar .dropdown-item:hover {
            background: #e3f2fd;
            color: #1a237e;
        }

        .site-navbar .dropdown {
            position: relative;
        }

        .site-navbar .dropdown-toggle {
            cursor: pointer;
        }

        .main-content-section {
            padding-top: 20px;
            min-height: 100vh;
        }

        .listing-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px auto;
            backdrop-filter: blur(5px);
            max-width: 1400px;
        }

        .listing-container h2 {
            color: #1a237e;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }

        .job-list-container {
            max-height: none;
            overflow-y: visible;
        }

        .job-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(26, 35, 126, 0.1);
            min-height: 300px;
        }

        .job-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border-color: rgba(26, 35, 126, 0.3);
        }

        .job-image-container {
            position: relative;
            height: 180px;
            background: rgba(248, 249, 250, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
            border-bottom: 1px solid rgba(26, 35, 126, 0.1);
        }

        .company-logo {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .job-box:hover .company-logo {
            transform: scale(1.1);
        }

        .job-type-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #1a237e;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: 500;
            z-index: 1;
        }

        .job-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .job-title {
            font-size: 1.2em;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            transition: color 0.3s ease;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .job-box:hover .job-title {
            color: #1a237e;
        }

        .job-details {
            color: #6c757d;
            margin-bottom: 15px;
        }

        .job-details p {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95em;
        }

        .job-details i {
            color: #1a237e;
            font-size: 1.1em;
        }

        .selected-job {
            border: 2px solid #1a237e;
        }

        #job-details {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 20px;
            min-height: 400px;
        }

        #job-details p {
            color: #6c757d;
            margin-bottom: 15px;
        }

        #job-details h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }

        #job-details .btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        #job-details .btn-primary {
            background: #6c63ff;
            border-color: #6c63ff;
        }

        #job-details .btn-primary:hover {
            background: #5a52e0;
            border-color: #5a52e0;
        }

        #job-details .btn-secondary {
            background: #6c757d;
            border-color: #6c757d;
        }

        #job-details .btn-secondary:hover {
            background: #5a6268;
            border-color: #5a6268;
        }

        #job-location-map {
            width: 100%;
            height: 300px;
            margin-top: 15px;
            border-radius: 10px;
            overflow: hidden;
        }

        .job-box-wrapper {
            transition: all 0.3s ease;
            height: 100%;
        }

        #job-details-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .back-button {
            margin-bottom: 20px;
        }

        @media (max-width: 1200px) {
            .job-box {
                min-height: 280px;
            }
            
            .job-image-container {
                height: 160px;
            }
        }

        @media (max-width: 992px) {
            .job-box {
                min-height: 260px;
            }
            
            .job-image-container {
                height: 150px;
            }
        }

        @media (max-width: 768px) {
            .job-box-wrapper {
                width: 100%;
            }
            
            .job-box {
                min-height: 240px;
            }
            
            .job-image-container {
                height: 140px;
            }
        }

        /* Updated split layout styles */
        .split-layout {
            display: none;
            gap: 20px;
        }

        .split-layout.active {
            display: flex;
        }

        .job-listings-column {
            width: 33.33%;
            padding-right: 20px;
        }

        .job-listings-column .job-box {
            min-height: 200px; /* Reduced height for split view */
        }

        .job-listings-column .job-image-container {
            height: 120px; /* Reduced height for split view */
        }

        .job-listings-column .job-content {
            padding: 15px; /* Reduced padding for split view */
        }

        .job-listings-column .job-title {
            font-size: 1.1em; /* Slightly smaller font for split view */
            margin-bottom: 10px;
        }

        .job-listings-column .job-details p {
            font-size: 0.9em; /* Smaller font for split view */
            margin-bottom: 5px;
        }

        .job-details-column {
            width: 66.67%;
            padding-left: 20px;
        }

        /* Updated three column layout styles */
        .three-column-layout {
            display: block;
        }

        .three-column-layout.hidden {
            display: none;
        }

        .job-box-wrapper {
            transition: all 0.3s ease;
            height: 100%;
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .job-box {
                min-height: 280px;
            }
            
            .job-image-container {
                height: 160px;
            }

            .job-listings-column .job-box {
                min-height: 180px;
            }

            .job-listings-column .job-image-container {
                height: 100px;
            }
        }

        @media (max-width: 992px) {
            .job-box {
                min-height: 260px;
            }
            
            .job-image-container {
                height: 150px;
            }

            .job-listings-column .job-box {
                min-height: 160px;
            }

            .job-listings-column .job-image-container {
                height: 90px;
            }
        }

        @media (max-width: 768px) {
            .job-box-wrapper {
                width: 100%;
            }
            
            .job-box {
                min-height: 240px;
            }
            
            .job-image-container {
                height: 140px;
            }

            .job-listings-column .job-box {
                min-height: 140px;
            }

            .job-listings-column .job-image-container {
                height: 80px;
            }

            /* Stack layout on mobile */
            .split-layout.active {
                flex-direction: column;
            }

            .job-listings-column,
            .job-details-column {
                width: 100%;
                padding: 0;
            }
        }
    </style>
    <!-- CV Selection Modal -->
    <div class="modal fade" id="cvModal" tabindex="-1" aria-labelledby="cvModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cvModalLabel">Select CVs to Submit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>CV Name</th>
                            </tr>
                        </thead>
                        <tbody id="cv-modal-body">
                            <!-- CV list will be dynamically populated here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="send-application-btn">Send Application</button>
                </div>
            </div>
        </div>
    </div>
</head>
<body id="top">
    <div id="overlayer"></div>
    <div class="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    
    <div class="site-wrap">
        
        <!-- Navbar Section -->
        <?php 
        $logo_path = "../fortest/images/peso_icons.jpg";
        require "../includes/nav_index.php"; 
        ?>

        <!-- Main Content Section -->
        <section class="main-content-section">
            <!-- Search -->
            <div class="container" style="margin-top: 100px;">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-12 col-lg-10">
                        <form method="get" class="search-jobs-form">
                            <div class="row mb-5">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                                    <input type="text" class="form-control form-control-lg" name="search_title" placeholder="Job title, Company..." value="<?= htmlspecialchars($search_title) ?>">
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                                    <select class="selectpicker form-control form-control-lg" name="search_category[]" data-style="btn-white btn-lg" data-width="100%" data-live-search="true" title="Select Category" multiple>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['category_id']; ?>" <?php echo in_array($category['category_id'], $search_category) ? 'selected' : ''; ?>><?php echo $category['category_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                                    <select class="selectpicker form-control form-control-lg" name="search_type" data-style="btn-white btn-lg" data-width="100%" data-live-search="true" title="Select Job Type">
                                        <option disabled>Select Job Type</option>
                                        <option value="" <?php echo $search_type == '' ? 'selected' : ''; ?>>All</option>
                                        <option value="Part-Time" <?php echo $search_type == 'Part-Time' ? 'selected' : ''; ?>>Part-Time</option>
                                        <option value="Full-Time" <?php echo $search_type == 'Full-Time' ? 'selected' : ''; ?>>Full-Time</option>
                                        <option value="Contract" <?php echo $search_type == 'Contract' ? 'selected' : ''; ?>>Contract</option>
                                        <option value="Temporary" <?php echo $search_type == 'Temporary' ? 'selected' : ''; ?>>Temporary</option>
                                        <option value="Internship" <?php echo $search_type == 'Internship' ? 'selected' : ''; ?>>Internship</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block btn-search"><span class="icon-search icon mr-2"></span>Search Job</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Listing cards -->
            <div class="listing-container">
                <!-- Three Column Layout -->
                <div class="three-column-layout" id="three-column-layout">
                    <div class="row">
                        <div class="col-12">
                            <h2>Job Listings</h2>
                            <div class="job-list-container">
                                <div class="row job-list" id="job-list">
                                    <?php if ($jobs->num_rows > 0): ?>
                                        <?php while ($job = $jobs->fetch_assoc()): ?>
                                            <div class="col-md-4 mb-4 job-box-wrapper">
                                                <div id="job-<?= $job['job_id'] ?>" class="job-box" onclick="showJobDetails(<?= $job['job_id'] ?>)">
                                                    <div class="job-image-container">
                                                        <img src="<?= htmlspecialchars($job['comp_logo_dir']) ?>" 
                                                             alt="<?= htmlspecialchars($job['companyName']) ?> Logo" 
                                                             class="company-logo">
                                                        <div class="job-type-badge"><?= htmlspecialchars($job['employment_type']) ?></div>
                                                    </div>
                                                    <div class="job-content">
                                                        <h5 class="job-title"><?= htmlspecialchars($job['title']) ?></h5>
                                                        <div class="job-details">
                                                            <p><i class="icon-building"></i> <?= htmlspecialchars($job['companyName']) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <div class="col-12 text-center text-muted">
                                            <i class="icon-info-circle" style="font-size: 48px;"></i>
                                            <p class="mt-3">No jobs available at the moment. Please check back later.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Split Layout (33-66) -->
                <div class="split-layout" id="split-layout">
                    <div class="job-listings-column">
                        <h2>Job Listings</h2>
                        <div class="job-list-container">
                            <div class="job-list" id="split-job-list">
                                <!-- Job listings will be moved here when a card is clicked -->
                            </div>
                        </div>
                    </div>
                    <div class="job-details-column">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>Job Details</h2>
                            <button class="btn btn-outline-primary" onclick="backToListings()">
                                <i class="icon-arrow-left"></i> Back to Listings
                            </button>
                        </div>
                        <div id="job-details" class="bg-white p-4 rounded shadow-sm">
                            <!-- Job details will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="../fortest/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fortest/js/isotope.pkgd.min.js"></script>
    <script src="../fortest/js/stickyfill.min.js"></script>
    <script src="../fortest/js/jquery.fancybox.min.js"></script>
    <script src="../fortest/js/jquery.easing.1.3.js"></script>
    <script src="../fortest/js/jquery.waypoints.min.js"></script>
    <script src="../fortest/js/jquery.animateNumber.min.js"></script>
    <script src="../fortest/js/owl.carousel.min.js"></script>
    <script src="../fortest/js/bootstrap-select.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="../includes/employee/js/emp_job_list.js"></script>
    <script src="../fortest/js/custom.js"></script>
    <script>
    function showJobDetails(jobId) {
        // Hide three column layout
        document.getElementById('three-column-layout').classList.add('hidden');
        
        // Show split layout
        document.getElementById('split-layout').classList.add('active');
        
        // Move all job boxes to the split layout
        const jobList = document.getElementById('job-list');
        const splitJobList = document.getElementById('split-job-list');
        splitJobList.innerHTML = jobList.innerHTML;
        
        // Add selected class to the clicked job box
        document.querySelectorAll('.job-box').forEach(box => {
            box.classList.remove('selected-job');
        });
        document.getElementById('job-' + jobId).classList.add('selected-job');
        
        // Load job details via AJAX
        fetch('get_job_details.php?id=' + jobId)
            .then(response => response.text())
            .then(html => {
                document.getElementById('job-details').innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading job details:', error);
                document.getElementById('job-details').innerHTML = '<div class="alert alert-danger">Error loading job details. Please try again.</div>';
            });
    }

    function backToListings() {
        // Show three column layout
        document.getElementById('three-column-layout').classList.remove('hidden');
        
        // Hide split layout
        document.getElementById('split-layout').classList.remove('active');
        
        // Remove selected class from all job boxes
        document.querySelectorAll('.job-box').forEach(box => {
            box.classList.remove('selected-job');
        });
    }
    </script>
</body>
</html>
