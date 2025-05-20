<!DOCTYPE html>
<?php
require_once '../includes/db_connect.php';

// Initialize message variables
$success_message = '';
$error_message = '';

// Create jobs table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_title VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    job_type VARCHAR(50) NOT NULL,
    status ENUM('Active', 'Pending', 'Rejected') DEFAULT 'Pending',
    date_posted DATETIME DEFAULT CURRENT_TIMESTAMP,
    description TEXT,
    requirements TEXT,
    salary VARCHAR(100),
    contact_email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($create_table_sql) === TRUE) {
    // Table created successfully or already exists
} else {
    echo "Error creating table: " . $conn->error;
}

// Handle job status updates
if (isset($_POST['action']) && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $action = $_POST['action'];
    
    try {
        switch ($action) {
            case 'approve':
                $sql = "UPDATE tbl_job_listing SET status = 'Active' WHERE job_id = ?";
                $success_message = "Job has been approved successfully!";
                break;
            case 'reject':
                $sql = "UPDATE tbl_job_listing SET status = 'Rejected' WHERE job_id = ?";
                $success_message = "Job has been rejected.";
                break;
            case 'delete':
                $sql = "DELETE FROM tbl_job_listing WHERE job_id = ?";
                $success_message = "Job has been deleted successfully!";
                break;
        }
        
        if (isset($sql)) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $job_id);
            
            if ($stmt->execute()) {
                // Success - message already set above
            } else {
                throw new Exception("Database error occurred.");
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Fetch all jobs with error handling
try {
    $sql = "SELECT j.*, c.companyName, c.company_verified 
            FROM tbl_job_listing j 
            LEFT JOIN tbl_comp_info c ON j.employer_id = c.company_id 
            ORDER BY j.posted_date DESC";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Error fetching jobs: " . $conn->error);
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
    $result = false;
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Job Finder</title>
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../dark_mode.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4f46e5;
      --primary-hover: #4338ca;
      --secondary-color: #64748b;
      --success-color: #10b981;
      --danger-color: #ef4444;
      --warning-color: #f59e0b;
      --background-color: #f8fafc;
      --card-background: #ffffff;
      --text-primary: #1e293b;
      --text-secondary: #64748b;
      --border-color: #e2e8f0;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--background-color);
      color: var(--text-primary);
    }

    .sidebar {
      height: 100vh;
      background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
      color: white;
      position: fixed;
      width: 250px;
      z-index: 1000;
      box-shadow: var(--shadow-lg);
    }

    .sidebar a {
      color: #e2e8f0;
      text-decoration: none;
      padding: 0.75rem 1.25rem;
      display: block;
      border-radius: 0.5rem;
      margin: 0.25rem 0.75rem;
      transition: all 0.3s ease;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
      transform: translateX(5px);
    }

    .main-content {
      margin-left: 250px;
      padding: 2rem;
      min-height: 100vh;
      background-color: var(--background-color);
      margin-top: 5rem;
      position: relative;
      z-index: 1;
      padding-top: 2.5rem;
    }

    .page-header {
      margin-bottom: 2.5rem;
      position: relative;
      padding: 2rem;
      background: white;
      border-radius: 1rem;
      box-shadow: var(--shadow);
      margin-top: 1.5rem;
      border: 1px solid var(--border-color);
    }

    .page-title {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .page-title i {
      color: var(--primary-color);
      font-size: 1.5rem;
    }

    .page-subtitle {
      color: #64748b;
      font-size: 0.875rem;
      margin: 0;
    }

    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2.5rem;
      padding: 0 0.5rem;
    }

    .stat-card {
      background: white;
      border-radius: 1rem;
      padding: 1.5rem;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      border: 1px solid var(--border-color);
    }

    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .stat-title {
      color: var(--text-secondary);
      font-size: 0.875rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .stat-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.25rem;
    }

    .stat-change {
      font-size: 0.875rem;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .stat-change.positive {
      color: #22c55e;
    }

    .stat-change.negative {
      color: #ef4444;
    }

    .card {
      background: white;
      border: none;
      border-radius: 1rem;
      box-shadow: var(--shadow);
      margin-bottom: 2rem;
      margin-top: 1rem;
      border: 1px solid var(--border-color);
    }

    .card-header {
      background: white;
      border-bottom: 1px solid var(--border-color);
      padding: 1.5rem;
      border-radius: 1rem 1rem 0 0 !important;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card-header h5 {
      font-weight: 600;
      color: #1e293b;
      margin: 0;
      font-size: 1.125rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .card-header h5 i {
      color: #2563eb;
    }

    .table {
      margin-bottom: 0;
      padding: 0 1.5rem;
    }

    .table th {
      font-weight: 600;
      color: var(--text-secondary);
      border-bottom: 2px solid var(--border-color);
      padding: 1.25rem 1rem;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      background: #f8fafc;
    }

    .table td {
      padding: 1.25rem 1rem;
      vertical-align: middle;
      color: var(--text-primary);
      border-bottom: 1px solid var(--border-color);
      font-size: 0.875rem;
    }

    .table tbody tr:nth-child(odd) td {
      background-color: #f8fafc;
    }

    .table tbody tr:nth-child(even) td {
      background-color: #ffffff;
    }

    .table tbody tr:hover td {
      background-color: #f1f5f9 !important;
    }

    .table tbody tr {
      transition: background-color 0.2s ease;
    }

    .table-responsive {
      padding: 0 1.5rem;
    }

    /* Status Badge Styles */
    .table td .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.5rem 1rem;
      border-radius: 2rem;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      box-shadow: var(--shadow-sm);
    }

    .table td .status-badge i {
      font-size: 0.625rem;
      margin-right: 0.375rem;
    }

    .table td .status-badge.status-active {
      background-color: #16a34a !important;
      color: #16a34a !important;
    }

    .table td .status-badge.status-pending {
      background-color: #ca8a04 !important;
      color: #ca8a04 !important;
    }

    .table td .status-badge.status-rejected {
      background-color: #dc2626 !important;
      color: #dc2626 !important;
    }

    .btn {
      padding: 0.625rem 1.25rem;
      font-weight: 500;
      border-radius: 0.5rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s ease;
      box-shadow: var(--shadow-sm);
    }

    .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }

    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-primary:hover {
      background-color: var(--primary-hover);
      border-color: var(--primary-hover);
      transform: translateY(-1px);
      box-shadow: var(--shadow);
    }

    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      color: white;
      transform: translateY(-1px);
      box-shadow: var(--shadow);
    }

    .btn-outline-danger {
      color: var(--danger-color);
      border-color: var(--danger-color);
    }

    .btn-outline-danger:hover {
      background-color: var(--danger-color);
      color: white;
      transform: translateY(-1px);
      box-shadow: var(--shadow);
    }

    .btn-outline-success {
      color: var(--success-color);
      border-color: var(--success-color);
    }

    .btn-outline-success:hover {
      background-color: var(--success-color);
      color: white;
      transform: translateY(-1px);
      box-shadow: var(--shadow);
    }

    .action-buttons {
      display: flex;
      gap: 0.5rem;
    }

    .empty-state {
      text-align: center;
      padding: 4rem 1rem;
      color: #64748b;
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: #e2e8f0;
    }

    .empty-state p {
      font-size: 1rem;
      margin-bottom: 0;
    }

    .error-state {
      text-align: center;
      padding: 4rem 1rem;
      color: #ef4444;
    }

    .error-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .error-state p {
      font-size: 1rem;
      margin-bottom: 1rem;
    }

    .error-state .btn-retry {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    /* Toast Notifications */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1050;
    }

    .toast {
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      padding: 1rem;
      margin-bottom: 0.5rem;
      min-width: 300px;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      animation: slideIn 0.3s ease-out;
    }

    .toast-success {
      border-left: 4px solid var(--success-color);
    }

    .toast-error {
      border-left: 4px solid var(--danger-color);
    }

    .toast-icon {
      font-size: 1.25rem;
    }

    .toast-success .toast-icon {
      color: var(--success-color);
    }

    .toast-error .toast-icon {
      color: var(--danger-color);
    }

    .toast-content {
      flex: 1;
    }

    .toast-title {
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .toast-message {
      color: var(--text-secondary);
      font-size: 0.875rem;
    }

    .toast-close {
      background: none;
      border: none;
      color: var(--text-secondary);
      cursor: pointer;
      padding: 0.25rem;
      font-size: 1.25rem;
      line-height: 1;
    }

    .toast-close:hover {
      color: var(--text-primary);
    }

    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    /* Loading State */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1060;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .loading-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .loading-spinner {
      width: 40px;
      height: 40px;
      border: 3px solid var(--border-color);
      border-top-color: var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* Form Loading State */
    .btn-loading {
      position: relative;
      pointer-events: none;
    }

    .btn-loading .btn-text {
      visibility: hidden;
    }

    .btn-loading::after {
      content: '';
      position: absolute;
      width: 16px;
      height: 16px;
      top: 50%;
      left: 50%;
      margin: -8px 0 0 -8px;
      border: 2px solid transparent;
      border-top-color: currentColor;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    /* Column width adjustments */
    .table th:nth-child(1), .table td:nth-child(1) { width: 5%; }
    .table th:nth-child(2), .table td:nth-child(2) { width: 25%; }
    .table th:nth-child(3), .table td:nth-child(3) { width: 15%; }
    .table th:nth-child(4), .table td:nth-child(4) { width: 20%; }
    .table th:nth-child(5), .table td:nth-child(5) { width: 10%; }
    .table th:nth-child(6), .table td:nth-child(6) { width: 10%; }
    .table th:nth-child(7), .table td:nth-child(7) { width: 10%; }
    .table th:nth-child(8), .table td:nth-child(8) { width: 15%; }

    .truncate {
      max-width: 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      position: relative;
    }

    .truncate:hover {
      overflow: visible;
      white-space: normal;
      word-break: break-word;
      position: relative;
      z-index: 1;
      background: white;
      box-shadow: var(--shadow-md);
      border-radius: 0.5rem;
      padding: 0.75rem;
      margin: -0.75rem;
    }
  </style>
</head>
<body>
<!-- Toast Container -->
<div class="toast-container">
  <?php if ($success_message): ?>
  <div class="toast toast-success" role="alert">
    <i class="bi bi-check-circle-fill toast-icon"></i>
    <div class="toast-content">
      <div class="toast-title">Success</div>
      <div class="toast-message"><?php echo htmlspecialchars($success_message); ?></div>
    </div>
    <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
  </div>
  <?php endif; ?>

  <?php if ($error_message): ?>
  <div class="toast toast-error" role="alert">
    <i class="bi bi-exclamation-circle-fill toast-icon"></i>
    <div class="toast-content">
      <div class="toast-title">Error</div>
      <div class="toast-message"><?php echo htmlspecialchars($error_message); ?></div>
    </div>
    <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
  </div>
  <?php endif; ?>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay">
  <div class="loading-spinner"></div>
</div>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar p-0">
      <?php include '../admin/side&nav.php'; ?>
    </div>

    <!-- Main content -->
    <main class="col-md-10 main-content">
      <div class="page-header">
        <h1 class="page-title">
          <i class="bi bi-briefcase"></i>
          Jobs Management
        </h1>
        <p class="page-subtitle">Manage and monitor all job listings in your platform</p>
      </div>

      <div class="stats-container">
        <div class="stat-card">
          <div class="stat-title">
            <i class="bi bi-check-circle"></i>
            Active Jobs
          </div>
          <div class="stat-value">
            <?php
            $active_count = $conn->query("SELECT COUNT(*) as count FROM tbl_job_listing WHERE status = 'Active'")->fetch_assoc()['count'];
            echo $active_count;
            ?>
          </div>
          <div class="stat-change positive">
            <i class="bi bi-arrow-up"></i>
            <span>12% from last month</span>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-title">
            <i class="bi bi-clock"></i>
            Pending Jobs
          </div>
          <div class="stat-value">
            <?php
            $pending_count = $conn->query("SELECT COUNT(*) as count FROM tbl_job_listing WHERE status = 'Pending'")->fetch_assoc()['count'];
            echo $pending_count;
            ?>
          </div>
          <div class="stat-change negative">
            <i class="bi bi-arrow-down"></i>
            <span>5% from last month</span>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-title">
            <i class="bi bi-x-circle"></i>
            Rejected Jobs
          </div>
          <div class="stat-value">
            <?php
            $rejected_count = $conn->query("SELECT COUNT(*) as count FROM tbl_job_listing WHERE status = 'Rejected'")->fetch_assoc()['count'];
            echo $rejected_count;
            ?>
          </div>
          <div class="stat-change">
            <span>No change</span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h5>
            <i class="bi bi-list-ul"></i>
            All Job Listings
          </h5>
          <button class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add New Job
          </button>
        </div>
        <div class="card-body p-0">
          <?php if ($result && $result->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Job Title</th>
                  <th>Company Name</th>
                  <th>Location</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Date Posted</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    $status_class = '';
                    $status_icon = '';
                    switch ($row['status']) {
                        case 'Active':
                            $status_class = 'status-active';
                            $status_icon = 'bi-check-circle-fill';
                            break;
                        case 'Pending':
                            $status_class = 'status-pending';
                            $status_icon = 'bi-clock-fill';
                            break;
                        case 'Rejected':
                            $status_class = 'status-rejected';
                            $status_icon = 'bi-x-circle-fill';
                            break;
                    }
                    
                    $action_buttons = '';
                    if ($row['status'] == 'Pending') {
                        $action_buttons = '
                            <div class="action-buttons">
                                <form method="POST" class="action-form" onsubmit="return showLoading(this)">
                                    <input type="hidden" name="job_id" value="' . $row['job_id'] . '">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <span class="btn-text">
                                            <i class="bi bi-check"></i>
                                            Approve
                                        </span>
                                    </button>
                                </form>
                                <form method="POST" class="action-form" onsubmit="return showLoading(this)">
                                    <input type="hidden" name="job_id" value="' . $row['job_id'] . '">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <span class="btn-text">
                                            <i class="bi bi-x"></i>
                                            Reject
                                        </span>
                                    </button>
                                </form>
                            </div>';
                    } else {
                        $action_buttons = '
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" onclick="editJob(' . $row['job_id'] . ')">
                                    <i class="bi bi-pencil"></i>
                                    Edit
                                </button>
                                <form method="POST" class="action-form" onsubmit="return confirmDelete(this)">
                                    <input type="hidden" name="job_id" value="' . $row['job_id'] . '">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <span class="btn-text">
                                            <i class="bi bi-trash"></i>
                                            Delete
                                        </span>
                                    </button>
                                </form>
                            </div>';
                    }
                    
                    echo '<tr>
                        <td>' . $counter . '</td>
                        <td class="truncate">' . htmlspecialchars($row['title']) . '</td>
                        <td class="truncate">
                            ' . htmlspecialchars($row['companyName']) . '
                            ' . ($row['company_verified'] ? '<i class="bi bi-patch-check-fill text-primary ms-1" title="Verified Company"></i>' : '') . '
                        </td>
                        <td class="truncate" title="' . htmlspecialchars($row['location']) . '">' . htmlspecialchars($row['location']) . '</td>
                        <td class="truncate">' . htmlspecialchars($row['employment_type']) . '</td>
                        <td><span class="status-badge ' . $status_class . '"><i class="bi ' . $status_icon . '"></i>' . htmlspecialchars($row['status']) . '</span></td>
                        <td>' . date('M d, Y', strtotime($row['posted_date'])) . '</td>
                        <td>' . $action_buttons . '</td>
                    </tr>';
                    $counter++;
                }
                ?>
              </tbody>
            </table>
          </div>
          <?php elseif ($error_message): ?>
          <div class="error-state">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <p>Failed to load jobs</p>
            <button class="btn btn-primary btn-retry" onclick="window.location.reload()">
              <i class="bi bi-arrow-clockwise"></i>
              Retry
            </button>
          </div>
          <?php else: ?>
          <div class="empty-state">
            <i class="bi bi-briefcase"></i>
            <p>No jobs found</p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../dark_mode.js"></script>
<script>
function editJob(jobId) {
    window.location.href = 'edit_job.php?id=' + jobId;
}

function showLoading(form) {
    const button = form.querySelector('button[type="submit"]');
    button.classList.add('btn-loading');
    return true;
}

function confirmDelete(form) {
    if (confirm('Are you sure you want to delete this job?')) {
        showLoading(form);
        return true;
    }
    return false;
}

// Auto-hide toasts after 5 seconds
document.querySelectorAll('.toast').forEach(toast => {
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
});
</script>
</body>
</html>
