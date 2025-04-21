<?php
require "../../includes/db_connect.php";

if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);
    $stmt = $conn->prepare("SELECT jl.title, jl.description, jl.requirements, jl.employment_type, jl.location, jl.salary_min, jl.salary_max, jl.currency, jl.posted_date, jl.expiry_date, jl.job_cover_img, c.companyName 
                            FROM tbl_job_listing jl 
                            JOIN tbl_comp_info c ON jl.employer_id = c.company_id 
                            WHERE jl.job_id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $job = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($job) {
        if (!empty($job['job_cover_img'])) {
            $cover_img_path = "../db/images/job_listing/" . htmlspecialchars($job['job_cover_img']);
            echo "<div class='job-cover-placeholder text-center mb-3'>";
            echo "<img src='" . $cover_img_path . "' alt='Job Cover Image' class='img-fluid rounded' style='width: 894px; height: 319px; object-fit: cover;'>";
            echo "</div>";
        }
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
