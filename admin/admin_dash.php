<?php
// Database connection
include '../includes/db_connect.php'; // Make sure this file contains the database connection logic

session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include "../includes/db_connect.php";

// Fetch Total Jobs
$totalJobsQuery = "SELECT COUNT(*) AS total_jobs FROM tbl_job_listing";
$totalJobsResult = $conn->query($totalJobsQuery);
$totalJobs = $totalJobsResult->fetch_assoc()['total_jobs'];

// Fetch Active Users
$activeUsersQuery = "SELECT COUNT(*) AS active_users FROM tbl_emp_info";
$activeUsersResult = $conn->query($activeUsersQuery);
$activeUsers = $activeUsersResult->fetch_assoc()['active_users'];

// Fetch Companies
$totalCompaniesQuery = "SELECT COUNT(*) AS total_companies FROM tbl_comp_info";
$totalCompaniesResult = $conn->query($totalCompaniesQuery);
$totalCompanies = $totalCompaniesResult->fetch_assoc()['total_companies'];

// Fetch Applications
$totalApplicationsQuery = "SELECT COUNT(*) AS total_applications FROM tbl_job_application";
$totalApplicationsResult = $conn->query($totalApplicationsQuery);
$totalApplications = $totalApplicationsResult->fetch_assoc()['total_applications'];

// Fetch 10 Most Recent Job Postings
$recentJobsQuery = "SELECT job_id, title, 
                    (SELECT companyName FROM tbl_comp_info WHERE company_id = employer_id) AS company_name,
                    (SELECT company_verified FROM tbl_comp_info WHERE company_id = employer_id) AS company_verified,
                    status, posted_date 
                    FROM tbl_job_listing 
                    ORDER BY posted_date DESC 
                    LIMIT 10";
$recentJobsResult = $conn->query($recentJobsQuery);

// Fetch 5 Most Recent Employee Registrations
$recentUsersQuery = "SELECT firstName, lastName, emailAddress, create_timestamp 
                     FROM tbl_emp_info 
                     ORDER BY create_timestamp DESC 
                     LIMIT 5";
$recentUsersResult = $conn->query($recentUsersQuery);

// Fetch 5 Most Recent Employer Registrations with Email
$recentEmployersQuery = "SELECT ci.companyName, cl.emailAddress, ci.create_time 
                         FROM tbl_comp_info ci
                         JOIN tbl_comp_login cl ON ci.company_id = cl.company_id
                         ORDER BY ci.create_time DESC 
                         LIMIT 5";
