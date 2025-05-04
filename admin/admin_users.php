<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Job Finder</title>
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
      <h2 class="mb-4">Users Management</h2>
      <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">All Users</h5>
          <button class="btn btn-sm btn-primary"><i class="bi bi-person-plus"></i> Add New User</button>
        </div>
        <div class="card-body">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Jane Smith</td>
                <td>jane@example.com</td>
                <td>Job Seeker</td>
                <td><span class="badge bg-success">Active</span></td>
                <td>April 30, 2025</td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary">Edit</button>
                  <button class="btn btn-sm btn-outline-danger">Ban</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Tom Hanks</td>
                <td>tom@example.com</td>
                <td>Employer</td>
                <td><span class="badge bg-warning text-dark">Pending</span></td>
                <td>April 28, 2025</td>
                <td>
                  <button class="btn btn-sm btn-outline-success">Approve</button>
                  <button class="btn btn-sm btn-outline-danger">Reject</button>
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
