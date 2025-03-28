<?php
session_start();
require "../includes/db_connect.php";

// Check if the user is logged in as a company
if (!isset($_SESSION['company_id'])) {
    exit('Unauthorized');
}

$company_id = $_SESSION['company_id'];
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'posted_date';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'desc';
$search_query = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

$query = "SELECT jl.job_id, jl.title, jl.description, jl.posted_date, jl.expiry_date, jl.status,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'pending') AS pending_count,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'awaiting') AS awaiting_count,
                 (SELECT COUNT(*) FROM tbl_job_application ja WHERE ja.job_id = jl.job_id AND ja.status = 'accepted') AS accepted_count
          FROM tbl_job_listing jl 
          WHERE jl.employer_id = ? AND jl.title LIKE ?";

// Add sorting based on the sort_by parameter
switch($sort_by) {
    case 'title':
        $query .= " ORDER BY jl.title " . ($sort_order === 'asc' ? 'ASC' : 'DESC');
        break;
    case 'posted_date':
        $query .= " ORDER BY jl.posted_date " . ($sort_order === 'asc' ? 'ASC' : 'DESC');
        break;
    case 'expiry_date':
        $query .= " ORDER BY jl.expiry_date " . ($sort_order === 'asc' ? 'ASC' : 'DESC');
        break;
    case 'pending_count':
        $query .= " ORDER BY pending_count " . ($sort_order === 'asc' ? 'ASC' : 'DESC');
        break;
    default:
        $query .= " ORDER BY jl.posted_date DESC";
}

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $company_id, $search_query);
$stmt->execute();
$result = $stmt->get_result();
$jobs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($jobs)): ?>
    <div class="job-item">
        <div colspan="4" class="text-center">No jobs found.</div>
    </div>
<?php else: ?>
    <?php foreach ($jobs as $job): ?>
        <div class="job-item">
            <div>
                <strong><?= htmlspecialchars($job['title']) ?></strong><br>
                <small><?= htmlspecialchars($job['description']) ?></small><br>
                <small>Created: <?= htmlspecialchars(date('Y-m-d', strtotime($job['posted_date']))) ?> - Ends: <?= htmlspecialchars(date('Y-m-d', strtotime($job['expiry_date']))) ?></small>
            </div>
            <div>
                <span><?= $job['pending_count'] ?> Pending</span> | 
                <span><?= $job['awaiting_count'] ?> Awaiting</span> | 
                <span><?= $job['accepted_count'] ?> Accepted</span>
            </div>
            <div>
                <select class="form-select job-status-dropdown" data-job-id="<?= $job['job_id'] ?>" onchange="updateJobStatus(this)">
                    <option value="active" <?= $job['status'] == 'active' ? 'selected' : '' ?>>● Active</option>
                    <option value="paused" <?= $job['status'] == 'paused' ? 'selected' : '' ?>>● Paused</option>
                    <option value="inactive" <?= $job['status'] == 'inactive' ? 'selected' : '' ?>>● Inactive</option>
                </select>
            </div>
            <div>
                <button class="action-btn" data-bs-toggle="modal" data-bs-target="#editJobModal" data-job-id="<?= $job['job_id'] ?>">Edit</button>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?> 