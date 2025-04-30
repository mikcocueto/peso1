<?php
// Fetch saved locations from the database
$conn = new mysqli("localhost", "root", "", "pesodb");
$savedLocations = $conn->query("SELECT e.id, e.location, c.latitude, c.longitude FROM tbl_0_test_environment e JOIN tbl_job_coordinates c ON e.id = c.job_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Location Test</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
  <style>
    .modal-body {
      position: relative;
    }
    #map {
      border: 1px solid #ced4da;
    }
    .card {
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }
    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #004085;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container py-5">
    <h1 class="mb-4 text-center">Location Test</h1>

    <!-- Button to open the location selection modal -->
    <div class="text-center mb-4">
      <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#locationModal">
        Select Location
      </button>
    </div>

    <!-- Display saved locations -->
    <div class="mt-4">
      <h5 class="text-center mb-4">Saved Locations</h5>
      <div class="row">
        <?php while ($row = $savedLocations->fetch_assoc()): ?>
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-body">
                <h6 class="card-title"><?php echo htmlspecialchars($row['location']); ?></h6>
                <p class="card-text">Latitude: <?php echo $row['latitude']; ?>, Longitude: <?php echo $row['longitude']; ?></p>
                <div id="map-<?php echo $row['id']; ?>" style="height: 200px;"></div>
              </div>
            </div>
          </div>
          <script>
            document.addEventListener("DOMContentLoaded", () => {
              const map = L.map("map-<?php echo $row['id']; ?>").setView([<?php echo $row['latitude']; ?>, <?php echo $row['longitude']; ?>], 15);
              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
              L.marker([<?php echo $row['latitude']; ?>, <?php echo $row['longitude']; ?>]).addTo(map);
            });
          </script>
        <?php endwhile; ?>
      </div>
    </div>

    <!-- Location selection modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Select Location</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Manual Search -->
            <div class="mb-3 position-relative" style="z-index: 1055;">
              <input type="text" id="addressInput" class="form-control" placeholder="Search address...">
              <ul id="suggestions" class="list-group position-absolute w-100 mt-1 shadow"></ul>
            </div>

            <!-- Map -->
            <div id="map" style="height: 350px; border-radius: 0.5rem;"></div>

            <!-- Hidden Inputs -->
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
          </div>
          <div class="modal-footer">
            <form action="location_test_process.php" method="POST">
              <input type="hidden" name="location" id="location">
              <input type="hidden" name="latitude" id="latitudeForm">
              <input type="hidden" name="longitude" id="longitudeForm">
              <button type="submit" class="btn btn-success">Save Location</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
  let map, marker;

  document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById('locationModal');
    const addressInput = document.getElementById('addressInput');
    const locationInput = document.getElementById('location');
    const suggestions = document.getElementById('suggestions');
    const latitudeInput = document.getElementById('latitudeForm');
    const longitudeInput = document.getElementById('longitudeForm');

    const LOCATIONIQ_API_KEY = 'pk.4e83ac2e2cbae8c63516e077f753cf2f'; // Replace with your real key

    modal.addEventListener('shown.bs.modal', () => {
      if (!map) {
        map = L.map('map').setView([13.41, 122.56], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        marker = L.marker([13.41, 122.56], { draggable: true }).addTo(map);

        marker.on('dragend', () => {
          const position = marker.getLatLng();
          document.getElementById('latitude').value = position.lat;
          document.getElementById('longitude').value = position.lng;
          latitudeInput.value = position.lat;
          longitudeInput.value = position.lng;

          // Reverse geocode to get the location name
          reverseGeocode(position.lat, position.lng);
        });
      }
      setTimeout(() => map.invalidateSize(), 200);
    });

    addressInput.addEventListener('input', () => {
      const query = addressInput.value;
      locationInput.value = query; // Synchronize with location input
      if (query.length < 3) {
        suggestions.innerHTML = '';
        return;
      }

      fetch(`https://us1.locationiq.com/v1/autocomplete.php?key=${LOCATIONIQ_API_KEY}&q=${encodeURIComponent(query)}&limit=5&countrycodes=ph`)
        .then(res => res.json())
        .then(data => {
          suggestions.innerHTML = '';
          data.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item.display_place || item.display_name;
            li.classList.add('list-group-item', 'list-group-item-action');
            li.style.cursor = 'pointer';

            li.onclick = () => {
              addressInput.value = item.display_name;
              locationInput.value = item.display_name; // Synchronize with location input
              suggestions.innerHTML = '';

              const lat = parseFloat(item.lat);
              const lon = parseFloat(item.lon);

              document.getElementById('latitude').value = lat;
              document.getElementById('longitude').value = lon;
              latitudeInput.value = lat;
              longitudeInput.value = lon;

              marker.setLatLng([lat, lon]);
              map.setView([lat, lon], 15);
            };

            suggestions.appendChild(li);
          });
        });
    });

    function reverseGeocode(lat, lon) {
      fetch(`https://us1.locationiq.com/v1/reverse.php?key=${LOCATIONIQ_API_KEY}&lat=${lat}&lon=${lon}&format=json`)
        .then(res => res.json())
        .then(data => {
          const address = data.display_name;
          addressInput.value = address;
          locationInput.value = address; // Synchronize with location input
        })
        .catch(error => console.error('Error reverse geocoding:', error));
    }
  });
  </script>
</body>
</html>