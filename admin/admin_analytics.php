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

      // Get cities data from PSGC API
      $citiesJson = file_get_contents('https://psgc.gitlab.io/api/cities/');
      $cities = json_decode($citiesJson, true);

      // Query to get all users' addresses
      $userLocationQuery = "SELECT address FROM tbl_emp_info";
      $userLocationResult = mysqli_query($conn, $userLocationQuery);

      // Initialize array to store user city counts
      $userCityCounts = array();

      // Process each user address
      while ($row = $userLocationResult->fetch_assoc()) {
          $address = strtolower($row['address']);
          
          foreach ($cities as $city) {
              $cityName = strtolower($city['name']);
              $cityName = str_replace('city of ', '', $cityName);
              
              if (strpos($address, $cityName) !== false) {
                  if (isset($userCityCounts[$city['name']])) {
                      $userCityCounts[$city['name']]++;
                  } else {
                      $userCityCounts[$city['name']] = 1;
                  }
                  break;
              }
          }
      }

      // Sort user cities by count
      arsort($userCityCounts);
      $topUserCities = array_slice($userCityCounts, 0, 5, true);
      $totalUsers = array_sum($userCityCounts);

      // Query to get all job listings with coordinates
      $jobLocationQuery = "SELECT jl.location, jc.coordinates 
                          FROM tbl_job_listing jl 
                          LEFT JOIN tbl_job_coordinates jc ON jl.coordinate_id = jc.id";
      $jobLocationResult = mysqli_query($conn, $jobLocationQuery);

      // Initialize array for job location counts
      $jobLocationCounts = array();

      // Process each job listing
      while ($job = $jobLocationResult->fetch_assoc()) {
          $location = strtolower($job['location']);
          $cityFound = false;
          
          foreach ($cities as $city) {
              $cityName = strtolower($city['name']);
              $cityName = str_replace('city of ', '', $cityName);
              
              if (strpos($location, $cityName) !== false) {
                  if (isset($jobLocationCounts[$city['name']])) {
                      $jobLocationCounts[$city['name']]++;
                  } else {
                      $jobLocationCounts[$city['name']] = 1;
                  }
                  $cityFound = true;
                  break;
              }
          }
          
          // If city not found in location field and coordinates exist, try to get city from coordinates
          if (!$cityFound && !empty($job['coordinates'])) {
              $coords = str_replace(['POINT(', ')'], '', $job['coordinates']);
              list($longitude, $latitude) = explode(' ', $coords);
              
              // Use Nominatim API to get city name from coordinates
              $geocodeUrl = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=10";
              $response = @file_get_contents($geocodeUrl);
              if ($response) {
                  $data = json_decode($response, true);
                  if (isset($data['address']['city'])) {
                      $cityName = $data['address']['city'];
                      if (isset($jobLocationCounts[$cityName])) {
                          $jobLocationCounts[$cityName]++;
                      } else {
                          $jobLocationCounts[$cityName] = 1;
                      }
                  }
              }
          }
      }

      // Sort job locations by count
      arsort($jobLocationCounts);
      $topJobLocations = array_slice($jobLocationCounts, 0, 5, true);
      $totalJobs = array_sum($jobLocationCounts);

      // Define all possible age ranges
      $allAgeRanges = ['15-24', '25-34', '35-44', '45-54', '55-64', '65+'];
      
      // Query to get age distribution
      $ageQuery = "SELECT 
        CASE 
          WHEN age BETWEEN 15 AND 24 THEN '15-24'
          WHEN age BETWEEN 25 AND 34 THEN '25-34'
          WHEN age BETWEEN 35 AND 44 THEN '35-44'
          WHEN age BETWEEN 45 AND 54 THEN '45-54'
          WHEN age BETWEEN 55 AND 64 THEN '55-64'
          ELSE '65+'
        END as age_range,
        COUNT(*) as count
        FROM tbl_emp_info
        WHERE age IS NOT NULL
        GROUP BY age_range
        ORDER BY 
          CASE age_range
            WHEN '15-24' THEN 1
            WHEN '25-34' THEN 2
            WHEN '35-44' THEN 3
            WHEN '45-54' THEN 4
            WHEN '55-64' THEN 5
            ELSE 6
          END";
      
      $ageResult = mysqli_query($conn, $ageQuery);
      
      // Initialize arrays with all age ranges and zero counts
      $ageRanges = $allAgeRanges;
      $ageCounts = array_fill(0, count($allAgeRanges), 0);
      
      // Update counts for age ranges that have data
      if ($ageResult && mysqli_num_rows($ageResult) > 0) {
          while ($row = mysqli_fetch_assoc($ageResult)) {
              $index = array_search($row['age_range'], $allAgeRanges);
              if ($index !== false) {
                  $ageCounts[$index] = $row['count'];
              }
          }
      }

      // Query to get age distribution of hired users
      $hiredAgeQuery = "SELECT 
        CASE 
          WHEN e.age BETWEEN 15 AND 24 THEN '15-24'
          WHEN e.age BETWEEN 25 AND 34 THEN '25-34'
          WHEN e.age BETWEEN 35 AND 44 THEN '35-44'
          WHEN e.age BETWEEN 45 AND 54 THEN '45-54'
          WHEN e.age BETWEEN 55 AND 64 THEN '55-64'
          ELSE '65+'
        END as age_range,
        COUNT(*) as count
        FROM tbl_job_application ja
        JOIN tbl_emp_info e ON ja.emp_id = e.user_id
        WHERE ja.status = 'hired'
        GROUP BY age_range
        ORDER BY 
          CASE age_range
            WHEN '15-24' THEN 1
            WHEN '25-34' THEN 2
            WHEN '35-44' THEN 3
            WHEN '45-54' THEN 4
            WHEN '55-64' THEN 5
            ELSE 6
          END";
      
      $hiredAgeResult = mysqli_query($conn, $hiredAgeQuery);
      
      // Initialize arrays with all age ranges and zero counts for hired users
      $hiredAgeRanges = $allAgeRanges;
      $hiredAgeCounts = array_fill(0, count($allAgeRanges), 0);
      
      // Update counts for age ranges that have data
      if ($hiredAgeResult && mysqli_num_rows($hiredAgeResult) > 0) {
          while ($row = mysqli_fetch_assoc($hiredAgeResult)) {
              $index = array_search($row['age_range'], $allAgeRanges);
              if ($index !== false) {
                  $hiredAgeCounts[$index] = $row['count'];
              }
          }
      }
      ?>

      <!-- City Distribution Cards -->
      <div class="row g-4 mb-4">
        <div class="col-md-6">
          <div class="card fade-in">
            <div class="card-header bg-white">
              <h5 class="mb-0">User Distribution by City</h5>
            </div>
            <div class="card-body">
              <canvas id="userCityChart" height="300"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card fade-in">
            <div class="card-header bg-white">
              <h5 class="mb-0">Job Listings Distribution by City</h5>
            </div>
            <div class="card-body">
              <canvas id="jobCityChart" height="300"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Age Distribution Cards -->
      <div class="row g-4 mb-4">
        <div class="col-md-6">
          <div class="card fade-in">
            <div class="card-header bg-white">
              <h5 class="mb-0">User Age Distribution</h5>
            </div>
            <div class="card-body">
              <canvas id="ageChart" height="300"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card fade-in">
            <div class="card-header bg-white">
              <h5 class="mb-0">Hired Users Age Distribution</h5>
            </div>
            <div class="card-body">
              <canvas id="hiredAgeChart" height="300"></canvas>
            </div>
          </div>
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

  // User City Distribution Chart
  const userCityChart = new Chart(document.getElementById('userCityChart'), {
    type: 'pie',
    data: {
      labels: <?php echo json_encode(array_keys($topUserCities)); ?>,
      datasets: [{
        data: <?php echo json_encode(array_values($topUserCities)); ?>,
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              const value = context.raw;
              const percentage = ((value / <?php echo $totalUsers; ?>) * 100).toFixed(1);
              return `${context.label}: ${value} users (${percentage}%)`;
            }
          }
        },
        legend: {
          position: 'right',
          labels: {
            boxWidth: 15,
            padding: 15
          }
        }
      }
    }
  });

  // Job City Distribution Chart
  const jobCityChart = new Chart(document.getElementById('jobCityChart'), {
    type: 'pie',
    data: {
      labels: <?php echo json_encode(array_keys($topJobLocations)); ?>,
      datasets: [{
        data: <?php echo json_encode(array_values($topJobLocations)); ?>,
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              const value = context.raw;
              const percentage = ((value / <?php echo $totalJobs; ?>) * 100).toFixed(1);
              return `${context.label}: ${value} jobs (${percentage}%)`;
            }
          }
        },
        legend: {
          position: 'right',
          labels: {
            boxWidth: 15,
            padding: 15
          }
        }
      }
    }
  });

  // Age Distribution Chart
  const ageChart = new Chart(document.getElementById('ageChart'), {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($ageRanges); ?>,
      datasets: [{
        label: 'Number of Users',
        data: <?php echo json_encode($ageCounts); ?>,
        backgroundColor: '#4e73df',
        borderColor: '#2e59d9',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const value = context.raw;
              const total = <?php echo array_sum($ageCounts); ?>;
              const percentage = ((value / total) * 100).toFixed(1);
              return `${value} users (${percentage}%)`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of Users'
          },
          ticks: {
            stepSize: 1
          }
        },
        x: {
          title: {
            display: true,
            text: 'Age Range'
          }
        }
      }
    }
  });

  // Hired Users Age Distribution Chart
  const hiredAgeChart = new Chart(document.getElementById('hiredAgeChart'), {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($hiredAgeRanges); ?>,
      datasets: [{
        label: 'Number of Hired Users',
        data: <?php echo json_encode($hiredAgeCounts); ?>,
        backgroundColor: '#1cc88a',
        borderColor: '#13855c',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const value = context.raw;
              const total = <?php echo array_sum($hiredAgeCounts); ?>;
              const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
              return `${value} hired users (${percentage}%)`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of Hired Users'
          },
          ticks: {
            stepSize: 1
          }
        },
        x: {
          title: {
            display: true,
            text: 'Age Range'
          }
        }
      }
    }
  });
</script>
<script src="../dark_mode.js"></script>
</body>
</html>
