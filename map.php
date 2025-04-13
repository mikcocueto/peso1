<?php
$lat = 14.0694;
$lng = 121.3253;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Mapbox with Search</title>
  <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">

  <!-- Mapbox CSS -->
  <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
  <!-- Geocoder CSS -->
  <link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.1.2/mapbox-gl-geocoder.css" rel="stylesheet" />
  <style>
    body { margin: 0; padding: 0; }
    #map { position: absolute; top: 0; bottom: 0; width: 100%; }
    .geocoder {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 1;
      width: 50%;
      max-width: 400px;
    }
  </style>
</head>
<body>

<!-- Search bar container -->
<div class="geocoder" id="geocoder"></div>

<!-- Map container -->
<div id="map"></div>

<!-- Mapbox JS -->
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
<!-- Geocoder JS -->
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.1.2/mapbox-gl-geocoder.min.js"></script>

<script>
  mapboxgl.accessToken = 'pk.eyJ1IjoiZGVsbDA2IiwiYSI6ImNtOWZxbzJhajFvaGUycm9teGMxM3prdWoifQ.Z0EuGCyyTbSGUyRtF_vBOw'; // replace this with your actual token

  const lat = <?php echo $lat; ?>;
  const lng = <?php echo $lng; ?>;

  const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [lng, lat],
    zoom: 13
  });

  // 🔵 Add default marker (San Pablo City)
  const defaultMarker = new mapboxgl.Marker()
    .setLngLat([lng, lat])
    .addTo(map);

  // 🔍 Add geocoder (search bar)
  const geocoder = new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    mapboxgl: mapboxgl,
    placeholder: 'Search for places...',
    marker: false // don’t add a new marker automatically
  });

  // Attach geocoder to div
  document.getElementById('geocoder').appendChild(geocoder.onAdd(map));

  // Add a marker manually when a search result is selected
  let searchMarker;

  geocoder.on('result', function(e) {
    const coords = e.result.center;

    // Remove existing search marker
    if (searchMarker) {
      searchMarker.remove();
    }

    // Add new marker
    searchMarker = new mapboxgl.Marker({ color: 'red' })
      .setLngLat(coords)
      .addTo(map);

    // Optionally: fly to location
    map.flyTo({ center: coords, zoom: 14 });
  });
</script>

</body>
</html>