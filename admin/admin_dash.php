<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PESO Admin</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../dark_mode.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: white;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #495057;
      border-radius: 5px;
    }
    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card h5 {
      font-weight: 600;
    }
    table {
      background-color: white;
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    
    <!-- Sidebar -->
    <nav class="col-md-2 d-none d-md-block sidebar py-4 px-3">
      <h4 class="text-center mb-4">Admin Panel</h4>
      <ul class="nav flex-column">
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_dash.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_jobs.php"><i class="bi bi-briefcase"></i> Jobs</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_users.php"><i class="bi bi-people"></i> Users</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_companies.php"><i class="bi bi-buildings"></i> Companies</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_analytics.php"><i class="bi bi-bar-chart"></i> Analytics</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="../admin/admin_settings.php"><i class="bi bi-gear"></i> Settings</a></li>
      </ul>
    </nav>

    <!-- Main content -->
    <main class="col-md-10 ms-sm-auto px-md-4 py-4">
        <!-- Top Navbar with Search and User Dropdown -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light rounded shadow-sm mb-4">
        <div class="container-fluid">
          <form class="d-flex me-auto" role="search">
            <input class="form-control me-2" type="search" placeholder="Search jobs, users..." aria-label="Search">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
          </form>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-4 me-2"></i>
                <span>Admin</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
      <h2 class="mb-4">Dashboard Overview</h2>
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="card text-white bg-primary">
            <div class="card-body">
              <h5 class="card-title">Total Jobs</h5>
              <p class="card-text fs-4">150</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-white bg-success">
            <div class="card-body">
              <h5 class="card-title">Active Users</h5>
              <p class="card-text fs-4">3,200</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <a href="../admin/admin_companies.php" class="text-white text-decoration-none">
            <div class="card text-white bg-info">
              <div class="card-body">
                <h5 class="card-title">Companies</h5>
                <p class="card-text fs-4">420</p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3">
          <div class="card text-white bg-warning">
            <div class="card-body">
              <h5 class="card-title">Applications</h5>
              <p class="card-text fs-4">8,500</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Job Postings Table -->
      <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Recent Job Postings</h5>
          <a href="../admin/admin_jobs.php"><button class="btn btn-sm btn-outline-primary">View All</button></a>
        </div>
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Job Title</th>
                <th>Company</th>
                <th>Status</th>
                <th>Posted</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Frontend Developer</td>
                <td>TechZone</td>
                <td><span class="badge bg-success">Approved</span></td>
                <td>2 days ago</td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary">Edit</button>
                  <button class="btn btn-sm btn-outline-danger">Delete</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Marketing Specialist</td>
                <td>BizCorp</td>
                <td><span class="badge bg-warning text-dark">Pending</span></td>
                <td>1 day ago</td>
                <td>
                  <button class="btn btn-sm btn-outline-success">Approve</button>
                  <button class="btn btn-sm btn-outline-danger">Reject</button>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>Data Analyst</td>
                <td>AnalyzeIt</td>
                <td><span class="badge bg-danger">Rejected</span></td>
                <td>4 hours ago</td>
                <td>
                  <button class="btn btn-sm btn-outline-primary">Review</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Users Table -->
      <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Recent User Signups</h5>
          <a href="../admin/admin_users.php"><button class="btn btn-sm btn-outline-primary">Manage Users</button></a>
        </div>
        <div class="card-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Alice Johnson</td>
                <td>alice@example.com</td>
                <td>Job Seeker</td>
                <td>Today</td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary">Edit</button>
                  <button class="btn btn-sm btn-outline-danger">Ban</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Michael Lee</td>
                <td>michael@example.com</td>
                <td>Employer</td>
                <td>Yesterday</td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary">Edit</button>
                  <button class="btn btn-sm btn-outline-danger">Ban</button>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>Sara Kim</td>
                <td>sara@example.com</td>
                <td>Job Seeker</td>
                <td>3 days ago</td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary">Edit</button>
                  <button class="btn btn-sm btn-outline-danger">Ban</button>
                </td>
              </tr>
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
