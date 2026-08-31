(function () {
  const BRASIL_CENTER = [-14.235, -51.9253];
  const DEFAULT_ZOOM = 4;
  const LOCATION_ZOOM = 16;

  function onlyDigits(value) {
    return String(value || '').replace(/\D/g, '');
  }

  function formatCep(value) {
    const digits = onlyDigits(value).slice(0, 8);
    return digits.length > 5 ? `${digits.slice(0, 5)}-${digits.slice(5)}` : digits;
  }

  function field(form, name) {
    return form.querySelector(`[name="${name}"]`);
  }

  function setStatus(form, message) {
    const status = form.querySelector('[data-address-status]');
    if (status) {
      status.textContent = message || '';
    }
  }

  function setFields(form, data) {
    const cep = field(form, 'cep');
    const cidade = field(form, 'cidade');
    const bairro = field(form, 'bairro');
    const endereco = field(form, 'endereco');

    if (cep && data.cep) cep.value = formatCep(data.cep);
    if (cidade && data.cidade !== undefined) cidade.value = data.cidade || '';
    if (bairro && data.bairro !== undefined) bairro.value = data.bairro || '';
    if (endereco && data.endereco !== undefined) endereco.value = data.endereco || '';
  }

  function setCoords(form, lat, lng, context) {
    const latitude = field(form, 'latitude');
    const longitude = field(form, 'longitude');

    if (latitude) latitude.value = Number(lat).toFixed(8);
    if (longitude) longitude.value = Number(lng).toFixed(8);

    if (context && context.map && context.marker) {
      const coords = [Number(lat), Number(lng)];
      context.marker.setLatLng(coords);
      context.map.setView(coords, LOCATION_ZOOM);
    }
  }

  function addressFromForm(form, fallbackState) {
    const parts = [
      field(form, 'endereco')?.value,
      field(form, 'bairro')?.value,
      field(form, 'cidade')?.value,
      fallbackState,
      'Brasil',
    ].filter(Boolean);

    return parts.join(', ');
  }

  async function geocodeAddress(form, context, fallbackState) {
    const q = addressFromForm(form, fallbackState);
    if (!q || q === 'Brasil') return;

    const url = new URL('https://nominatim.openstreetmap.org/search');
    url.searchParams.set('format', 'json');
    url.searchParams.set('limit', '1');
    url.searchParams.set('addressdetails', '1');
    url.searchParams.set('accept-language', 'pt-BR');
    url.searchParams.set('q', q);

    const response = await fetch(url.toString());
    if (!response.ok) return;

    const results = await response.json();
    if (results && results[0]) {
      setCoords(form, results[0].lat, results[0].lon, context);
    }
  }

  async function reverseGeocode(form, context, lat, lng) {
    setCoords(form, lat, lng, context);
    setStatus(form, 'Buscando endereco do ponto selecionado...');

    try {
      const url = new URL('https://nominatim.openstreetmap.org/reverse');
      url.searchParams.set('format', 'json');
      url.searchParams.set('lat', lat);
      url.searchParams.set('lon', lng);
      url.searchParams.set('addressdetails', '1');
      url.searchParams.set('accept-language', 'pt-BR');

      const response = await fetch(url.toString());
      if (!response.ok) throw new Error('reverse-geocode-failed');

      const data = await response.json();
      const address = data.address || {};

      setFields(form, {
        cep: address.postcode,
        cidade: address.city || address.town || address.village || address.municipality,
        bairro: address.suburb || address.neighbourhood || address.city_district,
        endereco: address.road || address.pedestrian || address.residential || address.hamlet,
      });
      setStatus(form, 'Endereco carregado pelo mapa.');
    } catch (error) {
      setStatus(form, 'Nao foi possivel carregar o endereco deste ponto.');
    }
  }

  async function buscarCep(form, context) {
    const cepInput = field(form, 'cep');
    const cep = onlyDigits(cepInput?.value);
    if (cep.length !== 8) return;

    setStatus(form, 'Consultando CEP...');

    try {
      const response = await fetch(`https://brasilapi.com.br/api/cep/v1/${cep}`);
      if (!response.ok) throw new Error('cep-not-found');

      const data = await response.json();
      setFields(form, {
        cep: data.cep,
        cidade: data.city,
        bairro: data.neighborhood,
        endereco: data.street,
      });

      setStatus(form, 'CEP encontrado. Ajuste o ponto no mapa se precisar.');
      await geocodeAddress(form, context, data.state);
    } catch (error) {
      setStatus(form, 'CEP nao encontrado.');
    }
  }

  function initMap(form) {
    if (!window.L) return null;

    const mapEl = form.querySelector('[data-address-map]');
    if (!mapEl) return null;
    if (mapEl.dataset.mapReady === '1') return form._addressMapContext || null;
    mapEl.dataset.mapReady = '1';

    const latitude = parseFloat(field(form, 'latitude')?.value);
    const longitude = parseFloat(field(form, 'longitude')?.value);
    const hasCoords = Number.isFinite(latitude) && Number.isFinite(longitude);
    const start = hasCoords ? [latitude, longitude] : BRASIL_CENTER;

    const map = L.map(mapEl).setView(start, hasCoords ? LOCATION_ZOOM : DEFAULT_ZOOM);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap',
    }).addTo(map);

    const marker = L.marker(start, { draggable: true }).addTo(map);
    const context = { map, marker };
    form._addressMapContext = context;

    marker.on('dragend', function () {
      const position = marker.getLatLng();
      reverseGeocode(form, context, position.lat, position.lng);
    });

    map.on('click', function (event) {
      reverseGeocode(form, context, event.latlng.lat, event.latlng.lng);
    });

    const offcanvas = form.closest('.offcanvas');
    if (offcanvas) {
      offcanvas.addEventListener('shown.bs.offcanvas', function () {
        setTimeout(function () {
          map.invalidateSize();
        }, 150);
      });
    }

    return context;
  }

  function initForm(form) {
    if (form.dataset.addressReady === '1') return;
    form.dataset.addressReady = '1';

    const context = initMap(form);
    const cepInput = field(form, 'cep');

    if (cepInput) {
      cepInput.addEventListener('input', function (event) {
        event.target.value = formatCep(event.target.value);
      });

      cepInput.addEventListener('blur', function () {
        buscarCep(form, context);
      });
    }
  }

  window.buscarCEP = function (cep) {
    const form = document.querySelector('[data-address-form]') || document.querySelector('#editOffcanvas form');
    if (!form) return;

    const cepInput = field(form, 'cep');
    if (cepInput) cepInput.value = cep;
    buscarCep(form, initMap(form));
  };

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-address-form]').forEach(initForm);
  });
})();
