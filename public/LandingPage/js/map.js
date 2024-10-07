// Access the global variable defined in the Blade template
var latitude = window.storeLocation.latitude;
var longitude = window.storeLocation.longitude;

// Initialize the Leaflet map
var map = L.map('map').setView([latitude, longitude], 13);
console.log(latitude, longitude, map);

// Add tile layer from OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Add a marker for the store location in Atambua
L.marker([latitude, longitude]).addTo(map)
    .bindPopup('Cuci Sepatu Modern')
    .openPopup();
