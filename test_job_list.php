<?php
require "includes/db_connect.php";

// Fetch job listings with company names
$jobs = $conn->query("SELECT jl.title, jl.description, jl.requirements, jl.employment_type, jl.location, jl.salary_min, jl.salary_max, jl.currency, jl.posted_date, jl.expiry_date, c.companyName FROM tbl_job_listing jl JOIN tbl_company c ON jl.employer_id = c.company_id WHERE jl.status = 'active'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }
        .job-details {
            margin-top: 10px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Current Job Listings</h2>
    <div class="row">
        <?php while ($job = $jobs->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="job-box">
                    <div class="job-title"><?= htmlspecialchars($job['title']) ?></div>
                    <div class="job-details">
                        <p><strong>Company:</strong> <?= htmlspecialchars($job['companyName']) ?></p>
                        <p><strong>Description:</strong> <?= htmlspecialchars($job['description']) ?></p>
                        <p><strong>Requirements:</strong> <?= htmlspecialchars($job['requirements']) ?></p>
                        <p><strong>Employment Type:</strong> <?= htmlspecialchars($job['employment_type']) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                        <p><strong>Salary:</strong> <?= htmlspecialchars($job['salary_min']) ?> - <?= htmlspecialchars($job['salary_max']) ?> <?= htmlspecialchars($job['currency']) ?></p>
                        <p><strong>Posted Date:</strong> <?= htmlspecialchars($job['posted_date']) ?></p>
                        <p><strong>Expiry Date:</strong> <?= htmlspecialchars($job['expiry_date']) ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
