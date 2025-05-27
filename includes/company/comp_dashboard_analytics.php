<?php
// Fetch monthly application data for the past 12 months
$applications_query = "SELECT 
    DATE_FORMAT(ja.application_time, '%Y-%m') as month,
    COUNT(*) as application_count
FROM tbl_job_application ja
JOIN tbl_job_listing jl ON ja.job_id = jl.job_id
WHERE jl.employer_id = ?
AND ja.application_time >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
GROUP BY DATE_FORMAT(ja.application_time, '%Y-%m')
ORDER BY month ASC";

$stmt = $conn->prepare($applications_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$applications_result = $stmt->get_result();
$monthly_applications = [];
while ($row = $applications_result->fetch_assoc()) {
    $monthly_applications[$row['month']] = $row['application_count'];
}
$stmt->close();

// Generate labels and data for the last 12 months
$labels = [];
$data = [];
for ($i = 11; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $labels[] = date('M Y', strtotime($month));
    $data[] = $monthly_applications[$month] ?? 0;
}

//Fetch company info
$company_query = "SELECT companyName, country, companyNumber, comp_logo_dir, firstName, lastName FROM tbl_comp_info WHERE company_id = ?";
$stmt = $conn->prepare($company_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company_result = $stmt->get_result();
$company_info = $company_result->fetch_assoc();
$stmt->close();

// Fetch job categories from the database alangan
$categories_result = $conn->query("SELECT category_id, category_name FROM tbl_job_category");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch company verification status from tbl_comp_info
$verification_query = "SELECT company_verified FROM tbl_comp_info WHERE company_id = ?";
$stmt = $conn->prepare($verification_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$verification_result = $stmt->get_result();
$verification_status = $verification_result->fetch_assoc();
$is_verified = $verification_status && $verification_status['company_verified'] == 1;
$stmt->close();

// Handle job search
$search_query = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'posted_date';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'desc';

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

// Fetch jobs posted by the logged-in company for the dropdown
$jobs_dropdown_query = "SELECT job_id, title FROM tbl_job_listing WHERE employer_id = ?";
$stmt = $conn->prepare($jobs_dropdown_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$jobs_dropdown_result = $stmt->get_result();
$jobs_dropdown = $jobs_dropdown_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch candidate counts for all statuses
$candidate_counts_query = "SELECT 
    SUM(CASE WHEN ja.status = 'applied' THEN 1 ELSE 0 END) as applied_count,
    SUM(CASE WHEN ja.status = 'awaiting' THEN 1 ELSE 0 END) as awaiting_count,
    SUM(CASE WHEN ja.status = 'reviewed' THEN 1 ELSE 0 END) as reviewed_count,
    SUM(CASE WHEN ja.status = 'contacted' THEN 1 ELSE 0 END) as contacted_count,
    SUM(CASE WHEN ja.status = 'hired' THEN 1 ELSE 0 END) as hired_count,
    SUM(CASE WHEN ja.status = 'rejected' THEN 1 ELSE 0 END) as rejected_count
FROM tbl_job_application ja
JOIN tbl_job_listing jl ON ja.job_id = jl.job_id
WHERE jl.employer_id = ?";

$stmt = $conn->prepare($candidate_counts_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$counts_result = $stmt->get_result();
$candidate_counts = $counts_result->fetch_assoc();
$stmt->close();

// Fetch current month's data
$current_month_query = "SELECT 
    COUNT(DISTINCT ja.id) as total_applications,
    COUNT(DISTINCT jl.job_id) as total_jobs,
    COUNT(DISTINCT CASE WHEN ja.status = 'hired' THEN ja.id END) as total_hired
FROM tbl_job_listing jl
LEFT JOIN tbl_job_application ja ON jl.job_id = ja.job_id
WHERE jl.employer_id = ?
AND (
    (MONTH(ja.application_time) = MONTH(CURRENT_DATE()) AND YEAR(ja.application_time) = YEAR(CURRENT_DATE()))
    OR (ja.application_time IS NULL AND MONTH(jl.posted_date) = MONTH(CURRENT_DATE()) AND YEAR(jl.posted_date) = YEAR(CURRENT_DATE()))
)";

$stmt = $conn->prepare($current_month_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$current_month_result = $stmt->get_result();
$current_month_data = $current_month_result->fetch_assoc();
$stmt->close();

// Fetch previous month's data
$previous_month_query = "SELECT 
    COUNT(DISTINCT ja.id) as total_applications,
    COUNT(DISTINCT jl.job_id) as total_jobs,
    COUNT(DISTINCT CASE WHEN ja.status = 'hired' THEN ja.id END) as total_hired
FROM tbl_job_listing jl
LEFT JOIN tbl_job_application ja ON jl.job_id = ja.job_id
WHERE jl.employer_id = ?
AND (
    (MONTH(ja.application_time) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(ja.application_time) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)))
    OR (ja.application_time IS NULL AND MONTH(jl.posted_date) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(jl.posted_date) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)))
)";

$stmt = $conn->prepare($previous_month_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$previous_month_result = $stmt->get_result();
$previous_month_data = $previous_month_result->fetch_assoc();
$stmt->close();

// Calculate percentage changes
function calculatePercentageChange($current, $previous) {
    if ($previous == 0) return $current > 0 ? 100 : 0;
    return (($current - $previous) / $previous) * 100;
}

$applications_change = calculatePercentageChange(
    $current_month_data['total_applications'],
    $previous_month_data['total_applications']
);

$jobs_change = calculatePercentageChange(
    $current_month_data['total_jobs'],
    $previous_month_data['total_jobs']
);

$hired_change = calculatePercentageChange(
    $current_month_data['total_hired'],
    $previous_month_data['total_hired']
);

// Fetch age distribution data
$age_query = "SELECT 
    CASE 
        WHEN TIMESTAMPDIFF(YEAR, ei.birth_date, CURDATE()) BETWEEN 15 AND 17 THEN '15-17'
        WHEN TIMESTAMPDIFF(YEAR, ei.birth_date, CURDATE()) BETWEEN 18 AND 24 THEN '18-24'
        WHEN TIMESTAMPDIFF(YEAR, ei.birth_date, CURDATE()) BETWEEN 25 AND 34 THEN '25-34'
        WHEN TIMESTAMPDIFF(YEAR, ei.birth_date, CURDATE()) BETWEEN 35 AND 44 THEN '35-44'
        WHEN TIMESTAMPDIFF(YEAR, ei.birth_date, CURDATE()) BETWEEN 45 AND 54 THEN '45-54'
        ELSE '55+'
    END as age_range,
    COUNT(DISTINCT ja.id) as count
FROM tbl_job_application ja
JOIN tbl_job_listing jl ON ja.job_id = jl.job_id
JOIN tbl_emp_info ei ON ja.emp_id = ei.user_id
WHERE jl.employer_id = ?
GROUP BY age_range
ORDER BY 
    CASE age_range
        WHEN '15-17' THEN 1
        WHEN '18-24' THEN 2
        WHEN '25-34' THEN 3
        WHEN '35-44' THEN 4
        WHEN '45-54' THEN 5
        ELSE 6
    END";

$stmt = $conn->prepare($age_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$age_result = $stmt->get_result();
$age_data = [];
while ($row = $age_result->fetch_assoc()) {
    $age_data[$row['age_range']] = $row['count'];
}
$stmt->close();

// Get cities data from PSGC API
$citiesJson = file_get_contents('https://psgc.gitlab.io/api/cities/');
$cities = json_decode($citiesJson, true);

// Query to get all applicants' addresses for this company's job listings
$locationQuery = "SELECT DISTINCT e.address 
                 FROM tbl_emp_info e 
                 INNER JOIN tbl_job_application ja ON e.user_id = ja.emp_id 
                 INNER JOIN tbl_job_listing jl ON ja.job_id = jl.job_id 
                 WHERE jl.employer_id = ?";
$stmt = $conn->prepare($locationQuery);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize array to store city counts
$cityCounts = array();

// Process each address
while ($row = $result->fetch_assoc()) {
    $address = strtolower($row['address']);
    
    // Check each city from PSGC data
    foreach ($cities as $city) {
        $cityName = strtolower($city['name']);
        // Remove "City of" prefix if present
        $cityName = str_replace('city of ', '', $cityName);
        
        if (strpos($address, $cityName) !== false) {
            if (isset($cityCounts[$city['name']])) {
                $cityCounts[$city['name']]++;
            } else {
                $cityCounts[$city['name']] = 1;
            }
            break; // Found a match, move to next address
        }
    }
}

// Sort cities by count in descending order
arsort($cityCounts);

// Take top 5 cities for better visualization
$topCities = array_slice($cityCounts, 0, 5, true);

// Calculate total applicants for percentage
$totalApplicants = array_sum($cityCounts);

// need separate table for tracking views
// placeholder fr now
$total_visitors = 0;
$visitors_change = 0;
?>