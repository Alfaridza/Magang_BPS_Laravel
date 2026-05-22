@extends('presensi.layouts.app')

@section('content')
<div class="h-screen w-full flex flex-col bg-gray-100">
    <!-- Header -->
    <div class="bg-white px-4 py-4 flex items-center shadow-sm z-20">
        <a href="{{ route('presensi.dashboard') }}" class="text-gray-600 hover:text-gray-900 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="font-bold text-lg text-gray-800">
            Absen {{ ucfirst($tipe) }}
        </h1>
    </div>

    <!-- Map Container -->
    <div class="flex-1 relative z-10">
        <div id="map" class="w-full h-full z-0"></div>
        
        <!-- Status Overlay -->
        <div class="absolute top-4 left-4 right-4 z-20">
            <div class="bg-white rounded-xl shadow-lg p-3 flex items-center justify-between border-l-4 border-gray-400" id="status-card">
                <div class="flex items-center gap-3">
                    <div id="status-icon-bg" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                        <i id="status-icon" class="fas fa-spinner fa-spin text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Status Lokasi</p>
                        <h4 id="status-text" class="font-bold text-gray-800 text-sm">Mencari sinyal GPS...</h4>
                    </div>
                </div>
                <div class="text-right">
                    <span id="distance-text" class="text-lg font-bold text-gray-800">--</span>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wide font-semibold mt-0.5">Jarak</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Action -->
    <div class="bg-white p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
        <button id="btn-absen" class="w-full py-4 rounded-xl font-bold text-white text-lg flex items-center justify-center gap-2 bg-gray-400 cursor-not-allowed transition-all duration-300" disabled>
            <i class="fas fa-camera"></i> Absen {{ ucfirst($tipe) }}
        </button>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
    /* Prevent leaflet zoom controls from overlapping header */
    .leaflet-control-zoom {
        margin-top: 90px !important;
    }
</style>

<script>
    const BPS_LAT = -6.171274937865753;  
    const BPS_LNG = 106.16087446395497;
    const MAX_RADIUS = {{ $radius }};

    let map, userMarker, bpsCircle;
    
    // Initialize Map
    document.addEventListener('DOMContentLoaded', function() {
        map = L.map('map', {
            zoomControl: true
        }).setView([BPS_LAT, BPS_LNG], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Draw BPS Radius Circle
        bpsCircle = L.circle([BPS_LAT, BPS_LNG], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.25,
            radius: MAX_RADIUS
        }).addTo(map);

        initGeolocation();
    });

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3;
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2-lat1) * Math.PI/180;
        const Δλ = (lon2-lon1) * Math.PI/180;

        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        return R * c;
    }

    function initGeolocation() {
        if (!navigator.geolocation) {
            updateUI('error', 'GPS Tidak Didukung', null);
            return;
        }

        navigator.geolocation.watchPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const distance = Math.round(calculateDistance(lat, lng, BPS_LAT, BPS_LNG));

                // Update marker
                if (!userMarker) {
                    // Create a custom blue marker
                    userMarker = L.marker([lat, lng]).addTo(map);
                    // Fit bounds to show both BPS and User if distance is large
                    if (distance > MAX_RADIUS) {
                        const bounds = L.latLngBounds([
                            [BPS_LAT, BPS_LNG],
                            [lat, lng]
                        ]);
                        map.fitBounds(bounds, { padding: [50, 50] });
                    } else {
                        map.setView([lat, lng], 18);
                    }
                } else {
                    userMarker.setLatLng([lat, lng]);
                }

                if (distance <= MAX_RADIUS) {
                    updateUI('success', 'Lokasi Sesuai', distance);
                } else {
                    updateUI('danger', 'Di Luar Radius', distance);
                }
            },
            (error) => {
                let msg = 'Gagal melacak lokasi';
                if(error.code === 1) msg = 'Akses Lokasi Ditolak';
                else if(error.code === 2) msg = 'Sinyal GPS Lemah';
                else if(error.code === 3) msg = 'Waktu Habis';
                
                updateUI('error', msg, null);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    function updateUI(status, text, distance) {
        const card = document.getElementById('status-card');
        const iconBg = document.getElementById('status-icon-bg');
        const icon = document.getElementById('status-icon');
        const statusText = document.getElementById('status-text');
        const distText = document.getElementById('distance-text');
        const btn = document.getElementById('btn-absen');

        statusText.textContent = text;
        distText.textContent = distance !== null ? distance + 'm' : '--';

        // Reset classes
        card.className = 'bg-white rounded-xl shadow-lg p-3 flex items-center justify-between border-l-4 transition-colors duration-300';
        iconBg.className = 'w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-300';
        icon.className = 'transition-colors duration-300';

        if (status === 'success') {
            card.classList.add('border-green-500');
            iconBg.classList.add('bg-green-100');
            icon.className = 'fas fa-check-circle text-green-600 text-lg';
            statusText.className = 'font-bold text-green-700 text-sm';
            distText.className = 'text-lg font-bold text-green-700';

            btn.disabled = false;
            // Biru matching user screenshot
            btn.className = 'w-full py-4 rounded-xl font-bold text-white text-lg flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer transform hover:-translate-y-0.5';
            
            // Set onclick
            btn.onclick = function() {
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                
                fetch("{{ route('presensi.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        tipe: '{{ $tipe }}',
                        latitude: userMarker.getLatLng().lat,
                        longitude: userMarker.getLatLng().lng
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        let msgText = '{{ $tipe }}' === 'masuk' ? 'Selamat Bekerja dan Semangat!' : 'Hati-hati di jalan dan Selamat Beristirahat!';
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: msgText,
                            confirmButtonColor: '#7a5af8', // Warna ungu menyesuaikan gambar
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'px-6 py-2 rounded-lg font-semibold'
                            }
                        }).then(() => {
                            window.location.href = "{{ route('presensi.dashboard') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message,
                            confirmButtonColor: '#ef4444',
                        });
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan sistem saat menyimpan presensi.',
                        confirmButtonColor: '#ef4444',
                    });
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            };

        } else if (status === 'danger') {
            card.classList.add('border-red-500');
            iconBg.classList.add('bg-red-100');
            icon.className = 'fas fa-times-circle text-red-600 text-lg';
            statusText.className = 'font-bold text-red-700 text-sm';
            distText.className = 'text-lg font-bold text-red-700';

            btn.disabled = true;
            btn.className = 'w-full py-4 rounded-xl font-bold text-white text-lg flex items-center justify-center gap-2 bg-gray-400 cursor-not-allowed transition-all duration-300';
            btn.onclick = null;
        } else {
            card.classList.add('border-gray-400');
            iconBg.classList.add('bg-gray-100');
            icon.className = 'fas fa-exclamation-triangle text-gray-500 text-lg';
            statusText.className = 'font-bold text-gray-700 text-sm';
            distText.className = 'text-lg font-bold text-gray-700';

            btn.disabled = true;
            btn.className = 'w-full py-4 rounded-xl font-bold text-white text-lg flex items-center justify-center gap-2 bg-gray-400 cursor-not-allowed transition-all duration-300';
            btn.onclick = null;
        }
    }
</script>
@endsection