$recentEmployersResult = $conn->query($recentEmployersQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PESO Admin</title>
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="../dark_mode.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #2c3e50;
      color: white;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 0.75rem 1rem;
      display: block;
      border-radius: 0.5rem;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #34495e;
    }
    .card, .table, .btn {
      border-radius: 0.75rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
      z-index: -1;
    }
    .badge {
      border-radius: 1rem;
    }
    .navbar {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .content {
      margin-top: 6rem;
    }
    @media (max-width: 768px) {
      .content {
        margin-top: 6rem;
      }
    }
    .table-responsive {
      overflow-x: auto;
    }
    .table-hover tbody tr:hover {
      background-color: #f1f1f1;
    }
    .fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Include Sidebar -->
    <div class="col-md-2 sidebar p-0">
      <?php include '../admin/side&nav.php'; ?>
    </div>

    <!-- Main Content -->
    <main class="col-md-10 ms-auto px-md-4 py-4 content" style="position:relative; z-index:1;">
      <!-- Dashboard Overview -->
      <h2 class="fs-3 mb-4">Dashboard Overview</h2>
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="card text-white bg-primary fade-in">
            <div class="card-body">
              <h5 class="card-title">Total Jobs</h5>
              <p class="card-text fs-4"><?php echo $totalJobs; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-white bg-success fade-in">
            <div class="card-body">
              <h5 class="card-title">Active Users</h5>
              <p class="card-text fs-4"><?php echo $activeUsers; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <a href="../admin/admin_companies.php" class="text-white text-decoration-none">
            <div class="card text-white bg-info fade-in">
              <div class="card-body">
                <h5 class="card-title">Companies</h5>
                <p class="card-text fs-4"><?php echo $totalCompanies; ?></p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3">
          <div class="card text-white bg-warning fade-in">
            <div class="card-body">
              <h5 class="card-title">Applications</h5>
              <p class="card-text fs-4"><?php echo $totalApplications; ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Job Postings Table -->
      <div class="card mb-4 fade-in">
        <div class="card-header bg-white d-flex justify-content-between align-items-center" style="z-index:2; position:relative;">
          <h5 class="mb-0">Recent Job Postings</h5>
          <a href="../admin/admin_jobs.php" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>No.</th>
                <th>Job Title</th>
                <th>Company</th>
                <th>Status</th>
                <th>Posted</th>
                <!-- <th>Actions</th>  -->
              </tr>
            </thead>
            <tbody>
              <?php if ($recentJobsResult->num_rows > 0): ?>
                <?php $counter = 1; ?>
                <?php while ($row = $recentJobsResult->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($row['company_name']); ?>
                        <?php if ($row['company_verified'] == 1): ?>
                            <i class="bi bi-patch-check-fill ms-1" style="color: #0d6efd; font-size: 1.1em;" title="Verified Company"></i>
                        <?php endif; ?>
                    </td>
                    <td>
                      <?php 
                        $statusClass = '';
                        switch(strtolower($row['status'])) {
                            case 'active':
                                $statusClass = 'bg-success';
                                break;
                            case 'paused':
                                $statusClass = 'bg-warning text-dark';
                                break;
                            case 'inactive':
                                $statusClass = 'bg-danger';
                                break;
                            default:
                                $statusClass = 'bg-info';
                        }
                      ?>
                      <span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($row['posted_date']); ?></td>
                    <!--<td>
                      <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</button>
                      <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Review</button>
                    </td> -->
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center">No recent job postings found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Users Table -->
      <div class="card mb-4 fade-in">
        <div class="card-header bg-white d-flex justify-content-between align-items-center" style="z-index:2; position:relative;">
          <h5 class="mb-0">Recent User Signups</h5>
          <a href="../admin/admin_users.php" class="btn btn-sm btn-outline-primary">Manage Users</a>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Joined</th>
               <!-- <th>Actions</th> -->
              </tr>
            </thead>
            <tbody>
              <?php if ($recentUsersResult->num_rows > 0): ?>
                <?php $counter = 1; ?>
                <?php while ($row = $recentUsersResult->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                    <td><?php echo htmlspecialchars($row['emailAddress']); ?></td>
                    <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($row['create_timestamp']))); ?></td>
                    <td>
                     <!-- 
                      <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</button>
                      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-person-x"></i> Ban</button>
                      -->
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center">No recent user signups found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Employer Signups Table -->
      <div class="card mb-4 fade-in">
        <div class="card-header bg-white d-flex justify-content-between align-items-center" style="z-index:2; position:relative;">
          <h5 class="mb-0">Recent Employer Signups</h5>
          <a href="../admin/admin_companies.php" class="btn btn-sm btn-outline-primary">Manage Employers</a>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No.</th>
                <th>Company Name</th>
                <th>Email</th>
                <th>Joined</th>
               <!--  <th>Actions</th>  -->
              </tr>
            </thead>
            <tbody>
              <?php if ($recentEmployersResult->num_rows > 0): ?>
                <?php $counter = 1; ?>
                <?php while ($row = $recentEmployersResult->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($row['companyName']); ?></td>
                    <td><?php echo htmlspecialchars($row['emailAddress']); ?></td>
                    <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($row['create_time']))); ?></td>
                  <!--  <td>
                      <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</button>
                      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-person-x"></i> Ban</button>
                    </td> -->
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center">No recent employer signups found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../dark_mode.js"></script>
</body>
</html>
