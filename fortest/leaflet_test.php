<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet Map with User Address</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        /* Modern modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .close {
            color: #333;
            font-size: 24px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
        }
        .close:hover {
            color: #ff0000;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 0;
        }
        button:hover {
            background-color: #0056b3;
        }
        input[type="text"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        #modalMap {
            height: 300px;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body>

    <!-- Map container -->
    <div id="map" style="height: 500px; margin: 20px auto; width: 90%; border-radius: 10px; overflow: hidden;"></div>

    <h2 style="text-align: center; font-family: Arial, sans-serif;">Leaflet Map with User Address</h2>

    <!-- Address input -->
    <div style="text-align: center;">
        <input type="text" id="address" placeholder="Enter an address" />
        <button onclick="addUserAddress()">Add Address</button>
    </div>

    <!-- Button to open modal -->
    <div style="text-align: center;">
        <button id="openModal">Open Modal</button>
    </div>

    <!-- Modal structure -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
           
            <div id="modalMap"></div>
            <h2 style="font-family: Arial, sans-serif;">Leaflet Map in Modal</h2>
            <p style="font-family: Arial, sans-serif;">Enter an address to display it on the map below:</p>
            
            <!-- Additional input fields -->
            <input type="text" id="streetAddress" placeholder="Street Address (e.g., '123 Main Street')" />
            <input type="text" id="addressLine2" placeholder="Address Line 2 (optional, e.g., Apartment/Suite number)" />
            <input type="text" id="city" placeholder="City" />
            <input type="text" id="state" placeholder="State/Province/Region" />
            <button id="addModalAddressBtn">Add Address in Modal</button>
        </div>
    </div>

    <script>
        var map = L.map('map').setView([51.505, -0.09], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Geolocation feature
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                map.setView([lat, lon], 13);
                L.marker([lat, lon]).addTo(map).bindPopup("You are here").openPopup();
            }, function() {
                alert("Geolocation failed.");
            });
        }

        // Geocode address using Nominatim API
        function geocodeAddress(address, targetMap) {
            var url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var lat = data[0].lat;
                        var lon = data[0].lon;
                        targetMap.setView([lat, lon], 13);
                        L.marker([lat, lon]).addTo(targetMap).bindPopup(`<b>${address}</b>`).openPopup();
                    } else {
                        alert("Address not found.");
                    }
                })
                .catch(error => {
                    console.error("Error fetching geocoding data", error);
                });
        }

        // Trigger geocoding when the user inputs an address
        function addUserAddress() {
            var address = document.getElementById("address").value;
            if (address) {
                geocodeAddress(address, map);
            } else {
                alert("Please enter an address.");
            }
        }

        // Modal functionality
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("openModal");
        var span = document.getElementsByClassName("close")[0];
        var modalMap;
        var modalMapInitialized = false;

        btn.onclick = function() {
            modal.style.display = "flex";

            // Initialize a Leaflet map inside the modal only once
            if (!modalMapInitialized) {
                setTimeout(() => {
                    modalMap = L.map('modalMap').setView([51.505, -0.09], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(modalMap);
                }, 100);
                modalMapInitialized = true;
            }
        };

        // Trigger geocoding for the combined address entered in the modal
        document.getElementById("addModalAddressBtn").onclick = function() {
            var streetAddress = document.getElementById("streetAddress").value;
            var addressLine2 = document.getElementById("addressLine2").value;
            var city = document.getElementById("city").value;
            var state = document.getElementById("state").value;

            // Combine all inputs into a single address string
            var combinedAddress = `${streetAddress}, ${addressLine2}, ${city}, ${state}`.replace(/,\s*$/, "").trim();

            if (combinedAddress) {
                if (modalMapInitialized) {
                    geocodeAddress(combinedAddress, modalMap);
                } else {
                    alert("Map is not initialized yet. Please try again.");
                }
            } else {
                alert("Please fill in at least the Street Address and City.");
            }
        };

        span.onclick = function() {
            modal.style.display = "none";
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>

</body>
</html>