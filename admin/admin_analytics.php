<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Job Finder</title>
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
    .card, .btn {
      border-radius: 0.75rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .card h5 {
      font-weight: 600;
    }
    .navbar {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
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
    <!-- Sidebar -->
    <!-- Include Sidebar -->
    <div class="col-md-2 sidebar p-0">
      <?php include '../admin/side&nav.php'; ?>
    </div>

    <!-- Main content -->
    <main class="col-md-10 ms-sm-auto px-md-4 py-4 content">
      <h2 class="fs-3 mb-4">Analytics Overview</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card text-white bg-primary fade-in">
            <div class="card-body text-center">
              <h5 class="card-title">Total Jobs</h5>
              <p class="display-6">1,250</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-success fade-in">
            <div class="card-body text-center">
              <h5 class="card-title">Active Users</h5>
              <p class="display-6">3,472</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-info fade-in">
            <div class="card-body text-center">
              <h5 class="card-title">Registered Companies</h5>
              <p class="display-6">540</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4 fade-in">
        <div class="card-header bg-white">
          <h5 class="mb-0">Monthly Job Postings</h5>
        </div>
        <div class="card-body">
          <canvas id="jobChart" height="100"></canvas>
        </div>
      </div>

      <div class="card mb-4 fade-in">
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
