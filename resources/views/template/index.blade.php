<!DOCTYPE html>
@extends('layouts.template')

@section('content')
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peta Sesar</title>
    <!-- Link ke stylesheet Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Link ke stylesheet custom -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>
    <!-- Header -->
    <header>
        <center><h1>PETA JAWA BARAT</h1></center>
    </header>

    <div class="container">
        <!-- Info Box - Menampilkan jumlah data -->
        <section id="info-boxes">
            <div class="info-box">
                <h3>Jumlah User Terdaftar</h3>
                <p id="jumlah-user">0</p>
            </div>
            <div class="info-box">
                <h3>Jumlah Peta</h3>
                <p id="jumlah-peta">0</p>
            </div>
            <div class="info-box">
                <h3>Jumlah Galeri</h3>
                <p id="jumlah-galeri">0</p>
            </div>
            <div class="info-box">
                <h3>Jumlah Artikel</h3>
                <p id="jumlah-artikel">0</p>
            </div>
        </section>

        <!-- Peta -->
        <div class="map-container">
            <div id="map"></div>
        </div>
    </div>

    <!-- Script Leaflet -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Script untuk mengambil data berita dan jumlah -->
    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-6.914744, 107.609810], 10);  // Koordinat Bandung, contoh

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Ambil data GeoJSON dan tampilkan di peta
        fetch('/api/geojson-endpoint')  // Ganti dengan endpoint backend Anda
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data).addTo(map);
            });

        // Ambil jumlah user, peta, galeri, dan artikel
        fetch('/api/dashboard')  // Ganti dengan endpoint backend yang mengembalikan data jumlah
            .then(response => response.json())
            .then(data => {
                document.getElementById('jumlah-user').textContent = data.jumlah_user;
                document.getElementById('jumlah-peta').textContent = data.jumlah_peta;
                document.getElementById('jumlah-galeri').textContent = data.jumlah_galeri;
                document.getElementById('jumlah-artikel').textContent = data.jumlah_artikel;
            });
    </script>
</body>
</html>
@endsection
