<?php
require "../db_connect.php";

if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);
    // Fetch job details and coordinate_id
    $stmt = $conn->prepare("SELECT jl.title, jl.description, jl.requirements, jl.employment_type, jl.location, jl.salary_min, jl.salary_max, jl.currency, jl.posted_date, jl.expiry_date, jl.job_cover_img, c.companyName, jl.coordinate_id 
                            FROM tbl_job_listing jl 
                            JOIN tbl_comp_info c ON jl.employer_id = c.company_id 
                            WHERE jl.job_id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $job = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $lat = null;
    $lng = null;
    if ($job && $job['coordinate_id']) {
        // Fetch coordinates from tbl_job_coordinates
        $stmt2 = $conn->prepare("SELECT ST_AsText(coordinates) as coordinates FROM tbl_job_coordinates WHERE id = ?");
        $stmt2->bind_param("i", $job['coordinate_id']);
        $stmt2->execute();
        $coord_row = $stmt2->get_result()->fetch_assoc();
        $stmt2->close();
        if ($coord_row && $coord_row['coordinates']) {
            // Parse "POINT(lon lat)"
            if (preg_match('/POINT\(([-0-9\.]+) ([-0-9\.]+)\)/', $coord_row['coordinates'], $matches)) {
                $lng = $matches[1];
                $lat = $matches[2];
            }
        }
    }

    if ($job) {
        // Add data attributes for coordinates if available
        $coord_data = ($lat && $lng) ? "data-lat='$lat' data-lng='$lng'" : "";
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
        // Add map container if coordinates exist
        if ($lat && $lng) {
            echo "<p><strong>Location Preview:</strong> " . "<div id='job-location-map' style='height:250px; margin-top:15px; border-radius:8px; overflow:hidden;' $coord_data></div>" . "</p>";
        }
    } else {
        echo "<p>Job details not found.</p>";
    }
} else {
    echo "<p>Invalid job ID.</p>";
}

$conn->close();
?>
