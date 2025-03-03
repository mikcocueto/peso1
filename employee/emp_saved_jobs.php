<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../employee/emp_login.php");
    exit();
}

require "../includes/db_connect.php";


$user_id = $_SESSION['user_id'];

// Fetch saved jobs for the logged-in user
$saved_jobs_query = $conn->prepare("SELECT jl.job_id, jl.title, jl.description, jl.requirements, jl.employment_type, jl.location, jl.salary_min, jl.salary_max, jl.currency, jl.expiry_date, c.companyName, c.comp_logo_dir, jc.category_name 
                                    FROM tbl_emp_saved_jobs esj
                                    JOIN tbl_job_listing jl ON esj.job_id = jl.job_id
                                    JOIN tbl_company c ON jl.employer_id = c.company_id
                                    JOIN tbl_job_category jc ON jl.category_id = jc.category_id
                                    WHERE esj.user_id = ?");
$saved_jobs_query->bind_param("i", $user_id);
$saved_jobs_query->execute();
$saved_jobs = $saved_jobs_query->get_result();
$saved_jobs_query->close();

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
    <title>Saved Jobs</title>
    <link rel="stylesheet" href="../fortest/style2/custom-bs.css">
    <link rel="stylesheet" href="../fortest/style2/jquery.fancybox.min.css">
    <link rel="stylesheet" href="../fortest/style2/bootstrap-select.min.css">
    <link rel="stylesheet" href="../fortest/fonts/icomoon/style.css">
    <link rel="stylesheet" href="../fortest/fonts/line-icons/style.css">
    <link rel="stylesheet" href="../fortest/style2/owl.carousel.min.css">
    <link rel="stylesheet" href="../fortest/style2/animate.min.css">
    <link rel="stylesheet" href="../fortest/style2/style.css">
    <style>
        .job-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .job-title {
            font-size: 1.5em;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        .job-title img {
            max-width: 50px;
            max-height: 50px;
            margin-right: 10px;
        }
        .job-details {
            margin-top: 10px;
        }
        .unsave-job-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="site-wrap">
    <header class="site-navbar mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="site-logo col-6">
                    <a href="../index.php">
                        <img src="../fortest/images/peso_icons.png" alt="PESO Logo" style="height: 150px;">
                        <div style="display: inline-block; vertical-align: middle; margin-left: 10px;">
                            <div>PESO</div>
                            <div>Job Hiring</div>
                        </div>
                    </a>
                </div>
                <nav class="mx-auto site-navigation">
                    <ul class="site-menu js-clone-nav d-none d-xl-block ml-0 pl-0">
                        <li><a href="../index.php" class="nav-link active">Home</a></li>
                        <li><a href="../job-listings.html">Job Listings</a></li>
                        <li><a href="../about.html">About</a></li>
                        <li><a href="../contact.html">Contact</a></li>
                    </ul>
                </nav>
                <div class="right-cta-menu text-right d-flex align-items-center col-6">
                    <div class="ml-auto">
                        <a href="emp_dashboard.php" class="btn btn-outline-white border-width-2 d-none d-lg-inline-block"><span class="mr-2 icon-user"></span>Profile</a>
                        <a href="../includes/employee/emp_logout.php" class="btn btn-primary border-width-2 d-none d-lg-inline-block"><span class="mr-2 icon-lock_outline"></span>Logout</a>
                    </div>
                    <a href="#" class="site-menu-toggle js-menu-toggle d-inline-block d-xl-none mt-lg-2 ml-3"><span class="icon-menu h3 m-0 p-0 mt-2"></span></a>
                </div>
            </div>
        </div>
    </header>

    <section class="site-section">
        <div class="container">
            <h2 class="text-center mb-4">Saved Jobs</h2>
            <div class="row" style="max-height: 400px; overflow-y: auto;">
                <?php while ($job = $saved_jobs->fetch_assoc()): ?>
                    <div class="col-md-6">
                        <div class="job-box">
                            <div class="job-title">
                                <img src="<?= $job['comp_logo_dir'] ? htmlspecialchars($job['comp_logo_dir']) : '../path/to/placeholder.png'; ?>" alt="Company Logo">
                                <?= htmlspecialchars($job['title']) ?>
                            </div>
                            <div class="job-details">
                                <p><strong>Company:</strong> <?= htmlspecialchars($job['companyName']) ?></p>
                                <p><strong>Description:</strong> <?= htmlspecialchars($job['description']) ?></p>
                                <p><strong>Requirements:</strong> <?= htmlspecialchars($job['requirements']) ?></p>
                                <p><strong>Employment Type:</strong> <?= htmlspecialchars($job['employment_type']) ?></p>
                                <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                                <p><strong>Salary:</strong> <?= htmlspecialchars($job['salary_min']) ?> - <?= htmlspecialchars($job['salary_max']) ?> <?= htmlspecialchars($job['currency']) ?></p>
                                <p><strong>Category:</strong> <?= htmlspecialchars($job['category_name']) ?></p>
                                <p><strong>Expiry Date:</strong> <?= htmlspecialchars($job['expiry_date']) ?></p>
                            </div>
                            <form method="post" action="../includes/save_job_process.php" class="unsave-job-btn">
                                <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                                <button type="submit" name="action" value="unsave" class="btn btn-outline-danger">Unsave</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
</div>

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
