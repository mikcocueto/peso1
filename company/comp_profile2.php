<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Company Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light text-dark">

<div class="container my-5">
  <!-- Header Section -->
  <div class="p-4 bg-white rounded shadow-sm d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Company Logo" width="70" class="me-3">
      <div>
        <h4 class="mb-0">Google Inc.</h4>
        <small class="text-muted">Mountain View, CA • 10,000+ employees • Public Company</small><br>
        <small>Founded: 1998 • <a href="http://www.google.com" target="_blank">www.google.com</a></small>
        <p class="mb-0"><small>1600 Amphitheatre Parkway, CA • +1 650-253-0000</small></p>
      </div>
    </div>
    <div>
      <a class="btn btn-success me-2" href="#">See All Jobs</a>
      <a class="btn btn-primary" href="#">Edit</a>
    </div>
  </div>

  <!-- Content Row -->
  <div class="row mt-4 g-4">
    <!-- About Us -->
    <div class="col-lg-8">
      <div class="bg-white p-4 rounded shadow-sm">
        <h5>About Us</h5>
        <p>
          Google’s mission is to organize the world’s information and make it universally accessible and useful.
          Since 1998, Google has grown rapidly, offering a variety of services including ads, search, cloud,
          mobile OS, video, and much more.
        </p>
        <p><strong>Specialties:</strong> search, ads, mobile, android, online, video, apps, machine learning, virtual reality</p>
      </div>

      <!-- Affiliated Companies -->
      <div class="bg-white p-4 rounded shadow-sm mt-4">
        <h5>Affiliated Companies</h5>
        <div class="row row-cols-2 row-cols-md-3 g-3">
          <div class="col"><div class="border rounded p-2 text-center">Google Marketing Tools</div></div>
          <div class="col"><div class="border rounded p-2 text-center">Adometry</div></div>
          <div class="col"><div class="border rounded p-2 text-center">Nest</div></div>
          <div class="col"><div class="border rounded p-2 text-center">YouTube</div></div>
          <div class="col"><div class="border rounded p-2 text-center">The X Factory</div></div>
        </div>
      </div>

      <!-- Supported Orgs -->
      <div class="bg-white p-4 rounded shadow-sm mt-4">
        <h5>Organizations We Support</h5>
        <div class="row row-cols-2 row-cols-md-3 g-3">
          <div class="col"><div class="border rounded p-2 text-center">UNICEF</div></div>
          <div class="col"><div class="border rounded p-2 text-center">Amnesty Intl.</div></div>
          <div class="col"><div class="border rounded p-2 text-center">Red Cross</div></div>
          <div class="col"><div class="border rounded p-2 text-center">EFF</div></div>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="col-lg-4">
      <div class="bg-white p-4 rounded shadow-sm mb-4">
        <h6>Languages We Speak</h6>
        <canvas id="langChart" height="200"></canvas>
      </div>
      <div class="bg-white p-4 rounded shadow-sm">
        <h6>Causes We Support</h6>
        <canvas id="causeChart" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Chart Script -->
<script>
  const langChart = new Chart(document.getElementById('langChart'), {
    type: 'doughnut',
    data: {
      labels: ['English', 'Spanish', 'German', 'Chinese', 'French'],
      datasets: [{
        data: [60, 15, 10, 9, 6],
        backgroundColor: ['#4285F4', '#EA4335', '#FBBC05', '#34A853', '#A142F4']
      }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
  });

  const causeChart = new Chart(document.getElementById('causeChart'), {
    type: 'doughnut',
    data: {
      labels: ['Education', 'Human Rights', 'Children', 'Science & Tech', 'Environment'],
      datasets: [{
        data: [40, 25, 15, 10, 10],
        backgroundColor: ['#0D6EFD', '#DC3545', '#FFC107', '#20C997', '#6F42C1']
      }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
  });
</script>

</body>
</html>
