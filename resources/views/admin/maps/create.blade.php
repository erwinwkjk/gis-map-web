@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
@endsection

@section('content')
    <div class="container">
        <h1>Create Map</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.maps.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="number" step="0.000001" class="form-control" id="latitude" name="latitude"
                    value="{{ old('latitude') }}" required>
            </div>

            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="number" step="0.000001" class="form-control" id="longitude" name="longitude"
                    value="{{ old('longitude') }}" required>
            </div>

            <div class="form-group">
                <label for="zoom">Zoom</label>
                <input type="number" class="form-control" id="zoom" name="zoom" value="{{ old('zoom') }}">
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="marker_color">Warna Marker</label>
                <select class="form-control" id="marker_color" name="marker_color">
                    <option value="red">Merah</option>
                    <option value="yellow">Kuning</option>
                    <option value="green">Hijau</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="map_type">Tipe Map</label>
                <select class="form-control" id="map_type" name="map_type">
                <option value="roadmap" {{ old('map_type') == 'roadmap' ? 'selected' : '' }}>Roadmap</option>
                <option value="satellite" {{ old('map_type') == 'satellite' ? 'selected' : '' }}>Satellite</option>
                <option value="topography" {{ old('map_type') == 'topography' ? 'selected' : '' }}>Topography</option>
                </select>
            </div>

            <!-- Leaflet Map untuk Menggambar Polygon -->
            <div id="map" style="height: 400px; margin-bottom: 15px;"></div>

            <div class="form-group mb-3">
                <label for="polygon">Polygon</label>
                <textarea class="form-control" id="polygon" name="polygon" rows="4" readonly>{{ old('polygon') }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="jsonFile">Upload File:</label>
                <input type="file" name="jsonFile" id="jsonFile" accept=".json,.geojson" style="display: none;" onchange="document.getElementById('file-name').innerText = this.files[0].name;">
                <button type="button" class="btn btn-primary" onclick="document.getElementById('jsonFile').click()">Choose File</button>
                <span id="file-name" class="ml-2">No file chosen</span>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Map</button>
            <a href="{{ route('admin.maps.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

    <script>
        var map = L.map('map').setView([{{ old('latitude', -6.914744) }}, {{ old('longitude', 107.60981) }}], {{ old('zoom', 13) }});

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

        var tileLayer = L.tileLayer(getTileLayerUrl('{{ old('map_type', 'roadmap') }}'), {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        document.getElementById('map_type').addEventListener('change', function() {
            tileLayer.setUrl(getTileLayerUrl(this.value));
        });

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            edit: { featureGroup: drawnItems },
            draw: { polygon: true, rectangle: true, polyline: true, circle: true, marker: true, circlemarker: false }
        });
        map.addControl(drawControl);

        function updatePolygonTextarea() {
            var allFeatures = {
                type: "FeatureCollection",
                features: []
            };

            drawnItems.eachLayer(function(layer) {
                if (layer instanceof L.Polygon || layer instanceof L.Polyline || layer instanceof L.Marker || layer instanceof L.Circle) {
                    var feature = {
                        type: 'Feature',
                        geometry: {
                            type: layer instanceof L.Polygon ? 'Polygon' : layer instanceof L.Polyline ? 'LineString' : 'Point',
                            coordinates: layer instanceof L.Polygon ? [layer.getLatLngs()[0].map(point => [point.lng, point.lat])] :
                                layer instanceof L.Polyline ? layer.getLatLngs().map(point => [point.lng, point.lat]) :
                                [layer.getLatLng().lng, layer.getLatLng().lat]
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
            let featureName = prompt("Masukkan Nama Fitur:");
            let featureDescription = prompt("Masukkan Deskripsi Fitur:");

            if (featureName && featureDescription) {
                layer.feature = layer.feature || { type: "Feature" };
                layer.feature.properties = { name: featureName, description: featureDescription };
                layer.bindTooltip(`<b>${featureName}</b><br>${featureDescription}`);

                // Set marker dengan warna sesuai pilihan
                if (layer instanceof L.Marker) {
                    const selectedColor = document.getElementById('marker_color').value;
                    let iconUrl;

                    switch (selectedColor) {
                        case 'red':
                            iconUrl = '/images/marker_merah.png';
                            break;
                        case 'yellow':
                            iconUrl = '/images/marker_kuning.png';
                            break;
                        case 'green':
                            iconUrl = '/images/marker_hijau.png';
                            break;
                    }

                    layer.setIcon(L.icon({
                        iconUrl: iconUrl,
                        iconSize: [40, 41],
                        iconAnchor: [20, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    }));

                    // Set latitude dan longitude ke input saat marker ditambahkan
                    document.getElementById('latitude').value = layer.getLatLng().lat.toFixed(6);
                    document.getElementById('longitude').value = layer.getLatLng().lng.toFixed(6);
                }

                drawnItems.addLayer(layer);
                updatePolygonTextarea();
            } else {
                alert("Nama dan Deskripsi harus diisi!");
            }
        });

        map.on('draw:edited', updatePolygonTextarea);
        map.on('draw:deleted', updatePolygonTextarea);

        function updateMapInputs() {
            document.getElementById('latitude').value = map.getCenter().lat.toFixed(6);
            document.getElementById('longitude').value = map.getCenter().lng.toFixed(6);
            document.getElementById('zoom').value = map.getZoom();
        }

        map.on('moveend', updateMapInputs);
        map.on('zoomend', updateMapInputs);

        document.getElementById('jsonFile').addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var json = JSON.parse(event.target.result);
                    document.getElementById('polygon').value = JSON.stringify(json, null, 4);

                    // Clear any existing layers and add new ones from the JSON data
                    drawnItems.clearLayers();
                    L.geoJSON(json).eachLayer(function(layer) {
                        drawnItems.addLayer(layer);
                    });

                    alert("Polygon field has been populated from the uploaded JSON file.");
                };
                reader.readAsText(file);
            }
        });
    </script>
@endsection
