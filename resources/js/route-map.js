import L from 'leaflet';

const mapElement = document.getElementById('route-map');
const routePoints = window.routePoints || [];

if (mapElement && routePoints.length > 0) {
    const firstPoint = routePoints[0];

    const map = L.map(mapElement).setView(
        [firstPoint.latitude, firstPoint.longitude],
        13
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    const latLngs = routePoints.map((point) => [
        point.latitude,
        point.longitude,
    ]);

    routePoints.forEach((point, index) => {
        const marker = L.marker([point.latitude, point.longitude]).addTo(map);

        marker.bindPopup(`
            <strong>${index + 1}. ${point.name}</strong><br>
            Visited at: ${point.visited_at}<br>
            Planned: ${point.is_planned ? 'Yes' : 'No'}<br>
            Visited: ${point.is_visited ? 'Yes' : 'No'}<br>
            Speed from previous: ${point.speed_from_previous ?? '-'} km/h
        `);
    });

    const routeLine = L.polyline(latLngs, {
        weight: 4,
    }).addTo(map);

    map.fitBounds(routeLine.getBounds(), {
        padding: [30, 30],
    });
}
