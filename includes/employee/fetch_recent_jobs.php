<?php
session_start();
require "../db_connect.php";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch job category preferences for the logged-in user
$preferred_categories = [];
if ($user_id) {
    $query = "SELECT category_id FROM tbl_emp_category_preferences WHERE emp_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $preferred_categories[] = $row['category_id'];
    }
    $stmt->close();
}

// Build the query to fetch recent job listings
$query = "SELECT jl.job_id, jl.title, jl.employment_type, c.companyName, c.comp_logo_dir, jl.category_id 
          FROM tbl_job_listing jl 
          JOIN tbl_comp_info c ON jl.employer_id = c.company_id 
          WHERE jl.status = 'active'";

// If the user has preferred categories, prioritize those listings
if (!empty($preferred_categories)) {
    $preferred_category_ids = implode(',', $preferred_categories);
    $query .= " ORDER BY FIELD(jl.category_id, $preferred_category_ids) DESC, jl.posted_date DESC";
} else {
    $query .= " ORDER BY jl.posted_date DESC";
}

$stmt = $conn->prepare($query);
$stmt->execute();
$jobs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

echo json_encode($jobs);
?>
