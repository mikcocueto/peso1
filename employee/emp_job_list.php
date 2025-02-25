<?php
require "../includes/db_connect.php";

// Fetch job listings with company names
$jobs = $conn->query("SELECT jl.job_id, jl.title, jl.employment_type, c.companyName FROM tbl_job_listing jl JOIN tbl_company c ON jl.employer_id = c.company_id WHERE jl.status = 'active'");
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
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
            cursor: pointer;
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
    </style>
    <script>
        function showJobDetails(jobId) {
            const jobDetails = document.getElementById('job-details');
            const jobBoxes = document.querySelectorAll('.job-box');
            jobBoxes.forEach(box => box.classList.remove('selected-job'));
            document.getElementById('job-' + jobId).classList.add('selected-job');

            fetch('../includes/emp_get_job_details.php?job_id=' + jobId)
                .then(response => response.text())
                .then(data => {
                    jobDetails.innerHTML = data;
                });
        }
    </script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mb-4 mb-md-0">
            <h2 class="text-center mb-4">Job Listings</h2>
            <div class="job-list-container">
                <div class="job-list">
                    <?php while ($job = $jobs->fetch_assoc()): ?>
                        <div id="job-<?= $job['job_id'] ?>" class="job-box" onclick="showJobDetails(<?= $job['job_id'] ?>)">
                            <div class="job-title"><?= htmlspecialchars($job['title']) ?></div>
                            <div class="job-details">
                                <p><strong>Company:</strong> <?= htmlspecialchars($job['companyName']) ?></p>
                                <p><strong>Employment Type:</strong> <?= htmlspecialchars($job['employment_type']) ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="text-center mb-4">Job Details</h2>
            <div id="job-details">
                <p>Select a job on the list</p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
