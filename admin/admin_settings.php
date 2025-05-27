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
  <style>
    :root {
      --bg-color: #f8f9fa;
      --text-color: #212529;
      --card-bg: #ffffff;
    }

    body.dark-mode {
      --bg-color: #212529;
      --text-color: #f8f9fa;
      --card-bg: #343a40;
    }

    body {
      background-color: var(--bg-color);
      color: var(--text-color);
      font-family: 'Inter', sans-serif;
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
      background-color: var(--card-bg);
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
      <h2 class="fs-3 mb-4">Settings</h2>
      <div class="card mb-4 fade-in">
        <div class="card-header bg-white">
          <h5 class="mb-0">Account Settings</h5>
        </div>
        <div class="card-body">
          <form>
            <div class="mb-3">
              <label for="adminEmail" class="form-label">Email address</label>
              <input type="email" class="form-control" id="adminEmail" placeholder="admin@example.com">
            </div>
            <div class="mb-3">
              <label for="adminPassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="adminPassword" placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary">Update Account</button>
          </form>
        </div>
      </div>
      <!--
      <div class="card mb-4 fade-in">
        <div class="card-header bg-white">
          <h5 class="mb-0">System Preferences</h5>
        </div>
        <div class="card-body">
          <form>
            <div class="mb-3">
              <label for="timezone" class="form-label">Time Zone</label>
              <select class="form-select" id="timezone">
                <option selected>UTC</option>
                <option>GMT</option>
                <option>PST</option>
                <option>EST</option>
              </select>
            </div>
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" id="darkModeToggle">
              <label class="form-check-label" for="darkModeToggle">Enable Dark Mode</label>
            </div>
            <button type="submit" class="btn btn-success">Save Preferences</button>
          </form>
        </div>
      </div>
      -->
      <!--
      <div class="card mb-4 fade-in">
        <div class="card-header bg-white">
          <h5 class="mb-0">Notification Settings</h5>
        </div>
        <div class="card-body">
          <form>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="emailNotif" checked>
              <label class="form-check-label" for="emailNotif">
                Email Notifications
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="smsNotif">
              <label class="form-check-label" for="smsNotif">
                SMS Notifications
              </label>
            </div>
            <button type="submit" class="btn btn-secondary mt-3">Update Notifications</button>
          </form>
        </div>
      </div>
      -->
    </main>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const isDark = localStorage.getItem('darkMode') === 'true';
    if (isDark) document.body.classList.add('dark-mode');
    darkModeToggle.checked = isDark;

    darkModeToggle.addEventListener('change', function () {
      document.body.classList.toggle('dark-mode');
      localStorage.setItem('darkMode', darkModeToggle.checked);
    });
  });
</script>
</body>
</html>
