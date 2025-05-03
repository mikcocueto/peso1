<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>San Pablo Leaflet Map</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        #map {
            height: 1000px;
            width: 100%;
        }

        .header {
            padding: 10px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            font-size: 24px;
        }
    </style>
</head>
<body>

    <div class="header">Map of San Pablo City</div>
    <div id="map"></div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // San Pablo City coordinates: approx. 14.0691° N, 121.3253° E
        var map = L.map('map').setView([14.0691, 121.3253], 13);

        // OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add a marker in San Pablo
        L.marker([14.0691, 121.3253]).addTo(map)
            .bindPopup('Hello from San Pablo City!')
            .openPopup();
    </script>

</body>
</html>