<?php
require "../db_connect.php";
session_start();

if (!isset($_SESSION['company_id'])) {
    echo "Unauthorized access.";
    exit();
}

$company_id = $_SESSION['company_id'];
$search_query = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

$query = "SELECT jl.job_id, jl.title, jl.description, jl.posted_date, jl.expiry_date, jl.status,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'pending') AS pending_count,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'awaiting') AS awaiting_count,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'accepted') AS accepted_count
          FROM tbl_job_listing jl 
          WHERE jl.employer_id = ? AND jl.title LIKE ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $company_id, $search_query);
$stmt->execute();
$result = $stmt->get_result();
$jobs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($jobs)) {
    echo '<div class="job-item"><div colspan="4" class="text-center">No jobs found.</div></div>';
} else {
    foreach ($jobs as $job) {
        echo '<div class="job-item">
                <div>
                    <strong>' . htmlspecialchars($job['title']) . '</strong><br>
                    <small>' . htmlspecialchars($job['description']) . '</small><br>
                    <small>Created: ' . htmlspecialchars(date('Y-m-d', strtotime($job['posted_date']))) . ' - Ends: ' . htmlspecialchars(date('Y-m-d', strtotime($job['expiry_date']))) . '</small>
                </div>
                <div>
                    <span>' . $job['pending_count'] . ' Pending</span> | 
                    <span>' . $job['awaiting_count'] . ' Awaiting</span> | 
                    <span>' . $job['accepted_count'] . ' Accepted</span>
                </div>
                <div>
                    <select class="form-select job-status-dropdown" data-job-id="' . $job['job_id'] . '" onchange="updateJobStatus(this)">
                        <option value="active" ' . ($job['status'] == 'active' ? 'selected' : '') . '>● Active</option>
                        <option value="paused" ' . ($job['status'] == 'paused' ? 'selected' : '') . '>● Paused</option>
                        <option value="inactive" ' . ($job['status'] == 'inactive' ? 'selected' : '') . '>● Inactive</option>
                    </select>
                </div>
                <div>
                    <button class="action-btn" data-bs-toggle="modal" data-bs-target="#editJobModal" data-job-id="' . $job['job_id'] . '">Edit</button>
                </div>
              </div>';
    }
}
?>
