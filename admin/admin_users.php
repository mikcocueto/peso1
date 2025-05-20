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
      <h2 class="fs-3 mb-4">Users Management</h2>
      <div class="card mb-4 fade-in">
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
                <th>Joined</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include '../includes/db_connect.php'; // Include database connection
              $query = "SELECT user_id, CONCAT(firstName, ' ', lastName) AS fullName, emailAddress, 'Job Seeker' AS role, create_timestamp FROM tbl_emp_info";
              $result = mysqli_query($conn, $query);
              if ($result && mysqli_num_rows($result) > 0) {
                  $counter = 1;
                  while ($row = mysqli_fetch_assoc($result)) {
                      echo "<tr>";
                      echo "<td>{$counter}</td>";
                      echo "<td>{$row['fullName']}</td>";
                      echo "<td>{$row['emailAddress']}</td>";
                      echo "<td>{$row['role']}</td>";
                      echo "<td>" . date('F d, Y', strtotime($row['create_timestamp'])) . "</td>";
                      echo "<td>
                              <button class='btn btn-sm btn-outline-secondary'><i class='bi bi-pencil'></i> Edit</button>
                              <button class='btn btn-sm btn-outline-danger'><i class='bi bi-person-x'></i> Ban</button>
                            </td>";
                      echo "</tr>";
                      $counter++;
                  }
              } else {
                  echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
              }
              ?>
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
