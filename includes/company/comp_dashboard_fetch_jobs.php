<?php
session_start();
require "../db_connect.php";

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

header('Content-Type: application/json');

// Fetch sorted jobs
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $company_id, $search_query);
$stmt->execute();
$result = $stmt->get_result();
$jobs = [];

while ($row = $result->fetch_assoc()) {
    $jobs[] = [
        'job_id' => $row['job_id'],
        'title' => htmlspecialchars($row['title']),
        'description' => htmlspecialchars($row['description']),
        'posted_date' => htmlspecialchars(date('Y-m-d', strtotime($row['posted_date']))),
        'expiry_date' => htmlspecialchars(date('Y-m-d', strtotime($row['expiry_date']))),
        'status' => $row['status'],
        'pending_count' => $row['pending_count'],
        'awaiting_count' => $row['awaiting_count'],
        'accepted_count' => $row['accepted_count']
    ];
}

$stmt->close();

// Return jobs as JSON
echo json_encode(['jobs' => $jobs]);