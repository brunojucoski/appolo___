(function () {
  const BRASIL_CENTER = [-14.235, -51.9253];
  const DEFAULT_ZOOM = 4;
  let map = null;

  function escapeHtml(value) {
    return String(value || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function readArtists() {
    const script = document.getElementById('artistas-map-data');
    if (!script) return [];

    try {
      return JSON.parse(script.textContent || '[]').filter(function (artist) {
        return Number.isFinite(Number(artist.latitude)) && Number.isFinite(Number(artist.longitude));
      });
    } catch (error) {
      return [];
    }
  }

  function displayName(artist) {
    return artist.nome_artistico || artist.nome || 'Artista';
  }

  function locationText(artist) {
    return [artist.bairro, artist.cidade].filter(Boolean).join(' - ') || 'Localidade informada no mapa';
  }

  function categoriesText(artist) {
    return Array.isArray(artist.categorias) && artist.categorias.length
      ? artist.categorias.join(', ')
      : 'Categorias não informadas';
  }

  function ratingText(artist) {
    if (!artist.avaliacao_media) return 'Sem avaliações ainda';

    const total = Number(artist.avaliacao_total || 0);
    return `${artist.avaliacao_media} (${total} avaliação${total === 1 ? '' : 's'})`;
  }

  function markerHtml(artist) {
    const name = escapeHtml(displayName(artist));
    const city = escapeHtml(artist.cidade || 'Localidade informada');
    const photo = escapeHtml(artist.foto);

    return `
      <div class="artistas-map-pin" aria-hidden="true"></div>
      <div class="artistas-map-preview">
        <img src="${photo}" alt="">
        <span>
          <strong>${name}</strong>
          <span>${city}</span>
        </span>
      </div>
    `;
  }

  function popupHtml(artist) {
    const name = escapeHtml(displayName(artist));
    const photo = escapeHtml(artist.foto);
    const profile = escapeHtml(artist.perfil_url);
    const categories = escapeHtml(categoriesText(artist));
    const location = escapeHtml(locationText(artist));
    const rating = escapeHtml(ratingText(artist));

    return `
      <div class="artistas-map-popup-card">
        <img src="${photo}" alt="Foto de ${name}">
        <div>
          <h3>${name}</h3>
          <p>${location}<br>${categories}<br>${rating}</p>
          <a href="${profile}">Ver perfil</a>
        </div>
      </div>
    `;
  }

  function initMap() {
    const mapEl = document.getElementById('artistas-map');
    if (!mapEl || map || !window.L) return;

    const artists = readArtists();
    map = L.map(mapEl, {
      scrollWheelZoom: false,
      zoomControl: true,
    }).setView(BRASIL_CENTER, DEFAULT_ZOOM);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap',
    }).addTo(map);

    if (!artists.length) {
      return;
    }

    const bounds = [];

    artists.forEach(function (artist) {
      const coords = [Number(artist.latitude), Number(artist.longitude)];
      bounds.push(coords);

      const icon = L.divIcon({
        className: 'artistas-map-marker',
        html: markerHtml(artist),
        iconSize: [28, 28],
        iconAnchor: [14, 28],
        popupAnchor: [0, -30],
      });

      L.marker(coords, { icon })
        .addTo(map)
        .bindPopup(popupHtml(artist), {
          className: 'artistas-map-popup',
          maxWidth: 280,
        });
    });

    if (bounds.length === 1) {
      map.setView(bounds[0], 13);
    } else {
      map.fitBounds(bounds, {
        maxZoom: 13,
        padding: [42, 42],
      });
    }
  }

  function showMap() {
    const mapView = document.getElementById('artistas-map-view');
    const listView = document.getElementById('artistas-list-view');
    if (!mapView || !listView) return;

    mapView.classList.remove('d-none');
    listView.classList.add('d-none');
    initMap();

    setTimeout(function () {
      if (map) map.invalidateSize();
    }, 80);
  }

  function showCards() {
    const mapView = document.getElementById('artistas-map-view');
    const listView = document.getElementById('artistas-list-view');
    if (!mapView || !listView) return;

    mapView.classList.add('d-none');
    listView.classList.remove('d-none');
  }

  document.addEventListener('DOMContentLoaded', function () {
    const cardsToggle = document.getElementById('visualizarCards');
    const mapToggle = document.getElementById('visualizarMapa');

    if (cardsToggle) {
      cardsToggle.addEventListener('change', function () {
        if (cardsToggle.checked) showCards();
      });
    }

    if (mapToggle) {
      mapToggle.addEventListener('change', function () {
        if (mapToggle.checked) showMap();
      });
    }
  });
})();
