<!-- Bootstrap & Leaflet CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<!-- Trigger Button -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addressModal">
  Set Address
</button>

<!-- Display Saved Addresses -->
<div class="mt-3">
  <label for="savedAddress" class="form-label">Primary Address:</label>
  <input type="text" id="savedAddress" name="savedAddress" class="form-control" readonly>
</div>
<div class="mt-3">
  <label for="secondaryAddress" class="form-label">Secondary Address:</label>
  <input type="text" id="secondaryAddress" name="secondaryAddress" class="form-control" readonly>
</div>
<div class="mt-3">
  <label for="street" class="form-label">Street:</label>
  <input type="text" id="street" name="street" class="form-control" readonly>
</div>
<div class="mt-3">
  <label for="zipCode" class="form-label">Zip Code:</label>
  <input type="text" id="zipCode" name="zipCode" class="form-control" readonly>
</div>
<div class="mt-3">
  <label for="city" class="form-label">City:</label>
  <input type="text" id="city" name="city" class="form-control" readonly>
</div>
<div class="mt-3">
  <label for="province" class="form-label">Province:</label>
  <input type="text" id="province" name="province" class="form-control" readonly>
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

  document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById('addressModal');

    modal.addEventListener('shown.bs.modal', () => {
      if (!map) {
        map = L.map('map').setView([13.41, 122.56], 6); // Philippines center

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19
        }).addTo(map);

        marker = L.marker([13.41, 122.56], { draggable: true }).addTo(map);

        marker.on('dragend', () => {
          const position = marker.getLatLng();
          document.getElementById('latitude').value = position.lat;
          document.getElementById('longitude').value = position.lng;
          reverseGeocode(position.lat, position.lng);
        });
      }

      setTimeout(() => map.invalidateSize(), 200);
    });

    // Reverse Geocoding
    function reverseGeocode(lat, lon) {
      fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`)
        .then(res => res.json())
        .then(data => {
          const address = data.display_name;
          document.getElementById('addressInput').value = address;
        });
    }

    // Autocomplete Search
    const input = document.getElementById('addressInput');
    const suggestions = document.getElementById('suggestions');

    input.addEventListener('input', () => {
      const query = input.value;
      if (query.length < 3) {
        suggestions.innerHTML = '';
        return;
      }

      const apiUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=5&countrycodes=ph&viewbox=116.87,21.32,127.81,4.63&bounded=1`;

      fetch(apiUrl)
        .then(res => res.json())
        .then(data => {
          suggestions.innerHTML = '';
          data.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item.display_name;
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

    // Save to external inputs
    const saveButton = document.querySelector('#addressModal .btn-success');
    const savedAddressInput = document.getElementById('savedAddress');
    const secondaryAddressInput = document.getElementById('secondaryAddress');
    const streetInput = document.getElementById('street');
    const zipCodeInput = document.getElementById('zipCode');
    const cityInput = document.getElementById('city');
    const provinceInput = document.getElementById('province');

    saveButton.addEventListener('click', () => {
      let address = document.getElementById('addressInput').value;

      // Remove "2nd District" or "District" from the address
      address = address.replace(/\b(2nd District|District)\b/g, '').trim();

      const [primary, ...secondary] = address.split(','); // Split address into parts
      savedAddressInput.value = primary.trim();
      secondaryAddressInput.value = secondary.join(',').trim();

      // Extract specific parts of the address
      const addressParts = address.split(',');
      streetInput.value = addressParts[0]?.trim() || '';
      cityInput.value = addressParts[1]?.trim() || '';
      provinceInput.value = addressParts[2]?.trim() || '';
      zipCodeInput.value = addressParts[3]?.trim() || '';
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
