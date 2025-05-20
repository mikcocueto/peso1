<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

include "../includes/db_connect.php";

// Fetch current categories
$query = "SELECT category_name FROM tbl_job_category";
$result = $conn->query($query);
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row["category_name"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Job Categories</title>
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../dark_mode.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #858796;
      --success-color: #1cc88a;
      --info-color: #36b9cc;
      --warning-color: #f6c23e;
      --danger-color: #e74a3b;
      --light-color: #f8f9fc;
      --dark-color: #5a5c69;
      --sidebar-bg: #2c3e50;
      --sidebar-hover: #34495e;
      --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      --transition-speed: 0.3s;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--light-color);
      color: var(--dark-color);
    }

    .sidebar {
      height: 100vh;
      background-color: var(--sidebar-bg);
      color: white;
      transition: all var(--transition-speed);
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 0.75rem 1rem;
      display: block;
      border-radius: 0.5rem;
      transition: all var(--transition-speed);
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: var(--sidebar-hover);
      transform: translateX(5px);
    }

    .card {
      border: none;
      border-radius: 0.75rem;
      box-shadow: var(--card-shadow);
      transition: transform var(--transition-speed);
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .btn {
      border-radius: 0.5rem;
      padding: 0.5rem 1rem;
      font-weight: 500;
      transition: all var(--transition-speed);
    }

    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-primary:hover {
      background-color: #2e59d9;
      border-color: #2e59d9;
      transform: translateY(-2px);
    }

    .category-list {
      list-style: none;
      padding: 0;
    }

    .category-list li {
      background: white;
      padding: 1.25rem;
      margin-bottom: 0.75rem;
      border-radius: 0.75rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      transition: all var(--transition-speed);
      border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .category-list li:hover {
      transform: translateX(5px);
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .btn-group .btn {
      padding: 0.375rem 0.75rem;
      margin: 0 0.25rem;
    }

    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      color: white;
    }

    .btn-outline-danger {
      color: var(--danger-color);
      border-color: var(--danger-color);
    }

    .btn-outline-danger:hover {
      background-color: var(--danger-color);
      color: white;
    }

    .modal-content {
      border: none;
      border-radius: 1rem;
      box-shadow: var(--card-shadow);
    }

    .modal-header {
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
      background-color: var(--light-color);
      border-radius: 1rem 1rem 0 0;
    }

    .modal-footer {
      border-top: 1px solid rgba(0, 0, 0, 0.1);
      background-color: var(--light-color);
      border-radius: 0 0 1rem 1rem;
    }

    .form-control {
      border-radius: 0.5rem;
      padding: 0.75rem 1rem;
      border: 1px solid rgba(0, 0, 0, 0.1);
      transition: all var(--transition-speed);
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
      border-color: var(--primary-color);
    }

    .fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .content {
      margin-top: 6rem;
    }

    .page-header {
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .category-count {
      background-color: var(--primary-color);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 2rem;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .empty-state {
      text-align: center;
      padding: 3rem;
      color: var(--secondary-color);
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: var(--secondary-color);
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar p-0">
      <?php include '../admin/side&nav.php'; ?>
    </div>

    <!-- Main Content -->
    <main class="col-md-10 ms-auto px-md-4 py-4 content">
      <div class="page-header d-flex justify-content-between align-items-center">
        <div>
          <h2 class="fs-3 mb-2">Job Categories</h2>
          <p class="text-muted mb-0">Manage your job categories and classifications</p>
        </div>
        <div class="d-flex align-items-center">
          <span class="category-count me-3">
            <i class="bi bi-tag"></i> <?php echo count($categories); ?> Categories
          </span>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-circle me-2"></i> Add New Category
          </button>
        </div>
      </div>

      <!-- Categories List -->
      <div class="card fade-in">
        <div class="card-body">
          <h5 class="card-title mb-4">
            <i class="bi bi-list-ul me-2"></i>Current Categories
          </h5>
          <?php if (empty($categories)): ?>
            <div class="empty-state">
              <i class="bi bi-folder-x"></i>
              <h4>No Categories Found</h4>
              <p>Start by adding your first job category</p>
              <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-circle me-2"></i> Add Category
              </button>
            </div>
          <?php else: ?>
            <ul class="category-list">
              <?php foreach ($categories as $category): ?>
                <li class="d-flex justify-content-between align-items-center">
                  <div>
                    <span class="fw-medium"><?php echo htmlspecialchars($category); ?></span>
                    
                  </div>
                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" onclick="editCategory('<?php echo htmlspecialchars($category); ?>')" title="Edit Category">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory('<?php echo htmlspecialchars($category); ?>')" title="Delete Category">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel">
          <i class="bi bi-plus-circle me-2"></i>Add New Category
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../includes/admin/process_add_categories.php" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="categories" class="form-label">Enter categories (separated by commas)</label>
            <input type="text" class="form-control" id="categories" name="categories" 
                   placeholder="e.g., Software Development, Marketing, Design" required>
            <small class="text-muted">You can add multiple categories by separating them with commas</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-2"></i>Close
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add Categories
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../dark_mode.js"></script>
<script>
function editCategory(category) {
  // Implement edit functionality
  console.log('Edit category:', category);
}

function deleteCategory(category) {
  if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
    // Implement delete functionality
    console.log('Delete category:', category);
  }
}
</script>
</body>
</html>
