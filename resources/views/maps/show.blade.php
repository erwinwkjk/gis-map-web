@extends('layouts.home')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
@endsection

@section('content')
    <div class="container">
        <h1>{{ $map->name }}</h1>

        <!-- Formulir untuk Mengedit Map -->

        <div class="form-group">
            <label for="map_type">Tipe Map</label>
            <select class="form-control" id="map_type" name="map_type">
                <option value="roadmap" {{ old('map_type', $map->map_type) == 'roadmap' ? 'selected' : '' }}>Roadmap
                </option>
                <option value="satellite" {{ old('map_type', $map->map_type) == 'satellite' ? 'selected' : '' }}>
                    Satellite</option>
                <option value="topography" {{ old('map_type', $map->map_type) == 'topography' ? 'selected' : '' }}>
                    Topography</option>
            </select>
        </div>

        <!-- Leaflet Map untuk Mengedit Polygon -->
        <div id="map" style="height: 400px; margin-bottom: 15px;"></div>
        <a href="{{ route('maps.index') }}" class="btn btn-secondary">Kembali</a> <!-- Tombol kembali di sini -->
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

    <script>
        var map = L.map('map').setView([{{ old('latitude', $map->latitude) }}, {{ old('longitude', $map->longitude) }}],
            {{ old('zoom', $map->zoom) }});

        function getTileLayerUrl(mapType) {
            switch (mapType) {
                case 'satellite':
                    return 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';
                case 'topography':
                    return 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png';
                default:
                    return 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
            }
        }

        var tileLayer = L.tileLayer(getTileLayerUrl('{{ old('map_type', $map->map_type) }}'), {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        document.getElementById('map_type').addEventListener('change', function() {
            tileLayer.setUrl(getTileLayerUrl(this.value));
        });

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Fungsi untuk mendapatkan icon marker sesuai warna
        function getMarkerIcon(color) {
            const colors = {
                red: '/images/marker_merah.png',
                yellow: '/images/marker_kuning.png',
                green: '/images/marker_hijau.png'
            };
            return L.icon({
                iconUrl: colors[color] || colors.red, // Default ke merah
                iconSize: [40, 41],
                iconAnchor: [20, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
        }

        @if ($map->polygon)
        var polygonData = {!! $map->polygon !!};
        L.geoJSON(polygonData, {
            pointToLayer: function(feature, latlng) {
                // Ambil warna dari properti 'marker_color' dalam fitur, atau gunakan warna default
                var color = '{{ $map->marker_color }}'; // Ambil warna dari database langsung
                return L.marker(latlng, { icon: getMarkerIcon(color) });
            },
            onEachFeature: function(feature, layer) {
                if (feature.properties && feature.properties.name) {
                    layer.bindTooltip(
                        `<b>${feature.properties.name}</b><br>${feature.properties.description}`
                    );
                }
                drawnItems.addLayer(layer);
            }
        });
    @endif

    // Fungsi untuk mendapatkan icon marker sesuai warna
    function getMarkerIcon(color) {
        const colors = {
            red: '/images/marker_merah.png',
            yellow: '/images/marker_kuning.png',
            green: '/images/marker_hijau.png'
        };
        return L.icon({
            iconUrl: colors[color] || colors.red, // Default ke merah jika warna tidak dikenal
            iconSize: [40, 41],
            iconAnchor: [20, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    }
    </script>
@endsection
