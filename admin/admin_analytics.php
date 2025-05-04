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
      <h2 class="mb-4">Analytics Overview</h2>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Total Jobs</h5>
              <p class="display-6">1,250</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Active Users</h5>
              <p class="display-6">3,472</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Registered Companies</h5>
              <p class="display-6">540</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header bg-white">
          <h5 class="mb-0">Monthly Job Postings</h5>
        </div>
        <div class="card-body">
          <canvas id="jobChart" height="100"></canvas>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header bg-white">
          <h5 class="mb-0">User Growth Over Time</h5>
        </div>
        <div class="card-body">
          <canvas id="userChart" height="100"></canvas>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const jobChart = new Chart(document.getElementById('jobChart'), {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
      datasets: [{
        label: 'Jobs Posted',
        data: [200, 300, 250, 400, 350],
        backgroundColor: '#0d6efd'
      }]
    }
  });

  const userChart = new Chart(document.getElementById('userChart'), {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
      datasets: [{
        label: 'New Users',
        data: [500, 700, 650, 800, 900],
        borderColor: '#198754',
        fill: false,
        tension: 0.4
      }]
    }
  });
</script>
<script src="../dark_mode.js"></script>
</body>
</html>
