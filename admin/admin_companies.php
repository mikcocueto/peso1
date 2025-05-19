<?php
require_once '../includes/db_connect.php';
session_start();

// Create companies table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS companies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    status ENUM('pending', 'active', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($create_table_sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Handle company status updates
if (isset($_POST['action']) && isset($_POST['company_id'])) {
    $company_id = $_POST['company_id'];
    $action = $_POST['action'];
    
    if ($action === 'approve') {
        $sql = "UPDATE tbl_comp_info SET company_verified = 1 WHERE company_id = ?";
    } elseif ($action === 'reject') {
        $sql = "UPDATE tbl_comp_info SET company_verified = 0 WHERE company_id = ?";
    } elseif ($action === 'delete') {
        $sql = "DELETE FROM tbl_comp_info WHERE company_id = ?";
    }
    
    if (isset($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $company_id);
        $stmt->execute();
        header("Location: admin_companies.php");
        exit();
    }
}

// Fetch all companies
$sql = "SELECT * FROM tbl_comp_info ORDER BY create_time DESC";
$result = $conn->query($sql);
?>
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
      <h2 class="fs-3 mb-4">Companies Management</h2>
      <div class="card mb-4 fade-in">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Registered Companies</h5>
          <button class="btn btn-sm btn-primary" hidden><i class="bi bi-building-add"></i> Add Company</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Company Name</th>
                <th>Email</th>
                <th>Location</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result->num_rows > 0) {
                  $counter = 1;
                  while($row = $result->fetch_assoc()) {
                      $status_class = '';
                      $status_text = $row['company_verified'] ? 'Verified' : 'Pending';
                      $status_class = $row['company_verified'] ? 'bg-success' : 'bg-warning text-dark';
              ?>
              <tr>
                <td><?php echo $counter++; ?></td>
                <td><?php echo htmlspecialchars($row['companyName']); ?></td>
                <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                <td><?php echo htmlspecialchars($row['country']); ?></td>
                <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                <td><?php echo date('F d, Y', strtotime($row['create_time'])); ?></td>
                <td>
                  <?php if (!$row['company_verified']): ?>
                    <form method="POST" style="display: inline;">
                      <input type="hidden" name="company_id" value="<?php echo $row['company_id']; ?>">
                      <input type="hidden" name="action" value="approve">
                      <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-check"></i> Approve</button>
                    </form>
                    <form method="POST" style="display: inline;">
                      <input type="hidden" name="company_id" value="<?php echo $row['company_id']; ?>">
                      <input type="hidden" name="action" value="reject">
                      <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-x"></i> Reject</button>
                    </form>
                  <?php else: ?>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editCompany(<?php echo $row['company_id']; ?>)"><i class="bi bi-pencil"></i> Edit</button>
                    <form method="POST" style="display: inline;">
                      <input type="hidden" name="company_id" value="<?php echo $row['company_id']; ?>">
                      <input type="hidden" name="action" value="delete">
                      <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this company?')"><i class="bi bi-trash"></i> Remove</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
              <?php
                  }
              } else {
                  echo "<tr><td colspan='7' class='text-center'>No companies found</td></tr>";
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
<script>
function editCompany(id) {
    // TODO: Implement edit functionality
    alert('Edit functionality will be implemented soon');
}
</script>
</body>
</html>
