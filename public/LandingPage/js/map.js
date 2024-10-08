// Access the global variable defined in the Blade template
var latitude = window.storeLocation.latitude;
var longitude = window.storeLocation.longitude;
var nama = window.storeLocation.nama;

// Initialize the Leaflet map
var map = L.map('map').setView([latitude, longitude], 13);
console.log(latitude, longitude, map);

// Add tile layer from OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Generate Google Maps direction link
var gmapsLink = `https://www.google.com/maps/dir/?api=1&destination=${latitude},${longitude}`;

// Add a marker for the store location with a popup
L.marker([latitude, longitude]).addTo(map)
    .bindPopup(`
        <b>${nama}</b><br>
        <a href="${gmapsLink}" target="_blank">Arahkan ke sini dengan Google Maps</a>
    `)
    .openPopup();