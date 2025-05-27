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
      z-index: 2; /* Added z-index */
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
      z-index: 2; /* Updated z-index */
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
      position: absolute;
      top: 100%;
      right: 0;
      left: auto;
      background-color: white;
      min-width: 150px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.15); /* Stronger shadow for separation */
      z-index: 1050;
      border-radius: 0.5rem;
      overflow: hidden;
      border: 1px solid #e0e0e0; /* Add border for separation */
      margin-top: 0.5rem; /* Space between navbar and dropdown */
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
    .sidebar .dropdown-menu {
      position: static !important;
      transform: none !important;
      width: 100%;
      margin-top: 0;
      border: none;
      border-radius: 0;
      background-color: #34495e;
      display: none;
    }
    .sidebar .dropdown-menu.show {
      display: block;
    }
    .sidebar .dropdown-item {
      color: white !important;
      padding: 0.5rem 1rem;
    }
    .sidebar .dropdown-item:hover {
      background-color: #2c3e50;
    }
    .sidebar .dropdown-toggle::after {
      float: right;
      margin-top: 8px;
    }
    /* Remove position/box-shadow/margin from .dropdown-menu here if present, as it's now inline style */
    /* Optionally, you can add: */
    #userDropdownMenu {
      /* Already styled inline for floating effect */
    }
    #userDropdownMenu .dropdown-item {
      background: transparent;
      color: #fff;
      transition: background 0.2s, color 0.2s;
    }
    #userDropdownMenu .dropdown-item:hover {
      background: #34495e;
      color: #00b894;
    }
    #userDropdownMenu .dropdown-divider {
      border-color: #34495e;
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
          <a class="nav-link" href="#" onclick="toggleJobsMenu(event)">
            <i class="bi bi-briefcase"></i> Jobs
            <i class="bi bi-chevron-down float-end"></i>
          </a>
          <ul class="dropdown-menu" id="jobsMenu">
            <li><a class="dropdown-item" href="../admin/admin_jobs.php"><i class="bi bi-list"></i> Job Listing</a></li>
            <li><a class="dropdown-item" href="../admin/admin_job_category.php"><i class="bi bi-list"></i> Job Category</a></li>
            <li><a class="dropdown-item" href="../admin/admin_jobApp.php"><i class="bi bi-file-earmark-text"></i> Job Applications</a></li>
          </ul>
        </li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_users.php"><i class="bi bi-people"></i> Users</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_companies.php"><i class="bi bi-buildings"></i> Companies</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_analytics.php"><i class="bi bi-bar-chart"></i> Analytics</a></li>
      </ul>
    </nav>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2 px-4" style="position: fixed; top: 0; left: 16.6667%; width: 83.3333%; z-index: 2;">
      <div class="container-fluid">
        <!-- Search Form -->
        <form class="d-flex me-auto" role="search">
          <input class="form-control me-2" type="search" placeholder="Search jobs, users..." aria-label="Search">
          <button class="btn btn-outline-primary" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </form>
      </div>
      <!-- User Dropdown Trigger (outside .container-fluid) -->
      <div class="dropdown" id="userDropdownWrapper" style="position: absolute; top: 50%; right: 2.5rem; transform: translateY(-50%);">
        <a href="#" id="userDropdownToggle" class="d-flex align-items-center text-decoration-none" onclick="toggleUserDropdown(event)">
          <i class='bx bx-user'></i>
          <span class="ms-2">Admin</span>
          <i class="bi bi-chevron-down ms-1"></i>
        </a>
        <!-- Floating Dropdown Menu: must be INSIDE this div for correct positioning -->
        <ul class="dropdown-menu dropdown-menu-end" id="userDropdownMenu" style="
          position: absolute;
          top: calc(100% + 2rem);
          right: 0;
          min-width: 220px;
          z-index: 3000;
          display: none;
          box-shadow: 0 8px 24px rgba(44,62,80,0.18);
          border-radius: 0.5rem;
          border: 1px solid #34495e;
          background: #2c3e50;
          padding: 1rem 0;
        ">
          <li>
            <div style="padding: 0.75rem 1.25rem; color: #b2bec3; font-size: 0.95rem; font-weight: 600;">
              Signed in as<br><span style="color: #ffffff;">Admin</span>
            </div>
          </li>
          <li><a class="dropdown-item" href="../admin/admin_settings.php" style="color: #fff !important;"><i class="bi bi-gear"></i> <span style="color: #fff !important;">Settings</span></a></li>
          <li><a class="dropdown-item" href="../admin/help.php" style="color: #fff !important;"><i class="bi bi-question-circle"></i> <span style="color: #fff !important;">Help</span></a></li>
          <li><hr class="dropdown-divider" style="border-color: #34495e;"></li>
          <li><a class="dropdown-item" href="../admin/admin_login.php" style="color: #ff7675 !important;"><i class="bi bi-box-arrow-right"></i> <span style="color: #ff7675 !important;">Logout</span></a></li>
        </ul>
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

  function toggleJobsMenu(event) {
    event.preventDefault();
    const menu = document.getElementById('jobsMenu');
    menu.classList.toggle('show');
  }

  // Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
    const menu = document.getElementById('jobsMenu');
    const jobsLink = event.target.closest('.nav-link');
    
    if (!jobsLink && menu.classList.contains('show')) {
      menu.classList.remove('show');
    }
  });

  // Prevent reload when clicking dropdown items
  document.querySelectorAll('#jobsMenu .dropdown-item').forEach(item => {
    item.addEventListener('click', function(event) {
      event.preventDefault();
      const href = this.getAttribute('href');
      if (href) {
        window.location.href = href;
      }
    });
  });

  function toggleUserDropdown(event) {
    event.preventDefault();
    const menu = document.getElementById('userDropdownMenu');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
  }

  // Close user dropdown when clicking outside
  document.addEventListener('click', function(event) {
    const menu = document.getElementById('userDropdownMenu');
    const toggle = event.target.closest('#userDropdownToggle');
    if (!toggle && menu.style.display === 'block') {
      menu.style.display = 'none';
    }
  });
</script>
</body>
</html>
