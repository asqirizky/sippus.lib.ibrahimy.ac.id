<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="mobile-web-app-capable" content="yes" />
  <title>Absensi Pustakawan Struktural - Perpustakaan Ibrahimy</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * {
      -webkit-tap-highlight-color: transparent;
    }

    html, body {
      height: 100%;
      overflow: hidden;
    }

    body {
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px) scale(0.95); }
      to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes pulseGlow {
      0%   { box-shadow: 0 0 0px rgba(59,130,246,0.0); }
      50%  { box-shadow: 0 0 12px rgba(59,130,246,0.5); }
      100% { box-shadow: 0 0 0px rgba(59,130,246,0.0); }
    }

    .animate-card {
      animation: fadeInUp 0.8s ease;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.375rem;
      padding: 0.35rem 0.85rem;
      border-radius: 9999px;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .status-badge .dot {
      width: 0.5rem;
      height: 0.5rem;
      border-radius: 9999px;
    }

    @media (max-width: 640px) {
      .status-badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.7rem;
      }
    }
  </style>
</head>

<body class="relative flex items-center justify-center min-h-screen" style="background: #22a953;">

  <!-- Form Card -->
  <div class="relative z-10 w-full max-w-md p-5 sm:p-6 mx-3 text-center shadow-2xl bg-white/10 backdrop-blur-md rounded-2xl animate-card" style="max-height: 98vh; overflow-y: auto;">

    <!-- Logo -->
    <h1 class="flex items-center justify-center gap-2 mb-3 text-2xl sm:text-3xl font-bold text-white">
      <span class="px-2 py-1 text-blue-700 bg-white rounded text-xl sm:text-2xl">lib</span>
      <span>Ibrahimy</span>
    </h1>

    <!-- Status Geofencing -->
    <div id="geo-status" class="mb-2">
      <span class="status-badge bg-gray-500/50 text-white">
        <span class="dot bg-gray-300"></span>
        Memeriksa lokasi...
      </span>
    </div>
    <div class="flex justify-center gap-2 mb-2">
      <button type="button" id="btn-cek-lokasi"
        class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition active:scale-95 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
        Cek Lokasi
      </button>
      <button type="button" id="btn-retry-geo"
        class="hidden px-3 py-2 text-xs font-semibold text-white bg-orange-500 rounded-xl hover:bg-orange-600 transition active:scale-95"
        onclick="retryGeolocation()">
        Coba lagi GPS
      </button>
    </div>
    <!-- Manual coordinate input (hidden, shown as last resort) -->
    <div id="manual-geo" class="hidden mb-3 space-y-2">
      <input type="text" id="manual-lat" placeholder="Latitude (cth: -7.7510)"
        class="w-full px-3 py-2.5 text-sm text-center rounded-lg bg-white/90 focus:outline-none">
      <input type="text" id="manual-lng" placeholder="Longitude (cth: 114.2737)"
        class="w-full px-3 py-2.5 text-sm text-center rounded-lg bg-white/90 focus:outline-none">
      <button type="button" id="btn-use-manual"
        class="w-full px-4 py-2.5 text-sm font-semibold text-blue-700 bg-white rounded-lg hover:bg-blue-100 transition active:scale-95">
        Gunakan Koordinat Manual
      </button>
    </div>

    <!-- Form -->
    <form class="form" method="POST" action="{{ route('struktural-proses') }}" id="absen-form">
      @csrf
      <input type="hidden" name="latitude" id="latitude-input" />
      <input type="hidden" name="longitude" id="longitude-input" />
      <div>
        <input type="text" name="nik" inputmode="numeric" pattern="[0-9]*" placeholder="Masukkan NIK" required
          class="w-full px-4 py-3 text-base text-center rounded-xl bg-white/90 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:animate-[pulseGlow_0.6s_ease]" autofocus/>
      </div>

      <div class="flex justify-center mt-3">
        <button type="submit" id="btn-submit"
          class="w-full sm:w-auto px-8 py-3 text-base font-semibold text-blue-700 transition duration-300 bg-white rounded-xl hover:bg-blue-100 hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
          disabled>
          Submit
        </button>
      </div>
    </form>

    <!-- Footer -->
    <p class="mt-4 text-xs text-white/80">
      &copy;2026 AsqiRizky-Librarian Developer
    </p>
  </div>

  <!-- SweetAlert Notification -->
  @if (session('success'))
    <script>
      Swal.fire({
        title: 'Alhamdulillah!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#2563eb'
      });
    </script>
  @endif

  @if (session('error'))
    <script>
      Swal.fire({
        title: 'Astaghfirullah!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc2626'
      });
    </script>
  @endif

  <script>
    const GEO_SETTINGS = @json($settings);

    let geoVerified = false;

    const geoStatus = document.getElementById('geo-status');
    const latInput = document.getElementById('latitude-input');
    const lngInput = document.getElementById('longitude-input');
    const btnSubmit = document.getElementById('btn-submit');

    function updateSubmitButton() {
      btnSubmit.disabled = !geoVerified;
    }

    // ========== GEOLOCATION ==========
    function calculateDistance(lat1, lon1, lat2, lon2) {
      const R = 6371000;
      const dLat = (lat2 - lat1) * Math.PI / 180;
      const dLon = (lon2 - lon1) * Math.PI / 180;
      const a = Math.sin(dLat / 2) ** 2 +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon / 2) ** 2;
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      return R * c;
    }

    function checkGeofence(userLat, userLng) {
      let nearest = { ok: false, dist: null, radius: null };
      for (const s of GEO_SETTINGS) {
        if (s.latitude && s.longitude && s.radius) {
          const dist = calculateDistance(parseFloat(s.latitude), parseFloat(s.longitude), userLat, userLng);
          if (dist <= parseFloat(s.radius)) {
            return { ok: true, dist: Math.round(dist), radius: parseFloat(s.radius) };
          }
          if (nearest.dist === null || dist < nearest.dist) {
            nearest = { ok: false, dist: Math.round(dist), radius: parseFloat(s.radius) };
          }
        }
      }
      return nearest;
    }

    function setGeoStatus(text, color, dotColor) {
      geoStatus.innerHTML =
        `<span class="status-badge" style="background-color: ${color}20; color: ${color}; border: 1px solid ${color}40">
          <span class="dot" style="background-color: ${dotColor}"></span>
          ${text}
        </span>`;
    }

    const btnRetryGeo = document.getElementById('btn-retry-geo');
    const manualGeo = document.getElementById('manual-geo');
    const manualLat = document.getElementById('manual-lat');
    const manualLng = document.getElementById('manual-lng');
    const btnUseManual = document.getElementById('btn-use-manual');

    function onLocationReceived(userLat, userLng) {
      const btnCek = document.getElementById('btn-cek-lokasi');
      btnCek.disabled = false;
      btnCek.textContent = 'Cek Lokasi';
      btnRetryGeo.classList.add('hidden');
      manualGeo.classList.add('hidden');
      latInput.value = userLat;
      lngInput.value = userLng;

      const result = checkGeofence(userLat, userLng);
      if (result.ok) {
        geoVerified = true;
        setGeoStatus('Lokasi sesuai (' + result.dist + ' m)', '#22c55e', '#22c55e');
      } else {
        geoVerified = false;
        const msg = result.dist !== null
          ? 'Luar area (jarak ' + result.dist + ' m)'
          : 'Luar area (tidak ada setting geofencing)';
        setGeoStatus(msg, '#ef4444', '#ef4444');
        btnRetryGeo.classList.remove('hidden');
      }
      updateSubmitButton();
    }

    function resetCekLokasiBtn() {
      const btn = document.getElementById('btn-cek-lokasi');
      btn.disabled = false;
      btn.textContent = 'Cek Lokasi';
    }

    function tryBrowserGeo(highAccuracy) {
      if (!navigator.geolocation) {
        resetCekLokasiBtn();
        return false;
      }
      setGeoStatus('Memeriksa lokasi...', '#94a3b8', '#94a3b8');
      navigator.geolocation.getCurrentPosition(
        function (pos) {
          onLocationReceived(pos.coords.latitude, pos.coords.longitude);
        },
        function (err) {
          if (err && err.code === 1) {
            setGeoStatus('Akses lokasi ditolak', '#ef4444', '#ef4444');
            btnRetryGeo.classList.remove('hidden');
            resetCekLokasiBtn();
            return;
          }
          if (highAccuracy) {
            tryBrowserGeo(false);
          } else {
            tryIPGeo();
          }
        },
        { enableHighAccuracy: highAccuracy, timeout: 30000 }
      );
      return true;
    }

    function tryIPGeo() {
      setGeoStatus('Lokasi via IP (mungkin tidak akurat)...', '#f59e0b', '#f59e0b');
      fetch('https://ipapi.co/json/')
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (data.latitude && data.longitude) {
            setGeoStatus('Lokasi via IP - klik "Coba lagi" untuk GPS', '#f59e0b', '#f59e0b');
            onLocationReceived(data.latitude, data.longitude);
          } else {
            showManualInput();
          }
        })
        .catch(function () {
          showManualInput();
        });
    }

    function showManualInput() {
      resetCekLokasiBtn();
      setGeoStatus('Masukkan koordinat manual', '#f59e0b', '#f59e0b');
      manualGeo.classList.remove('hidden');
    }

    function retryGeolocation() {
      btnRetryGeo.classList.add('hidden');
      manualGeo.classList.add('hidden');
      tryBrowserGeo(true);
    }

    function checkLocation() {
      const btn = document.getElementById('btn-cek-lokasi');
      btn.disabled = true;
      btn.textContent = 'Mengecek...';
      geoVerified = false;
      updateSubmitButton();
      btnRetryGeo.classList.add('hidden');
      manualGeo.classList.add('hidden');
      tryBrowserGeo(true);
      setTimeout(function () {
        resetCekLokasiBtn();
      }, 5000);
    }

    document.getElementById('btn-cek-lokasi').addEventListener('click', function (e) {
      e.preventDefault();
      checkLocation();
    });

    btnUseManual.addEventListener('click', function () {
      const lat = parseFloat(manualLat.value);
      const lng = parseFloat(manualLng.value);
      if (isNaN(lat) || isNaN(lng)) {
        Swal.fire({ title: 'Astaghfirullah!', text: 'Masukkan latitude dan longitude yang valid.', icon: 'error', confirmButtonText: 'OK', confirmButtonColor: '#dc2626' });
        return;
      }
      onLocationReceived(lat, lng);
    });

    if (GEO_SETTINGS.length > 0) {
      if (!tryBrowserGeo(true)) {
        tryIPGeo();
      }
    } else {
      geoVerified = true;
      setGeoStatus('Geofencing belum dikonfigurasi', '#f59e0b', '#f59e0b');
      updateSubmitButton();
    }

    // ========== FORM SUBMIT ==========
    // Prevent form submit if conditions not met
    document.getElementById('absen-form').addEventListener('submit', function (e) {
      if (!geoVerified) {
        e.preventDefault();
        Swal.fire({
          title: 'Astaghfirullah!',
          text: 'Anda berada di luar area absensi yang ditentukan.',
          icon: 'error',
          confirmButtonText: 'OK',
          confirmButtonColor: '#dc2626'
        });
        return;
      }
      btnSubmit.disabled = true;
      btnSubmit.textContent = 'Memproses...';
    });
  </script>

</body>
</html>