<?php
session_start();
require "../includes/db_connect.php";

// Check if the user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}

$company_id = $_SESSION['company_id'];

// Fetch job listings posted by the logged-in company
$jobs = $conn->query("SELECT job_id, title FROM tbl_job_listing WHERE employer_id = '$company_id'");

// Fetch candidates for the selected job
$selected_job_id = isset($_GET['job_id']) ? $_GET['job_id'] : '';
$candidates = $conn->query("SELECT DISTINCT e.user_id, e.firstName, e.lastName, e.emailAddress, ec.cv_dir 
                            FROM tbl_job_application ja 
                            JOIN tbl_emp_info e ON ja.emp_id = e.user_id 
                            LEFT JOIN tbl_emp_cv ec ON e.user_id = ec.emp_id 
                            WHERE ja.job_id = '$selected_job_id' AND ja.job_id IN (SELECT job_id FROM tbl_job_listing WHERE employer_id = '$company_id')");
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
                                
                                <th>Resume</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($candidate = $candidates->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($candidate['firstName'] . ' ' . $candidate['lastName']) ?></td>
                                    
                                    <td><a href="<?= htmlspecialchars($candidate['cv_dir']) ?>" target="_blank">View Resume</a></td>
                                    <td>
                                        <button class="btn btn-info" onclick='openInfoModal(<?php echo json_encode($candidate); ?>)'>Information</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <a href="comp_posted_jobs.php" class="btn btn-primary w-100 mt-2">Go back</a>
</div>

<!-- Modal for displaying applicant information -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Applicant Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="applicantName"></span></p>
                <p><strong>Email:</strong> <span id="applicantEmail"></span></p>
                <!-- Add more fields as needed -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openInfoModal(candidate) {
        document.getElementById('applicantName').innerText = candidate.firstName + ' ' + candidate.lastName;
        document.getElementById('applicantEmail').innerText = candidate.emailAddress;
        // Populate more fields as needed

        var infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
        infoModal.show();
    }
</script>
</body>
</html>
