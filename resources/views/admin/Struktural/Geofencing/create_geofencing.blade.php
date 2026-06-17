@extends('layout.sidebarnavbar')
@section('admin-konten')

<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        {{-- Toolbar --}}
        <div id="kt_app_toolbar" class="py-3 app-toolbar py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="flex-wrap page-title d-flex flex-column justify-content-center me-3">
                    <h1 class="my-0 text-gray-900 page-heading d-flex fw-bold fs-3 flex-column justify-content-center">
                        Tambah Koordinat
                    </h1>
                    <ul class="pt-1 my-0 breadcrumb breadcrumb-separatorless fw-semibold fs-7">
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bg-gray-500 bullet w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            Tambah Koordinat
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        {{-- Content --}}
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <form class="form" method="POST" enctype="multipart/form-data" action="admin/struktural-geofencing">
                    @csrf
                    <div class="row g-5">
                        {{-- Form --}}
                        <div class="col-lg-4">
                            <div class="card card-flush shadow-sm">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Setting Geofencing</h2>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-5">
                                        <label class="required form-label">Ruang Pustakawan</label>
                                        <select name="ruang_id" class="form-select" data-control="select2" data-hide-search="true">
                                            <option selected disabled>Pilih Lokasi</option>
                                            @foreach ($ruang as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->ruang_pustakawans }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-5">
                                        <label class="required form-label">Latitude</label>
                                        <input type="text" id="latitude" class="form-control" name="latitude" placeholder="Latitude">
                                    </div>
                                    <div class="mb-5">
                                        <label class="required form-label">Longitude</label>
                                        <input type="text" id="longitude" class="form-control" name="longitude" placeholder="Longitude">
                                    </div>
                                    <div class="mb-5">
                                        <label class="required form-label">Radius (Meter)</label>
                                        <input type="number" id="radius" class="form-control" name="radius" placeholder="Radius" value="50">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Simpan Koordinat</button>
                                </div>
                            </div>
                        </div>
                        {{-- Map --}}
                        <div class="col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h3 class="card-title">Peta Jangkauan</h3>
                                </div>
                                <div class="card-body">
                                    <div id="map"
                                        style="height: 600px; width: 100%; border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layout.footer')
</div>

{{-- Leaflet CSS --}}
<link rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        let initialLat = -7.8014;
        let initialLng = 110.3644;
        let initialRadius = 50;

        // ===============================
        // INIT MAP
        // ===============================
        const map = L.map('map').setView([initialLat, initialLng], 16);

        // ===============================
        // GOOGLE HYBRID
        // ===============================
        const googleHybrid = L.tileLayer(
            'https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                attribution: '&copy; Google Maps',
                maxZoom: 22
            }
        );

        // ===============================
        // GOOGLE STREET
        // ===============================
        const googleStreet = L.tileLayer(
            'https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                attribution: '&copy; Google Maps',
                maxZoom: 22
            }
        );

        // ===============================
        // GOOGLE SATELLITE ONLY
        // ===============================
        const googleSatellite = L.tileLayer(
            'https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                attribution: '&copy; Google Maps',
                maxZoom: 22
            }
        );

        // ===============================
        // DEFAULT MAP
        // ===============================
        googleHybrid.addTo(map);

        // ===============================
        // LAYER CONTROL
        // ===============================
        const baseMaps = {
            "Hybrid": googleHybrid,
            "Street": googleStreet,
            "Satellite": googleSatellite
        };

        L.control.layers(baseMaps).addTo(map);

        // ===============================
        // MARKER TITIK PUSAT
        // ===============================
        let marker = L.marker([initialLat, initialLng]).addTo(map);

        // ===============================
        // LINGKARAN RADIUS
        // ===============================
        let circle = L.circle([initialLat, initialLng], {
            color: '#2563eb',
            fillColor: '#2563eb',
            fillOpacity: 0.2,
            radius: initialRadius,
            weight: 2
        }).addTo(map);

        // ===============================
        // KLIK MAP
        // ===============================
        map.on('click', function(e) {

            let lat = e.latlng.lat.toFixed(7);
            let lng = e.latlng.lng.toFixed(7);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            marker.setLatLng(e.latlng);
            circle.setLatLng(e.latlng);

            map.flyTo(e.latlng, 18, {
                duration: 1
            });
        });

        // ===============================
        // UPDATE RADIUS REALTIME
        // ===============================
        document.getElementById('radius').addEventListener('input', function(e) {

            let radius = parseInt(e.target.value);

            if (!isNaN(radius) && radius > 0) {
                circle.setRadius(radius);
            }
        });

        // ===============================
        // FIX RENDER MAP
        // ===============================
        setTimeout(() => {
            map.invalidateSize();
        }, 300);

    });
</script>

@endsection
