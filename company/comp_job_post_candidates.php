<?php
require "../includes/db_connect.php";

// Fetch job listings
$jobs = $conn->query("SELECT job_id, title FROM tbl_job_listing");

// Fetch candidates for the selected job
$selected_job_id = isset($_GET['job_id']) ? $_GET['job_id'] : '';
$candidates = $conn->query("SELECT DISTINCT e.firstName, e.lastName, e.emailAddress, ec.cv_dir 
                            FROM tbl_job_application ja 
                            JOIN tbl_employee e ON ja.emp_id = e.user_id 
                            LEFT JOIN tbl_emp_cv ec ON e.user_id = ec.emp_id 
                            WHERE ja.job_id = '$selected_job_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Candidates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="text-center">Job Candidates</h3>
                    <form method="GET" action="comp_job_post_candidates.php">
                        <div class="mb-3">
                            <label for="job_id" class="form-label">Select Job</label>
                            <select class="form-select" name="job_id" onchange="this.form.submit()">
                                <?php while ($job = $jobs->fetch_assoc()): ?>
                                    <option value="<?= $job['job_id'] ?>" <?= $selected_job_id == $job['job_id'] ? 'selected' : '' ?>><?= htmlspecialchars($job['title']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </form>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Candidate Name</th>
                                <th>Email</th>
                                <th>Resume</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($candidate = $candidates->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($candidate['firstName'] . ' ' . $candidate['lastName']) ?></td>
                                    <td><?= htmlspecialchars($candidate['emailAddress']) ?></td>
                                    <td><a href="<?= htmlspecialchars($candidate['cv_dir']) ?>" target="_blank">View Resume</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
