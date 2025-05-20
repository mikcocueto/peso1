<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

require "../includes/db_connect.php";

$admin_id = $_SESSION["admin_id"];
$stmt = $conn->prepare("SELECT firstName, lastName FROM tbl_admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($firstName, $lastName);
$stmt->fetch();
$stmt->close();

// Fetch the count of active job listings
$active_jobs_count = $conn->query("SELECT COUNT(*) AS count FROM tbl_job_listing WHERE status = 'active'")->fetch_assoc()['count'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    
    <style>
      .nav-link:hover {
    background-color: #495057;
    border-radius: 5px;
    }
    </style>
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark text-white sidebar collapse" id="sidebarMenu">
            <div class="position-sticky pt-3">
                <div class="d-flex align-items-center mb-3">
                    <img alt="Logo" class="mr-2" height="40" src="https://storage.googleapis.com/a1aa/image/leh5rwW3uEpXCLQpnX9TmmB8YHf1NhgpIXX-JvFg7K0.jpg" width="40"/>
                    <span class="text-xl font-bold">PESO Admin</span>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="admin_job_category.php">
                            <i class="fas fa-briefcase mr-2"></i> Jobs
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-chart-line mr-2"></i> Analytics
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-users mr-2"></i> Candidates
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                    </li>
                </ul>
                <div class="mt-auto">
                    
                    <button class="btn btn-primary w-100 mt-2">Add New Job</button>
                </div>
            </div>
        </nav>
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <button class="btn btn-primary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <input class="form-control form-control-dark w-100" type="text" placeholder="Search..." aria-label="Search">
                </div>
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-bell"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-envelope"></i>
                    </button>
                </div>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img alt="User Avatar" class="rounded-circle me-2" height="40" src="https://storage.googleapis.com/a1aa/image/gG1p5Bzs0qFmJTUMqGPA7zudkZHymK7k1VLXOeMtMTA.jpg" width="40"/>
                        <strong class="text-dark"><?= htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName) ?></strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item"  href="admin_verify_company.php">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../includes/admin/admin_logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>
            <!-- Dashboard Content -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Current active listings</span>
                                <span class="text-primary display-4"><?= $active_jobs_count ?></span>
                            </div>
                            <a class="text-primary" href="#">View all jobs</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">My Earnings</span>
                                
                            </div>
                            <a class="text-primary" href="#">View earnings</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">My Bookings</span>
                                
                            </div>
                            <a class="text-primary" href="#">View bookings</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Talent Demographics</span>
                                <span class="text-muted">ABC</span>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Talent Projects</span>
                                <span class="text-muted">XYZ</span>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Top Active Jobs</span>
                                <span class="text-muted">ABC</span>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Job Progress</span>
                                <span class="text-muted">XYZ</span>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Applications</span>
                                <span class="text-muted">ABC</span>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">New Applications</span>
                                <span class="text-muted">XYZ</span>
                            </div>
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-center mb-2">
                                    <img alt="Applicant 1" class="rounded-circle me-2" height="40" src="https://storage.googleapis.com/a1aa/image/jPqYs91Athp-9NsnUcPtcKhYs4froWAyTzTaaZRmTKg.jpg" width="40"/>
                                    <span class="text-muted">Applicant 1</span>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <img alt="Applicant 2" class="rounded-circle me-2" height="40" src="https://storage.googleapis.com/a1aa/image/A8oj1Ebc8wQ9bpodng2lGmq7zg57Pxhj65BrY_wgmyg.jpg" width="40"/>
                                    <span class="text-muted">Applicant 2</span>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <img alt="Applicant 3" class="rounded-circle me-2" height="40" src="https://storage.googleapis.com/a1aa/image/9huFySc05paM0CF7eia_EpYR7SX0TYp4cbeWrtmeV4Q.jpg" width="40"/>
                                    <span class="text-muted">Applicant 3</span>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <img alt="Applicant 4" class="rounded-circle me-2" height="40" src="https://storage.googleapis.com/a1aa/image/66s0rGxSHjPNUClUreXSEiUoE3vmQm5Ck-XSRIrqezU.jpg" width="40"/>
                                    <span class="text-muted">Applicant 4</span>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <img alt="Applicant 5" class="rounded-circle me-2" height="40" src="https://storage.googleapis.com/a1aa/image/Pan570NdnO6fifrXlqXFdNRRmeHRv7WwLmsazOQ2GYQ.jpg" width="40"/>
                                    <span class="text-muted">Applicant 5</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasSidebarLabel">PESO Admin</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="#">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="#">
                    <i class="fas fa-briefcase mr-2"></i> Jobs
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="#">
                    <i class="fas fa-chart-line mr-2"></i> Analytics
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white">
                    <i class="fas fa-users mr-2"></i> Candidates
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="#">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
            </li>
        </ul>
        <div class="mt-auto">
            <img alt="Add New Job" class="w-100" height="150" src="https://storage.googleapis.com/a1aa/image/UNiLmCEN0pF7peY8Ih-93Oyh4tlYqeuuLFQIELAE6Oo.jpg" width="150"/>
            <button class="btn btn-primary w-100 mt-2">Add New Job</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>