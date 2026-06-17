<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Geofencing - Lib Ibrahimy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body {
            background-image: url('https://source.unsplash.com/1600x900/?nature,abstract');
            /* Placeholder background, ganti jika ada */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            animation: bgMove 15s ease-in-out infinite alternate;
        }

        /* ANIMASI MASUK CARD */
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

        /* ANIMASI INPUT */
        @keyframes pulseGlow {
            0% {
                box-shadow: 0 0 0px rgba(59, 130, 246, 0.0);
            }

            50% {
                box-shadow: 0 0 12px rgba(59, 130, 246, 0.6);
            }

            100% {
                box-shadow: 0 0 0px rgba(59, 130, 246, 0.0);
            }
        }

        /* BACKGROUND GERAK HALUS */
        @keyframes bgMove {
            0% {
                background-position: center top;
            }

            100% {
                background-position: center bottom;
            }
        }

        .animate-card {
            animation: fadeInUp 0.8s ease backwards;
        }

        /* Styling Map Leaflet untuk melengkung */
        #map {
            height: 450px;
            width: 100%;
            border-radius: 0.75rem;
            z-index: 1;
        }

        /* Custom Scrollbar untuk tabel */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body class="relative min-h-screen text-white font-sans antialiased">

    <!-- Overlay warna hijau elegan khas Lib Ibrahimy -->
    <div class="fixed inset-0 backdrop-blur-sm" style="background-color: rgba(34, 169, 83, 0.85);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-10">

        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4 animate-card"
            style="animation-delay: 0.1s;">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                    📍 Pengaturan Geofencing
                </h1>
                <p class="mt-2 text-sm text-white/80">Atur lokasi titik pusat dan radius jangkauan absensi.</p>
            </div>

            <!-- Logo Lib Ibrahimy -->
            <div
                class="flex items-center gap-2 text-2xl font-bold text-white bg-white/10 px-4 py-2 rounded-xl backdrop-blur-md border border-white/20 shadow-lg">
                <span class="px-2 py-1 text-blue-700 bg-white rounded">lib</span>
                <span>Ibrahimy</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Form Card -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl h-fit animate-card"
                style="animation-delay: 0.2s;">
                <h2 class="text-xl font-semibold mb-6 text-white border-b border-white/20 pb-3">Konfigurasi Wilayah</h2>

                <form action="{{ route('geofencing.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-white/80 mb-2">Lokasi
                            Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ $lokasi }}"
                            class="w-full px-4 py-3 rounded-lg bg-white/90 text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-400 focus:animate-[pulseGlow_0.6s_ease] transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-white/80 mb-2">Latitude
                            Pusat</label>
                        <input type="text" id="latitude" name="latitude" value="{{ $lat }}"
                            class="w-full px-4 py-3 rounded-lg bg-white/90 text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-400 focus:animate-[pulseGlow_0.6s_ease] transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-white/80 mb-2">Longitude
                            Pusat</label>
                        <input type="text" id="longitude" name="longtitude" value="{{ $lng }}"
                            class="w-full px-4 py-3 rounded-lg bg-white/90 text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-400 focus:animate-[pulseGlow_0.6s_ease] transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-white/80 mb-2">Radius
                            (Meter)</label>
                        <input type="number" id="radius" name="radius" value="{{ $radius }}"
                            class="w-full px-4 py-3 rounded-lg bg-white/90 text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-400 focus:animate-[pulseGlow_0.6s_ease] transition-all">
                    </div>

                    <div
                        class="p-4 bg-black/10 rounded-lg border border-white/10 text-xs text-white/90 leading-relaxed">
                        💡 <strong>Petunjuk:</strong> Anda bisa mengisi koordinat instan dengan mengklik langsung titik
                        manapun di dalam peta.
                    </div>

                    <button type="submit"
                        class="w-full mt-2 px-6 py-3 font-bold text-blue-700 transition duration-300 bg-white rounded-lg hover:bg-blue-50 hover:scale-[1.02] active:scale-95 shadow-xl">
                        Simpan Area Presensi
                    </button>
                </form>
            </div>

            <!-- Map Card -->
            <div class="lg:col-span-2 bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-2xl shadow-2xl animate-card"
                style="animation-delay: 0.3s;">
                <h2 class="text-xl font-semibold mb-4 text-white border-b border-white/20 pb-3">Peta Jangkauan</h2>
                <div id="map" class="border-[3px] border-white/30 shadow-inner"></div>
            </div>
        </div>

        <!-- Tabel Log Card -->
        <div class="mt-8 bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl animate-card"
            style="animation-delay: 0.4s;">
            <h2 class="text-xl font-semibold mb-4 text-white border-b border-white/20 pb-3">Log Aktif Presensi Terakhir
            </h2>
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full text-left text-sm text-white/90 whitespace-nowrap">
                    <thead class="bg-black/20 text-xs uppercase tracking-wider text-white">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-lg">Nomor WhatsApp</th>
                            <th class="px-6 py-4">Koordinat User</th>
                            <th class="px-6 py-4 rounded-tr-lg">Waktu Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($riwayatPresensi as $row)
                            <tr class="hover:bg-white/10 transition duration-200">
                                <td class="px-6 py-4 font-semibold">{{ $row->nomor_wa }}</td>
                                <td class="px-6 py-4 font-mono text-xs opacity-80">{{ $row->koordinat }}</td>
                                <td class="px-6 py-4 opacity-90">{{ $row->created_at->format('d M Y, H:i') }} WIB</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-8 text-center text-white/70 italic bg-black/5 rounded-b-lg">
                                    Belum ada riwayat presensi yang terekam hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-8 text-center text-xs text-white/60 animate-card" style="animation-delay: 0.5s;">
            ©2026 AsqiRizky-Librarian Developer
        </p>
    </div>

    <!-- SweetAlert Notification (Sama persis dengan template absensi) -->
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

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let initialLat = parseFloat(document.getElementById('latitude').value) || -7.8014;
        let initialLng = parseFloat(document.getElementById('longitude').value) || 110.3644;
        let initialRadius = parseInt(document.getElementById('radius').value) || 50;

        const map = L.map('map').setView([initialLat, initialLng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let centerMarker = L.marker([initialLat, initialLng]).addTo(map);

        let radiusCircle = L.circle([initialLat, initialLng], {
            color: '#2563eb', // Garis lingkaran (Biru agar serasi dengan tombol/font form)
            fillColor: '#3b82f6', // Isi lingkaran (Biru muda)
            fillOpacity: 0.2,
            weight: 2,
            radius: initialRadius
        }).addTo(map);

        map.on('click', function(e) {
            let clickedLat = e.latlng.lat.toFixed(7);
            let clickedLng = e.latlng.lng.toFixed(7);

            document.getElementById('latitude').value = clickedLat;
            document.getElementById('longitude').value = clickedLng;

            // Trigger animasi manual pada input agar terlihat interaktif
            document.getElementById('latitude').classList.remove('animate-[pulseGlow_0.6s_ease]');
            void document.getElementById('latitude').offsetWidth;
            document.getElementById('latitude').classList.add('animate-[pulseGlow_0.6s_ease]');

            centerMarker.setLatLng(e.latlng);
            radiusCircle.setLatLng(e.latlng);
        });

        document.getElementById('radius').addEventListener('input', function(e) {
            let currentRadius = parseInt(e.target.value);
            if (!isNaN(currentRadius) && currentRadius > 0) {
                radiusCircle.setRadius(currentRadius);
            }
        });
    </script>
</body>

</html>
