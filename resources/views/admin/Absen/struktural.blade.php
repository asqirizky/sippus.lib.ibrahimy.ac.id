<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Absensi Pustakawan Struktural - Perpustakaan Ibrahimy</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes pulseGlow {
      0% { box-shadow: 0 0 0px rgba(59,130,246,0.0); }
      50% { box-shadow: 0 0 12px rgba(59,130,246,0.5); }
      100% { box-shadow: 0 0 0px rgba(59,130,246,0.0); }
    }

    @keyframes bgMove {
      0% { background-position: center top; }
      100% { background-position: center bottom; }
    }

    .animate-card {
      animation: fadeInUp 0.8s ease;
    }

    body {
      animation: bgMove 10s ease-in-out infinite alternate;
    }

    #camera-preview {
      width: 100%;
      max-height: 240px;
      object-fit: cover;
      border-radius: 0.75rem;
      transform: scaleX(-1);
    }

    #captured-photo {
      width: 100%;
      max-height: 240px;
      object-fit: contain;
      border-radius: 0.75rem;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.375rem;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .status-badge .dot {
      width: 0.5rem;
      height: 0.5rem;
      border-radius: 9999px;
    }
  </style>
</head>

<body class="relative flex items-center justify-center min-h-screen">

  <!-- Overlay -->
  <div class="absolute inset-0 backdrop-blur-sm" style="background-color: #22a953"></div>

  <!-- Form Card -->
  <div class="relative z-10 w-full max-w-md p-6 text-center shadow-2xl bg-white/10 backdrop-blur-md rounded-2xl animate-card">

    <!-- Logo -->
    <h1 class="flex items-center justify-center gap-2 mb-4 text-3xl font-bold text-white">
      <span class="px-2 py-1 text-blue-700 bg-white rounded">lib</span>
      <span>Ibrahimy</span>
    </h1>

    <!-- Status Geofencing -->
    <div id="geo-status" class="mb-4">
      <span class="status-badge bg-gray-500/50 text-white">
        <span class="dot bg-gray-300"></span>
        Memeriksa lokasi...
      </span>
    </div>
    <button type="button" id="btn-retry-geo"
      class="hidden text-xs text-white/80 underline hover:text-white mb-2"
      onclick="retryGeolocation()">
      Coba lagi
    </button>
    <!-- Manual coordinate input (hidden, shown as last resort) -->
    <div id="manual-geo" class="hidden mb-3 space-y-2">
      <input type="text" id="manual-lat" placeholder="Latitude (cth: -7.7510)"
        class="w-full px-3 py-2 text-sm text-center rounded-lg bg-white/90 focus:outline-none">
      <input type="text" id="manual-lng" placeholder="Longitude (cth: 114.2737)"
        class="w-full px-3 py-2 text-sm text-center rounded-lg bg-white/90 focus:outline-none">
      <button type="button" id="btn-use-manual"
        class="px-4 py-1.5 text-sm font-semibold text-blue-700 bg-white rounded-lg hover:bg-blue-100 transition">
        Gunakan Koordinat Manual
      </button>
    </div>

    <!-- Camera Section -->
    <div id="camera-section" class="mb-4">
      <video id="camera-preview" autoplay playsinline class="bg-black/30 mb-2"></video>
      <div id="camera-controls" class="flex justify-center gap-2">
        <button type="button" id="btn-capture"
          class="px-4 py-1.5 text-sm font-semibold text-blue-700 bg-white rounded-lg hover:bg-blue-100 transition disabled:opacity-50 disabled:cursor-not-allowed"
          disabled>
          Ambil Foto
        </button>
      </div>
      <img id="captured-photo" class="hidden mt-2" />
      <input type="hidden" name="foto" id="foto-input" />
      <input type="hidden" name="latitude" id="latitude-input" />
      <input type="hidden" name="longitude" id="longitude-input" />
    </div>

    <!-- Form -->
    <form class="form" method="POST" action="{{ route('struktural-proses') }}" id="absen-form">
      @csrf
      <div>
        <input type="text" name="nik" placeholder="Masukkan NIK" required
          class="w-full px-4 py-3 text-center rounded-lg bg-white/90 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:animate-[pulseGlow_0.6s_ease]" autofocus/>
      </div>

      <div class="flex justify-center mt-4">
        <button type="submit" id="btn-submit"
          class="px-6 py-2 font-semibold text-blue-700 transition duration-300 bg-white rounded-lg hover:bg-blue-100 hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
          disabled>
          Submit
        </button>
      </div>
    </form>

    <!-- Footer -->
    <p class="mt-6 text-xs text-white/80">
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
    let photoCaptured = false;
    let cameraStream = null;

    const geoStatus = document.getElementById('geo-status');
    const video = document.getElementById('camera-preview');
    const capturedImg = document.getElementById('captured-photo');
    const fotoInput = document.getElementById('foto-input');
    const latInput = document.getElementById('latitude-input');
    const lngInput = document.getElementById('longitude-input');
    const btnCapture = document.getElementById('btn-capture');
    const btnSubmit = document.getElementById('btn-submit');

    function updateSubmitButton() {
      btnSubmit.disabled = !(geoVerified && photoCaptured);
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

    function tryBrowserGeo(highAccuracy) {
      if (!navigator.geolocation) {
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
      setGeoStatus('Masukkan koordinat manual', '#f59e0b', '#f59e0b');
      manualGeo.classList.remove('hidden');
    }

    function retryGeolocation() {
      btnRetryGeo.classList.add('hidden');
      manualGeo.classList.add('hidden');
      tryBrowserGeo(true);
    }

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

    // ========== CAMERA ==========
    async function startCamera() {
      try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
        video.srcObject = cameraStream;
        btnCapture.disabled = false;
      } catch {
        Swal.fire({
          title: 'Kamera tidak tersedia',
          text: 'Akses kamera diperlukan untuk absen. Izinkan akses kamera dan reload halaman.',
          icon: 'error',
          confirmButtonText: 'OK',
          confirmButtonColor: '#dc2626'
        });
      }
    }

    function stopCamera() {
      if (cameraStream) {
        cameraStream.getTracks().forEach(function (track) { track.stop(); });
        cameraStream = null;
      }
    }

    btnCapture.addEventListener('click', function () {
      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth || 640;
      canvas.height = video.videoHeight || 480;
      const ctx = canvas.getContext('2d');
      ctx.translate(canvas.width, 0);
      ctx.scale(-1, 1);
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
      fotoInput.value = dataUrl;
      capturedImg.src = dataUrl;
      capturedImg.classList.remove('hidden');
      video.classList.add('hidden');
      btnCapture.textContent = 'Foto Ulang';
      photoCaptured = true;
      updateSubmitButton();
    });

    video.addEventListener('click', function () {
      photoCaptured = false;
      capturedImg.classList.add('hidden');
      video.classList.remove('hidden');
      btnCapture.textContent = 'Ambil Foto';
      fotoInput.value = '';
      updateSubmitButton();
    });

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
      if (!photoCaptured) {
        e.preventDefault();
        Swal.fire({
          title: 'Astaghfirullah!',
          text: 'Silakan ambil foto terlebih dahulu.',
          icon: 'error',
          confirmButtonText: 'OK',
          confirmButtonColor: '#dc2626'
        });
        return;
      }
      btnSubmit.disabled = true;
      btnSubmit.textContent = 'Memproses...';
    });

    // Start camera on load
    startCamera();

    // Cleanup on page unload
    window.addEventListener('beforeunload', function () {
      stopCamera();
    });
  </script>

</body>
</html>