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

// Fetch user's category preferences
$preferred_categories = [];
$preferences_query = $conn->prepare("SELECT category_id FROM tbl_emp_category_preferences WHERE emp_id = ?");
$preferences_query->bind_param("i", $user_id);
$preferences_query->execute();
$preferences_result = $preferences_query->get_result();
while ($row = $preferences_result->fetch_assoc()) {
    $preferred_categories[] = $row['category_id'];
}
$preferences_query->close();

// Modify the job query to prioritize preferred categories
$query = "SELECT jl.job_id, jl.title, jl.employment_type, c.companyName, c.comp_logo_dir,
          CASE 
              WHEN jl.category_id IN (" . implode(',', array_map('intval', $preferred_categories)) . ") THEN 1
              ELSE 2
          END AS relevance
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

// Add sorting by relevance and posted date
$query .= " ORDER BY relevance, jl.posted_date DESC";

$jobs = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fortest/style2/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../fortest/style2/custom-bs.css">
    <link rel="stylesheet" href="../fortest/style2/jquery.fancybox.min.css">
    <link rel="stylesheet" href="../fortest/style2/bootstrap-select.min.css">
    <link rel="stylesheet" href="../fortest/fonts/icomoon/style.css">
    <link rel="stylesheet" href="../fortest/fonts/line-icons/style.css">
    <link rel="stylesheet" href="../fortest/style2/owl.carousel.min.css">
    <link rel="stylesheet" href="../fortest/style2/animate.min.css">
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

        /* Add styles for Bootstrap Select dropdown */
        .bootstrap-select {
            z-index: 2 !important;
        }
        .bootstrap-select .dropdown-menu {
            z-index: 2 !important;
        }
        .bootstrap-select.show-tick .dropdown-menu {
            z-index: 2 !important;
        }
        .bootstrap-select .dropdown-menu.inner {
            z-index: 2 !important;
        }
        .bootstrap-select .dropdown-menu.open {
            z-index: 2 !important;
        }
        .bootstrap-select .dropdown-menu.show {
            z-index: 2 !important;
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
            max-height: 80vh;
            overflow-y: auto;
            padding-right: 15px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            backdrop-filter: blur(5px);
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
        }

        .job-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border-color: rgba(26, 35, 126, 0.3);
        }

        .job-image-container {
            position: relative;
            height: 150px;
            background: rgba(248, 249, 250, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }

        .company-logo {
            max-width: 100%;
            max-height: 100%;
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
        }

        .job-content {
            padding: 20px;
            flex-grow: 1;
        }

        .job-title {
            font-size: 1.2em;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        .job-box:hover .job-title {
            color: #1a237e;
        }

        .job-details {
            color: #6c757d;
            margin-bottom: 15px;
        }

        .job-details p {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .job-details i {
            color: #1a237e;
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
                                    <select class="selectpicker form-control form-control-lg" name="search_category[]" data-style="btn-white btn-lg" data-width="100%" data-live-search="true" data-live-search-placeholder="Search categories..." data-actions-box="true" title="Select Category" multiple>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['category_id']; ?>" <?php echo in_array($category['category_id'], $search_category) ? 'selected' : ''; ?>><?php echo $category['category_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                                    <select class="selectpicker form-control form-control-lg" name="search_type" data-style="btn-white btn-lg" data-width="100%" data-live-search="true" data-live-search-placeholder="Search job types..." title="Select Job Type">
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
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h2>Job Listings</h2>
                        <div class="job-list-container">
                            <div class="job-list">
                                <?php if ($jobs->num_rows > 0): ?>
                                    <?php while ($job = $jobs->fetch_assoc()): ?>
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
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="text-center text-muted">
                                        <i class="icon-info-circle" style="font-size: 48px;"></i>
                                        <p class="mt-3">No jobs available at the moment. Please check back later.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="col-md-6">
                        <h2>Job Details</h2>
                        <div id="job-details">
                            <div class="text-center text-muted">
                                <i class="icon-search" style="font-size: 48px;"></i>
                                <p class="mt-3">Select a job from the list to view details</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </div>
    

    <script src="../fortest/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
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
        // Add this new script to handle job selection from URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap Select with specific options
            $('.selectpicker').selectpicker({
                liveSearch: true,
                liveSearchPlaceholder: 'Search...',
                noneSelectedText: 'Nothing selected',
                noneResultsText: 'No results found {0}',
                countSelectedText: '{0} items selected',
                selectAllText: 'Select All',
                deselectAllText: 'Deselect All',
                size: 5
            });

            const urlParams = new URLSearchParams(window.location.search);
            const jobId = urlParams.get('job_id');
            
            if (jobId) {
                // Find and click the job box with matching ID
                const jobBox = document.getElementById(`job-${jobId}`);
                if (jobBox) {
                    // Add a small delay to ensure the page is fully loaded
                    setTimeout(() => {
                        jobBox.click();
                        // Scroll the job box into view
                        jobBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 100);
                }
            }
        });
    </script>
</body>
</html>
