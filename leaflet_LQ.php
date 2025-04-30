<!-- Bootstrap & Leaflet CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<!-- Trigger Button -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addressModal">
  Set Address
</button>

<!-- Display Saved Address -->
<div class="mt-3">
  <label for="savedAddress" class="form-label">Selected Address:</label>
  <input type="text" id="savedAddress" name="savedAddress" class="form-control" readonly>
</div>

<!-- Display Latitude and Longitude -->
<div class="mt-3">
  <label for="latitudeDisplay" class="form-label">Latitude:</label>
  <input type="text" id="latitudeDisplay" class="form-control" readonly>
</div>
<div class="mt-3">
  <label for="longitudeDisplay" class="form-label">Longitude:</label>
  <input type="text" id="longitudeDisplay" class="form-control" readonly>
</div>

<!-- Address Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Address</h5>
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
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Save Address</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap & Leaflet JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
  let map, marker;

  // 🔑 Add your LocationIQ Access Token here:
  const LOCATIONIQ_API_KEY = 'pk.4e83ac2e2cbae8c63516e077f753cf2f'; // <<< Replace this with your real key

  document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById('addressModal');

    modal.addEventListener('shown.bs.modal', () => {
      if (!map) {
        map = L.map('map').setView([13.41, 122.56], 6); // Center to Philippines

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19
        }).addTo(map);

        marker = L.marker([13.41, 122.56], { draggable: true }).addTo(map);

        marker.on('dragend', () => {
          const position = marker.getLatLng();
          document.getElementById('latitude').value = position.lat;
          document.getElementById('longitude').value = position.lng;

          // Update displayed latitude and longitude
          document.getElementById('latitudeDisplay').value = position.lat;
          document.getElementById('longitudeDisplay').value = position.lng;

          reverseGeocode(position.lat, position.lng);
        });
      }

      setTimeout(() => map.invalidateSize(), 200);
    });

    // 🔄 Reverse Geocoding (Dragging Marker)
    function reverseGeocode(lat, lon) {
      fetch(`https://us1.locationiq.com/v1/reverse.php?key=${LOCATIONIQ_API_KEY}&lat=${lat}&lon=${lon}&format=json`)
        .then(res => res.json())
        .then(data => {
          const address = data.display_name;
          document.getElementById('addressInput').value = address;
        })
        .catch(error => console.error('Error reverse geocoding:', error));
    }

    // 🔎 Autocomplete (Searching Address)
    const input = document.getElementById('addressInput');
    const suggestions = document.getElementById('suggestions');

    input.addEventListener('input', () => {
      const query = input.value;
      if (query.length < 3) {
        suggestions.innerHTML = '';
        return;
      }

      const apiUrl = `https://us1.locationiq.com/v1/autocomplete.php?key=${LOCATIONIQ_API_KEY}&q=${encodeURIComponent(query)}&limit=5&countrycodes=ph`;

      fetch(apiUrl)
        .then(res => res.json())
        .then(data => {
          suggestions.innerHTML = '';
          data.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item.display_place || item.display_name;
            li.classList.add('list-group-item', 'list-group-item-action');
            li.style.cursor = 'pointer';

            li.onclick = () => {
              input.value = item.display_name;
              suggestions.innerHTML = '';

              const lat = parseFloat(item.lat);
              const lon = parseFloat(item.lon);

              document.getElementById('latitude').value = lat;
              document.getElementById('longitude').value = lon;

              marker.setLatLng([lat, lon]);
              map.setView([lat, lon], 15);
            };

            suggestions.appendChild(li);
          });
        });
    });

    // ✅ Save to external input
    const saveButton = document.querySelector('#addressModal .btn-success');
    const savedAddressInput = document.getElementById('savedAddress');

    saveButton.addEventListener('click', () => {
      const address = document.getElementById('addressInput').value;
      savedAddressInput.value = address;
    });
  });
</script>

<style>
  #suggestions {
    max-height: 200px;
    overflow-y: auto;
    background-color: white;
    border: 1px solid #ced4da;
    border-top: none;
    z-index: 1055;
  }

  #suggestions li:hover {
    background-color: #f8f9fa;
  }
</style>
