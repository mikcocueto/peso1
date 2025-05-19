<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar & Navbar</title>
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <link href="../dark_mode.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
    }
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 16.6667%; /* Matches col-md-2 width */
      background-color: #2c3e50;
      color: white;
      overflow-y: auto;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 0.75rem 1rem;
      display: block;
      border-radius: 0.5rem;
      margin: 0.25rem 0;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #34495e;
    }
    .sidebar h4 {
      padding: 1rem 0;
      text-align: center;
      border-bottom: 1px solid #34495e;
    }
    .navbar {
      position: fixed;
      top: 0;
      left: 16.6667%; /* Align with the content area */
      width: 83.3333%; /* Matches the content width */
      z-index: 1030;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .content {
      margin-left: 16.6667%; /* Matches sidebar width */
      margin-top: 4rem; /* Space for the navbar */
    }
    .dropdown {
      position: relative;
      display: inline-block;
    }
    .dropdown-menu {
      display: none;
      position: absolute; /* Changed back to absolute for alignment within the parent */
      top: 100%; /* Position below the dropdown trigger */
      right: 0;
      background-color: white;
      min-width: 150px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      z-index: 1050; /* Ensure it appears above other elements */
      border-radius: 0.25rem;
      overflow: hidden;
    }
    .dropdown-menu a {
      color: #2c3e50;
      padding: 0.5rem 1rem;
      text-decoration: none;
      display: block;
    }
    .dropdown-menu a:hover {
      background-color: #f1f1f1;
    }
    .dropdown-menu.show {
      display: block;
    }
    .bx-user {
      color: #2c3e50; /* Set the desired color */
      font-size: 1.5rem; /* Adjust size if needed */
    }
    .bx-user:hover {
      color: #007bff; /* Change color on hover */
    }
    @media (max-width: 768px) {
      .sidebar {
        position: relative;
        width: 100%;
        height: auto;
      }
      .navbar {
        left: 0;
        width: 100%;
      }
      .content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <nav class="sidebar py-4 px-3">
      <h4>Admin Panel</h4>
      <ul class="nav flex-column">
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_dash.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li class="nav-item mb-2">
          <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#jobsCollapse" role="button" aria-expanded="false" aria-controls="jobsCollapse">
            <i class="bi bi-briefcase"></i> Jobs
          </a>
          <div class="collapse" id="jobsCollapse">
            <ul class="nav flex-column ms-3">
              <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_jobs.php"><i class="bi bi-list"></i> Job Listing</a></li>
              <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_job_category.php"><i class="bi bi-list"></i> Job Category</a></li>
              <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_job_applications.php"><i class="bi bi-file-earmark-text"></i> Job Applications</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_users.php"><i class="bi bi-people"></i> Users</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_companies.php"><i class="bi bi-buildings"></i> Companies</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_analytics.php"><i class="bi bi-bar-chart"></i> Analytics</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
      </ul>
    </nav>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2 px-4">
      <div class="container-fluid">
        <!-- Search Form -->
        <form class="d-flex me-auto" role="search">
          <input class="form-control me-2" type="search" placeholder="Search jobs, users..." aria-label="Search">
          <button class="btn btn-outline-primary" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </form>

        <!-- User Dropdown -->
        <div class="dropdown">
          <i class="bx bx-user" onclick="toggleDropdown()" style="cursor: pointer;"></i>
          <div class="dropdown-menu">
            <a href="comp_profile.php">Profile</a>
            <a href="comp_change_password.php">Settings</a>
            <a href="../admin/admin_login.php">Logout</a>
          </div>
        </div>
      </div>
    </nav>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../dark_mode.js"></script>
<script>
  // Ensure Bootstrap's collapse functionality works
  document.addEventListener('DOMContentLoaded', () => {
    const collapses = document.querySelectorAll('[data-bs-toggle="collapse"]');
    collapses.forEach(collapse => {
      collapse.addEventListener('click', () => {
        const target = document.querySelector(collapse.getAttribute('data-bs-target'));
        if (target) {
          target.classList.toggle('show');
        }
      });
    });
  });
</script>
</body>
</html>
