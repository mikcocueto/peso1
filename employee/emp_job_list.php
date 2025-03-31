<?php
require "../includes/db_connect.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: emp_login.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fortest/style2/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../fortest/style2/custom-bs.css">
    <link rel="stylesheet" href="../fortest/style2/jquery.fancybox.min.css">
    <link rel="stylesheet" href="../fortest/style2/bootstrap-select.min.css">
    <link rel="stylesheet" href="../fortest/fonts/icomoon/style.css">
    <link rel="stylesheet" href="../fortest/fonts/line-icons/style.css">
    <link rel="stylesheet" href="../fortest/style2/owl.carousel.min.css">
    <link rel="stylesheet" href="../fortest/style2/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
    <style>
        .job-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
            cursor: pointer;
            position: relative;
        }
        .job-title {
            font-size: 1.2em;
            font-weight: bold;
        }
        .job-details {
            margin-top: 10px;
        }
        .selected-job {
            background-color: #e9ecef;
        }
        .job-list-container {
            max-height: 80vh;
            overflow-y: auto;
        }
        @media (max-width: 767.98px) {
            .job-list-container {
                max-height: 80vh;
            }
        }
        .search-jobs-form .form-control, .search-jobs-form .selectpicker, .search-jobs-form .btn-search {
            margin-bottom: 0;
        }
        .company-logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .category-select {
            width: 100%;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
    </style>
    <script>
        function showJobDetails(jobId) {
            const jobDetails = document.getElementById('job-details');
            const jobBoxes = document.querySelectorAll('.job-box');
            jobBoxes.forEach(box => box.classList.remove('selected-job'));
            document.getElementById('job-' + jobId).classList.add('selected-job');

            fetch('../includes/employee/emp_get_job_details.php?job_id=' + jobId)
                .then(response => response.text())
                .then(data => {
                    jobDetails.innerHTML = data;
                    fetch('../includes/employee/emp_check_application.php?job_id=' + jobId)
                        .then(response => response.json())
                        .then(data => {
                            const applyButton = document.createElement('button');
                            if (data.applied) {
                                applyButton.textContent = 'Already Applied';
                                applyButton.classList.add('btn', 'btn-secondary');
                                applyButton.disabled = true;
                            } else {
                                applyButton.textContent = 'Apply';
                                applyButton.classList.add('btn', 'btn-primary');
                                applyButton.onclick = function() {
                                    applyForJob(jobId);
                                };
                            }
                            jobDetails.appendChild(applyButton);
                        });
                });
        }

        function applyForJob(jobId) {
            fetch('../includes/employee/emp_apply_job.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ job_id: jobId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Application submitted successfully');
                    showJobDetails(jobId); // Refresh job details to update the button
                } else {
                    alert('Failed to submit application');
                }
            });
        }
    </script>
</head>
<body class="bg-light">

    <!-- Search -->
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-12 col-lg-10">
                <form method="get" class="search-jobs-form">
                    <div class="row mb-5">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                            <input type="text" class="form-control form-control-lg" name="search_title" placeholder="Job title, Company..." value="<?= htmlspecialchars($search_title) ?>">
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                            <select class="selectpicker form-control form-control-lg" name="search_category[]" data-style="btn-white btn-lg" data-width="100%" data-live-search="true" title="Select Category" multiple>
                                <option disabled>Select Category</option>
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
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="text-center mb-4">Job Listings</h2>
                <div class="job-list-container">
                    <div class="job-list">
                        <?php while ($job = $jobs->fetch_assoc()): ?>
                            <div id="job-<?= $job['job_id'] ?>" class="job-box" onclick="showJobDetails(<?= $job['job_id'] ?>)">
                                <img src="<?= htmlspecialchars($job['comp_logo_dir']) ?>" alt="Company Logo" class="company-logo">
                                <div>
                                    <div class="job-title"><?= htmlspecialchars($job['title']) ?></div>
                                    <div class="job-details">
                                        <p><strong>Company:</strong> <?= htmlspecialchars($job['companyName']) ?></p>
                                        <p><strong>Employment Type:</strong> <?= htmlspecialchars($job['employment_type']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="col-md-6">
                <h2 class="text-center mb-4">Job Details</h2>
                <div id="job-details">
                    <p>Select a job on the list</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Update the index button with home icon -->
    <div style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">
        <a href="../index.php" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;">
            <i class="icon-home" style="margin-right: 5px;"></i>Home
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<script src="../fortest/js/jquery.min.js"></script>
<script src="../fortest/js/bootstrap.bundle.min.js"></script>
<script src="../fortest/js/isotope.pkgd.min.js"></script>
<script src="../fortest/js/stickyfill.min.js"></script>
<script src="../fortest/js/jquery.fancybox.min.js"></script>
<script src="../fortest/js/jquery.easing.1.3.js"></script>

<script src="../fortest/js/jquery.waypoints.min.js"></script>
<script src="../fortest/js/jquery.animateNumber.min.js"></script>
<script src="../fortest/js/owl.carousel.min.js"></script>

<script src="../fortest/js/bootstrap-select.min.js"></script>

<script src="../fortest/js/custom.js"></script>                       
</body>
</html>
