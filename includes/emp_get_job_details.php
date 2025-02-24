<?php
require "../includes/db_connect.php";

if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);
    $stmt = $conn->prepare("SELECT jl.title, jl.description, jl.requirements, jl.employment_type, jl.location, jl.salary_min, jl.salary_max, jl.currency, jl.posted_date, jl.expiry_date, c.companyName 
                            FROM tbl_job_listing jl 
                            JOIN tbl_company c ON jl.employer_id = c.company_id 
                            WHERE jl.job_id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $job = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($job) {
        echo "<div class='job-title'>" . htmlspecialchars($job['title']) . "</div>";
        echo "<div class='job-details'>";
        echo "<p><strong>Company:</strong> " . htmlspecialchars($job['companyName']) . "</p>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($job['description']) . "</p>";
        echo "<p><strong>Requirements:</strong> " . htmlspecialchars($job['requirements']) . "</p>";
        echo "<p><strong>Employment Type:</strong> " . htmlspecialchars($job['employment_type']) . "</p>";
        echo "<p><strong>Location:</strong> " . htmlspecialchars($job['location']) . "</p>";
        echo "<p><strong>Salary:</strong> " . htmlspecialchars($job['salary_min']) . " - " . htmlspecialchars($job['salary_max']) . " " . htmlspecialchars($job['currency']) . "</p>";
        echo "<p><strong>Posted Date:</strong> " . htmlspecialchars($job['posted_date']) . "</p>";
        echo "<p><strong>Expiry Date:</strong> " . htmlspecialchars($job['expiry_date']) . "</p>";
        echo "</div>";
    } else {
        echo "<p>Job details not found.</p>";
    }
} else {
    echo "<p>Invalid job ID.</p>";
}

$conn->close();
?>
