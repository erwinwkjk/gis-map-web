@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
@endsection

@section('content')
    <div class="container">
        <h1>Edit Map</h1>

        <!-- Menampilkan Error Validasi -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulir untuk Mengedit Map -->
        <form action="{{ route('admin.maps.update', $map->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $map->name) }}">
            </div>

            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="number" step="0.000001" class="form-control" id="latitude" name="latitude"
                    value="{{ old('latitude', $map->latitude) }}" required>
            </div>

            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="number" step="0.000001" class="form-control" id="longitude" name="longitude"
                    value="{{ old('longitude', $map->longitude) }}" required>
            </div>

            <div class="form-group">
                <label for="zoom">Zoom</label>
                <input type="number" class="form-control" id="zoom" name="zoom"
                    value="{{ old('zoom', $map->zoom) }}">
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $map->description) }}</textarea>
            </div>

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

            <div class="form-group">
                <label for="polygon">Polygon</label>
                <textarea class="form-control" id="polygon" name="polygon" rows="4" readonly>{{ old('polygon', $map->polygon) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Map</button>
            <a href="{{ route('admin.maps.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
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

        @if ($map->polygon)
            var polygonData = {!! $map->polygon !!};
            L.geoJSON(polygonData, {
                onEachFeature: function(feature, layer) {
                    if (feature.properties && feature.properties.name) {
                        layer.bindTooltip(
                            `<b>${feature.properties.name}</b><br>${feature.properties.description}`);
                    }
                    drawnItems.addLayer(layer);
                }
            });
        @endif

        var drawControl = new L.Control.Draw({
            edit: {
                featureGroup: drawnItems
            },
            draw: {
                polygon: true,
                rectangle: true,
                polyline: true,
                circle: true,
                marker: true,
                circlemarker: false
            }
        });
        map.addControl(drawControl);

        function updatePolygonTextarea() {
            var allFeatures = {
                type: "FeatureCollection",
                features: []
            };

            drawnItems.eachLayer(function(layer) {
                if (layer instanceof L.Polygon || layer instanceof L.Polyline || layer instanceof L.Marker ||
                    layer instanceof L.Circle) {
                    var feature = {
                        type: 'Feature',
                        geometry: {
                            type: layer instanceof L.Polygon ? 'Polygon' : layer instanceof L.Polyline ?
                                'LineString' : 'Point',
                            coordinates: layer instanceof L.Polygon ? [layer.getLatLngs()[0].map(point => [point
                                .lng, point.lat
                            ])] : layer instanceof L.Polyline ? layer.getLatLngs().map(point => [point.lng,
                                point.lat
                            ]) : [layer.getLatLng().lng, layer.getLatLng().lat]
                        },
                        properties: layer.feature && layer.feature.properties ? layer.feature.properties : {}
                    };
                    allFeatures.features.push(feature);
                }
            });

            document.getElementById('polygon').value = JSON.stringify(allFeatures, null, 4);
        }

        map.on('draw:created', function(e) {
            var layer = e.layer;
            drawnItems.addLayer(layer);
            updatePolygonTextarea();
        });

        map.on('draw:edited', function(e) {
            updatePolygonTextarea();
        });
        map.on('draw:deleted', function(e) {
            updatePolygonTextarea();
        });

        function updateMapInputs() {
            document.getElementById('latitude').value = map.getCenter().lat.toFixed(6);
            document.getElementById('longitude').value = map.getCenter().lng.toFixed(6);
            document.getElementById('zoom').value = map.getZoom();
        }

        map.on('moveend', function() {
            updateMapInputs();
        });
        map.on('zoomend', function() {
            updateMapInputs();
        });
    </script>
@endsection
