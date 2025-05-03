<?php
session_start();
$email = $_SESSION['temp_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Job Seeker Account</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <style>
    body {
      background-image: url('../fortest/images/SPC_wide.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      margin: 0;
      background-color: rgba(0, 0, 0, 0.5);
      background-blend-mode: darken;
    }
    .form-container {
      background: white;
      padding: 2.5rem;
      border-radius: 1rem;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.08);
      max-width: 1100px;
      width: 100%;
    }
    @media (max-width: 768px) {
      .form-container {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2 class="text-center mb-4">Complete Your Account Profile</h2>
  <form action="../includes/employee/emp_reg_process.php" method="POST" enctype="multipart/form-data" id="profileForm">
    <div class="row g-4">

      <!-- Email Preview -->
      <div class="col-12">
        <label for="email" class="form-label">Registering Email:</label>
        <h4 id="emailPreview"><?php echo htmlspecialchars($email); ?></h4>
      </div>

      <div class="col-md-6">
        <label for="firstName" class="form-label">First Name</label>
        <input type="text" class="form-control" id="firstName" name="first_name" required>
      </div>
      <div class="col-md-6">
        <label for="lastName" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="lastName" name="last_name" required>
      </div>

      <div class="col-md-6">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-select" name="gender" id="gender" required>
          <option selected disabled>Select gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="prefer_not_to_say">Prefer not to say</option>
        </select>
      </div>
      <div class="col-md-6">
        <label for="dob" class="form-label">Date of Birth-tba</label>
        <input type="date" class="form-control" id="dob" name="dob">
      </div>
      <div class="col-md-6">
        <label for="age" class="form-label">Age-tba</label>
        <input type="text" class="form-control" id="age" name="age" readonly>
      </div>
      <div class="col-md-6">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="tel" class="form-control" id="phone" name="mobile_number" required pattern="^09\d{9}$" placeholder="e.g. 09XXXXXXXXX">
      </div>

      <div class="col-12">
        <label for="address" class="form-label">Address</label>
        <div class="d-flex align-items-center">
          <textarea class="form-control me-2" id="address" name="address" rows="2" placeholder="Enter your full address..." required></textarea>
          <button type="button" class="btn btn-outline-primary border-0" data-bs-toggle="modal" data-bs-target="#locationModal">
            <i class="fas fa-map-marker-alt" style="color: red; font-size: 1.5rem;"></i>
          </button>
        </div>
      </div>

      <div class="col-md-6">
        <label for="jobCategory" class="form-label">Preferred Job Category-tba</label>
        <select class="form-select" name="job_category" id="jobCategory">
          <option selected disabled>Select a category</option>
          <option>IT & Software</option>
          <option>Marketing</option>
          <option>Customer Service</option>
          <option>Healthcare</option>
          <option>Education</option>
          <option>Engineering</option>
          <option>Others</option>
        </select>
      </div>

      <div class="col-md-6">
        <label for="experience" class="form-label">Years of Experience-tba</label>
        <input type="number" class="form-control" id="experience" name="experience" min="0">
      </div>

      <div class="col-md-6">
        <label for="education" class="form-label">Highest Education Level-tba</label>
        <select class="form-select" name="education" id="education">
          <option selected disabled>Select education level</option>
          <option>High School</option>
          <option>Diploma</option>
          <option>Bachelor's Degree</option>
          <option>Master's Degree</option>
          <option>Doctorate</option>
        </select>
      </div>

      <div class="col-md-6">
        <label for="resume" class="form-label">Upload Resume (PDF)-tba</label>
        <input type="file" class="form-control" id="resume" name="resume" accept=".pdf">
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary w-100">Create Account</button>
      </div>
    </div>
  </form>
</div>

<!-- Location Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pin Your Location</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <input type="text" id="locationSearch" class="form-control" placeholder="Search for a location...">
        </div>
        <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirmLocationBtn" data-bs-dismiss="modal">Use This Location</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
  let map, marker, lat, lng;

  const initMap = () => {
    map = L.map('map').setView([14.5995, 120.9842], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add geocoder control
    L.Control.geocoder({
      defaultMarkGeocode: true,
      geocoder: L.Control.Geocoder.nominatim({
        geocodingQueryParams: {
          countrycodes: 'ph' // ISO 3166-1 alpha-2 country code for the Philippines
        }
      })
    }).addTo(map);

    map.on('click', async (e) => {
      lat = e.latlng.lat;
      lng = e.latlng.lng;

      if (marker) marker.remove();
      marker = L.marker([lat, lng]).addTo(map).bindPopup("Selected location").openPopup();

      const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
      const data = await res.json();
      const address = data.display_name;

      document.getElementById('address').value = address;
      document.getElementById('latitude').value = lat;
      document.getElementById('longitude').value = lng;

      document.getElementById('street').value = data.address.road || '';
      document.getElementById('barangay').value = data.address.suburb || '';
      document.getElementById('city').value = data.address.city || data.address.town || data.address.municipality || '';
      document.getElementById('province').value = data.address.state || '';
      document.getElementById('zip').value = data.address.postcode || '';
      document.getElementById('country').value = data.address.country || '';
    });
  };

  document.getElementById('locationSearch').addEventListener('input', async function () {
    const query = this.value;
    if (query.length < 3) return; // Wait for at least 3 characters

    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}&countrycodes=ph`); // Prioritize PH
    const results = await res.json();

    if (results.length > 0) {
      const { lat, lon, display_name } = results[0];
      map.setView([lat, lon], 15);

      if (marker) marker.remove();
      marker = L.marker([lat, lon]).addTo(map).bindPopup(display_name).openPopup();

      document.getElementById('address').value = display_name;
      document.getElementById('latitude').value = lat;
      document.getElementById('longitude').value = lon;
    }
  });

  const getUserLocation = () => {
    if (!navigator.geolocation) {
      setMapToFallback();
      return;
    }

    navigator.geolocation.getCurrentPosition(
      pos => {
        lat = pos.coords.latitude;
        lng = pos.coords.longitude;
        map.setView([lat, lng], 15);
        if (marker) marker.remove();
        marker = L.marker([lat, lng]).addTo(map).bindPopup("You are here").openPopup();
      },
      () => setMapToFallback()
    );
  };

  const setMapToFallback = () => {
    map.setView([14.5995, 120.9842], 13);
  };

  document.getElementById('locationModal').addEventListener('shown.bs.modal', () => {
    if (!map) initMap();
    setTimeout(() => {
      map.invalidateSize();
      getUserLocation();
    }, 100);
  });

  document.getElementById('dob').addEventListener('change', function () {
    const dob = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
      age--;
    }
    document.getElementById('age').value = isNaN(age) ? '' : age;
  });
</script>

</body>
</html>
