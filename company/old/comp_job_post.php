<?php
session_start();
if (!isset($_SESSION["company_id"])) { // Changed from employer_id to company_id
    header("Location: comp_dashboard.php");
    exit();
}

require "../includes/db_connect.php";

// Fetch job categories
$categories = $conn->query("SELECT category_id, category_name FROM tbl_job_category");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="text-center">Create Job Listing</h3>
                    <form action="../includes/company/comp_job_upload_process.php" method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Job Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Requirements</label>
                            <textarea class="form-control" name="requirements" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="employment_type" class="form-label">Employment Type</label>
                            <select class="form-select" name="employment_type" required>
                                <option value="Full time">Full-time</option>
                                <option value="Part time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Temporary">Temporary</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label for="salary_min" class="form-label">Minimum Salary</label>
                            <input type="number" class="form-control" name="salary_min" required>
                        </div>
                        <div class="mb-3">
                            <label for="salary_max" class="form-label">Maximum Salary</label>
                            <input type="number" class="form-control" name="salary_max" required>
                        </div>
                        <div class="mb-3">
                            <label for="currency" class="form-label">Currency</label>
                            <input type="text" class="form-control" name="currency" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Job Category</label>
                            <select class="form-select" name="category_id" required>
                                <?php while ($category = $categories->fetch_assoc()): ?>
                                    <option value="<?= $category['category_id'] ?>"><?= htmlspecialchars($category['category_name']) ?></option>
                                <?php endwhile; ?>
                            </select>   
                        </div>
                        <div class="mb-3">
                            <label for="expiry_date" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="expiry_date" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Create Job Listing</button>
                        <a href="comp_dashboard.php">Back to Company Dashboard</a><br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
