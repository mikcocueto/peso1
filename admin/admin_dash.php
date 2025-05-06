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
    .card, .table, .btn {
      border-radius: 0.75rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .badge {
      border-radius: 1rem;
    }
    .navbar {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .content {
      margin-top: 6rem; /* Increased space for the fixed top navbar */
    }
    @media (max-width: 768px) {
      .content {
        margin-top: 6rem; /* Ensure consistent spacing on smaller screens */
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
    <main class="col-md-10 ms-auto px-md-4 py-4 content">
      <!-- Dashboard Overview -->
      <h2 class="fs-3 mb-4">Dashboard Overview</h2>
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="card text-white bg-primary fade-in">
            <div class="card-body">
              <h5 class="card-title">Total Jobs</h5>
              <p class="card-text fs-4">150</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card text-white bg-success fade-in">
            <div class="card-body">
              <h5 class="card-title">Active Users</h5>
              <p class="card-text fs-4">3,200</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <a href="../admin/admin_companies.php" class="text-white text-decoration-none">
            <div class="card text-white bg-info fade-in">
              <div class="card-body">
                <h5 class="card-title">Companies</h5>
                <p class="card-text fs-4">420</p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3">
          <div class="card text-white bg-warning fade-in">
            <div class="card-body">
              <h5 class="card-title">Applications</h5>
              <p class="card-text fs-4">8,500</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Job Postings Table -->
      <div class="card mb-4 fade-in">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Recent Job Postings</h5>
          <a href="../admin/admin_jobs.php"><button class="btn btn-sm btn-outline-primary">View All</button></a>
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
                  <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</button>
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Delete</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Marketing Specialist</td>
                <td>BizCorp</td>
                <td><span class="badge bg-warning text-dark">Pending</span></td>
                <td>1 day ago</td>
                <td>
                  <button class="btn btn-sm btn-outline-success"><i class="bi bi-check"></i> Approve</button>
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x"></i> Reject</button>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>Data Analyst</td>
                <td>AnalyzeIt</td>
                <td><span class="badge bg-danger">Rejected</span></td>
                <td>4 hours ago</td>
                <td>
                  <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Review</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Users Table -->
      <div class="card mb-4 fade-in">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Recent User Signups</h5>
          <a href="../admin/admin_users.php"><button class="btn btn-sm btn-outline-primary">Manage Users</button></a>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No.</th>
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
                  <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</button>
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-person-x"></i> Ban</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Michael Lee</td>
                <td>michael@example.com</td>
                <td>Employer</td>
                <td>Yesterday</td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</button>
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-person-x"></i> Ban</button>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>Sara Kim</td>
                <td>sara@example.com</td>
                <td>Job Seeker</td>
                <td>3 days ago</td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</button>
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-person-x"></i> Ban</button>
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
