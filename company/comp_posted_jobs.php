<?php
session_start();
require "../includes/db_connect.php";

// Check if the user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
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
    <title>Posted Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../fortest/style2/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Your Posted Jobs</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Job Details</th>
                <th>Candidates</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job): ?>
                <tr>
                    <td>
                            <div style="font-size: 1.5em; font-weight: bold;">
                            <?= htmlspecialchars($job['title']) ?>
                        </div>
                        <div style="font-size: 0.9em;">
                            <strong>Posted Date:</strong> <?= htmlspecialchars(date('Y-m-d', strtotime($job['posted_date']))) ?> |
                            <strong>Expiry Date:</strong> <?= htmlspecialchars(date('Y-m-d', strtotime($job['expiry_date']))) ?>
                        </div>
                        <div>
                            <?= htmlspecialchars($job['description']) ?>
                        </div>
                    </td>
                    <td>
                        <strong>Pending:</strong> <?= $job['pending_count'] ?><br>
                        <strong>Awaiting:</strong> <?= $job['awaiting_count'] ?><br>
                        <strong>Accepted:</strong> <?= $job['accepted_count'] ?>
                    </td>
                    <td>
                        <div class="d-flex">
                            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editJobModal" data-job-id="<?= $job['job_id'] ?>">Edit</button>
                            <a href="comp_job_post_candidates.php?job_id=<?= $job['job_id'] ?>" class="btn btn-secondary me-2">View Candidates</a>
                            <select class="form-select w-50" onchange="updateJobStatus(<?= $job['job_id'] ?>, this.value)">
                                <option value="active" <?= $job['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="paused" <?= $job['status'] == 'paused' ? 'selected' : '' ?>>Paused</option>
                                <option value="inactive" <?= $job['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

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

<a href="comp_dashboard.php" class="btn btn-primary w-100 mt-2">Go back</a>

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

    function updateJobStatus(jobId, status) {
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
</script>
</body>
</html>
