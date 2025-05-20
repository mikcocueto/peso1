<!DOCTYPE html>
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
      --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
      --success-gradient: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
      --info-gradient: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      height: 100vh;
      background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
      color: white;
      box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar a {
      color: rgba(255, 255, 255, 0.8);
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

    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.75rem 2rem rgba(0, 0, 0, 0.12);
    }

    .card.bg-primary {
      background: var(--primary-gradient) !important;
    }

    .card.bg-success {
      background: var(--success-gradient) !important;
    }

    .card.bg-info {
      background: var(--info-gradient) !important;
    }

    .card-body {
      padding: 1.5rem;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: 500;
      margin-bottom: 1rem;
      opacity: 0.9;
    }

    .display-6 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0;
    }

    .navbar {
      box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.08);
      background: white;
    }

    .content {
      padding: 2rem;
    }

    .fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    canvas {
      max-height: 300px;
    }

    .card-header {
      background: white;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      padding: 1.25rem 1.5rem;
    }

    .card-header h5 {
      font-weight: 600;
      color: #2c3e50;
      margin: 0;
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
      <div class="row g-4 mb-5">
        <?php
        include '../includes/db_connect.php'; // Include database connection

        // Fetch total jobs
        $totalJobsQuery = "SELECT COUNT(*) AS totalJobs FROM tbl_job_listing";
        $totalJobsResult = mysqli_query($conn, $totalJobsQuery);
        $totalJobs = $totalJobsResult ? mysqli_fetch_assoc($totalJobsResult)['totalJobs'] : 0;

        // Fetch active users
        $activeUsersQuery = "SELECT COUNT(*) AS activeUsers FROM tbl_emp_info";
        $activeUsersResult = mysqli_query($conn, $activeUsersQuery);
        $activeUsers = $activeUsersResult ? mysqli_fetch_assoc($activeUsersResult)['activeUsers'] : 0;

        // Fetch registered companies
        $registeredCompaniesQuery = "SELECT COUNT(*) AS registeredCompanies FROM tbl_comp_info";
        $registeredCompaniesResult = mysqli_query($conn, $registeredCompaniesQuery);
        $registeredCompanies = $registeredCompaniesResult ? mysqli_fetch_assoc($registeredCompaniesResult)['registeredCompanies'] : 0;
        ?>
        <div class="col-md-4">
          <div class="card text-white bg-primary fade-in">
            <div class="card-body text-center">
              <h5 class="card-title">Total Jobs</h5>
              <p class="display-6"><?php echo $totalJobs; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-success fade-in">
            <div class="card-body text-center">
              <h5 class="card-title">Active Users</h5>
              <p class="display-6"><?php echo $activeUsers; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-info fade-in">
            <div class="card-body text-center">
              <h5 class="card-title">Registered Companies</h5>
              <p class="display-6"><?php echo $registeredCompanies; ?></p>
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
      <?php
      // Fetch monthly job postings data
      $monthlyJobPostingsQuery = "
        SELECT 
          MONTHNAME(posted_date) AS month, 
          COUNT(*) AS job_count 
        FROM tbl_job_listing 
        WHERE YEAR(posted_date) = YEAR(CURDATE()) 
        GROUP BY MONTH(posted_date) 
        ORDER BY MONTH(posted_date)";
      $monthlyJobPostingsResult = mysqli_query($conn, $monthlyJobPostingsQuery);

      $months = [];
      $jobCounts = [];

      if ($monthlyJobPostingsResult && mysqli_num_rows($monthlyJobPostingsResult) > 0) {
          while ($row = mysqli_fetch_assoc($monthlyJobPostingsResult)) {
              $months[] = $row['month'];
              $jobCounts[] = $row['job_count'];
          }
      }
      ?>

      <div class="card mb-4 fade-in">
        <div class="card-header bg-white">
          <h5 class="mb-0">User Growth Over Time</h5>
        </div>
        <div class="card-body">
          <canvas id="userChart" height="100"></canvas>
        </div>
      </div>
      <?php
      // Fetch user growth data
      $userGrowthQuery = "
        SELECT 
          MONTHNAME(create_timestamp) AS month, 
          COUNT(*) AS user_count 
        FROM tbl_emp_info 
        WHERE YEAR(create_timestamp) = YEAR(CURDATE()) 
        GROUP BY MONTH(create_timestamp) 
        ORDER BY MONTH(create_timestamp)";
      $userGrowthResult = mysqli_query($conn, $userGrowthQuery);

      $userMonths = [];
      $userCounts = [];

      if ($userGrowthResult && mysqli_num_rows($userGrowthResult) > 0) {
          while ($row = mysqli_fetch_assoc($userGrowthResult)) {
              $userMonths[] = $row['month'];
              $userCounts[] = $row['user_count'];
          }
      }
      ?>
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
      labels: <?php echo json_encode($months); ?>, // Dynamic months from PHP
      datasets: [{
        label: 'Jobs Posted',
        data: <?php echo json_encode($jobCounts); ?>, // Dynamic job counts from PHP
        backgroundColor: '#0d6efd'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          position: 'top'
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Month'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Number of Jobs'
          },
          beginAtZero: true
        }
      }
    }
  });

  const userChart = new Chart(document.getElementById('userChart'), {
    type: 'line',
    data: {
      labels: <?php echo json_encode($userMonths); ?>, // Dynamic months from PHP
      datasets: [{
        label: 'New Users',
        data: <?php echo json_encode($userCounts); ?>, // Dynamic user counts from PHP
        borderColor: '#198754',
        backgroundColor: 'rgba(25, 135, 84, 0.2)',
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          position: 'top'
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Month'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Number of Users'
          },
          beginAtZero: true
        }
      }
    }
  });
</script>
<script src="../dark_mode.js"></script>
</body>
</html>
