<?php
require_once '../includes/db_connect.php';
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<?php
// Fetch job listings with coordinates
$sql = "
    SELECT 
        jl.job_id, jl.title, jl.description, jl.requirements, jl.employment_type, 
        jl.location, jl.salary_min, jl.salary_max, jl.currency, 
        ST_AsText(jc.coordinates) AS coordinates
    FROM tbl_job_listing jl
    LEFT JOIN tbl_job_coordinates jc ON jl.coordinate_id = jc.id
    WHERE jl.status = 'active'
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $coordinates = $row['coordinates'];
        if ($coordinates) {
            $coordinates = str_replace(['POINT(', ')'], '', $coordinates); // Format coordinates
            list($longitude, $latitude) = explode(' ', $coordinates);
        } else {
            $longitude = $latitude = null; // Default to null if coordinates are missing
        }
        ?>
        <div class="job-listing">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
            <p><strong>Requirements:</strong> <?php echo htmlspecialchars($row['requirements']); ?></p>
            <p><strong>Employment Type:</strong> <?php echo htmlspecialchars($row['employment_type']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
            <p><strong>Salary:</strong> <?php echo htmlspecialchars($row['currency'] . ' ' . $row['salary_min'] . ' - ' . $row['salary_max']); ?></p>
            <?php if ($longitude !== null && $latitude !== null): ?>
                <div class="map-preview" style="width: 100%; height: 300px;" id="map-<?php echo $row['job_id']; ?>"></div>
                <script>
                    var map = L.map('map-<?php echo $row['job_id']; ?>').setView([<?php echo $latitude; ?>, <?php echo $longitude; ?>], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
                    L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>]).addTo(map);
                </script>
            <?php else: ?>
                <p><strong>Map Preview:</strong> Not available</p>
            <?php endif; ?>
        </div>
        <hr>
        <?php
    }
} else {
    echo "<p>No job listings found.</p>";
}
?>
