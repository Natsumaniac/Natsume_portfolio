(function () {
  const mapNode = document.getElementById('contact-lupon-map');

  if (!mapNode || typeof L === 'undefined') return;

  const lat = Number(mapNode.dataset.lat || 6.89814);
  const lng = Number(mapNode.dataset.lng || 126.00961);
  const label = mapNode.dataset.label || 'Lupon, Davao Oriental, Philippines';

  const map = L.map(mapNode, {
    center: [lat, lng],
    zoom: 11,
    zoomControl: false,
    attributionControl: false,
    scrollWheelZoom: false,
    dragging: true,
    doubleClickZoom: false,
    boxZoom: false,
    keyboard: false,
    tap: false
  });

  L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    subdomains: 'abcd',
    maxZoom: 19
  }).addTo(map);

  const pin = L.divIcon({
    className: 'contact-map-pin',
    html: '<span class="contact-map-pin__pulse"></span><span class="contact-map-pin__core"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>',
    iconSize: [58, 58],
    iconAnchor: [29, 42]
  });

  L.marker([lat, lng], {
    icon: pin,
    title: label,
    keyboard: false
  }).addTo(map);

  window.setTimeout(() => {
    map.flyTo([lat, lng], 12, {
      animate: true,
      duration: 2.6,
      easeLinearity: 0.18
    });
  }, 450);

  window.addEventListener('resize', () => {
    window.setTimeout(() => map.invalidateSize(), 120);
  }, { passive: true });

  window.setTimeout(() => map.invalidateSize(), 300);
})();
