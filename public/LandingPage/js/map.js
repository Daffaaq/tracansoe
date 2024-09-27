// Initialize the Leaflet map
var map = L.map('map').setView([-9.0837414, 124.89648], 13); // Coordinates of Atambua

// Add tile layer from OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Add a marker for the store location in Atambua
L.marker([-9.108398, 124.892494]).addTo(map)
    .bindPopup('Cuci Sepatu Modern')
    .openPopup();