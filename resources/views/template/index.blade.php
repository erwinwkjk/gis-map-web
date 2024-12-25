@extends('layouts.template')

@section('content')
    <!--Home-->
    <!-- Dashboard utama-->
    <div class="trending-area fix">
        <div class="container">
            <div class="trending-main">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="trending-top mb-30">
                            <div class="trend-top-img">
                                <img src="assets/img/trending/trending_top.jpg" alt="">
                                <div class="trend-top-cap">
                                    <h2><a href="details.html">PETA KESELURUHAN</a></h2>
                                </div>
                            </div>
                        </div>
                        <!-- Trending Bottom -->
                        <div class="trending-bottom">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="single-bottom mb-35">
                                        <div class="trend-bottom-img mb-30">
                                            <img src="assets/img/trending/trending_bottom1.jpg" alt="">
                                        </div>
                                        <div class="trend-bottom-cap">
                                            <span class="color1">USER</span>
                                            <h4><a href="details.html">Menunjukan user yang terdaftar</a></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="single-bottom mb-35">
                                        <div class="trend-bottom-img mb-30">
                                            <img src="assets/img/trending/trending_bottom2.jpg" alt="">
                                        </div>
                                        <div class="trend-bottom-cap">
                                            <span class="color2">GALLERY</span>
                                            <h4>
                                                <h4><a href="details.html">Menunjukan foto yang di upload</a></h4>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="single-bottom mb-35">
                                        <div class="trend-bottom-img mb-30">
                                            <img src="assets/img/trending/trending_bottom3.jpg" alt="">
                                        </div>
                                        <div class="trend-bottom-cap">
                                            <span class="color3">ARTICKELS</span>
                                            <h4><a href="details.html">Menunjukan artikel yang di upload</a></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Riht content -->
                    <div class="col-lg-4">
                        <div class="trand-right-single d-flex">
                            <div class="trand-right-img">
                                <img src="assets/img/trending/right1.jpg" alt="">
                            </div>
                            <div class="trand-right-cap">
                                <span class="color1">GRAFIK KEMIRINGAN TANAH</span>
                                <h4><a href="details.html">Grafik data kemiringan tanah</a></h4>
                            </div>
                        </div>
                        <div class="trand-right-single d-flex">
                            <div class="trand-right-img">
                                <img src="assets/img/trending/right2.jpg" alt="">
                            </div>
                            <div class="trand-right-cap">
                                <span class="color3">GRAFIK GEMPA</span>
                                <h4><a href="details.html">Grafik data gempa</a></h4>
                            </div>
                        </div>
                        <div class="trand-right-single d-flex">
                            <div class="trand-right-img">
                                <img src="assets/img/trending/right3.jpg" alt="">
                            </div>
                            <div class="trand-right-cap">
                                <span class="color2">GRAFIK CURAH HUJAN</span>
                                <h4><a href="details.html">Grafik data curah hujan</a></h4>
                            </div>
                        </div>
                        <div class="trand-right-single d-flex">
                            <div class="trand-right-img">
                                <img src="assets/img/trending/right4.jpg" alt="">
                            </div>
                            <div class="trand-right-cap">
                                <span class="color4">GRAFIK DATA LAIN</span>
                                <h4><a href="details.html">Grafik data pergerakan tanah</a></h4>
                            </div>
                        </div>
                        <div class="trand-right-single d-flex">
                            <div class="trand-right-img">
                                <img src="assets/img/trending/right5.jpg" alt="">
                            </div>
                            <div class="trand-right-cap">
                                <span class="color1">GRAFIK KEDALAMAN TANAH</span>
                                <h4><a href="details.html">Grafik data kedalaman tanah</a></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--maplist-->
    <!--daftar map yang sudah di unggah-->
    <div class="container mt-4">
    <h3>MAP LIST</h3>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nama Peta</th>
                <th>Deskripsi</th>
                <th>Tanggal Unggah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="map-table-body">
            <!-- Baris data akan dimuat secara dinamis -->
        </tbody>
    </table>
</div>

<!-- Script untuk Memuat Data ke Tabel -->
<script>
    // Ambil data peta dari backend
    fetch('/api/maps') // Ganti dengan endpoint backend Anda
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById('map-table-body');
            data.forEach((map, index) => {
                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${map.title}</td>
                    <td>${map.description}</td>
                    <td>${map.uploaded_at}</td>
                    <td>
                        <a href="/maps/${map.id}" class="btn btn-info btn-sm">Lihat</a>
                        <button class="btn btn-danger btn-sm" onclick="deleteMap(${map.id})">Hapus</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching map data:', error);
            let tableBody = document.getElementById('map-table-body');
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-danger">Gagal memuat data peta.</td>
                </tr>
            `;
        });

    // Fungsi untuk menghapus peta (opsional)
    function deleteMap(mapId) {
        if (confirm('Apakah Anda yakin ingin menghapus peta ini?')) {
            fetch(`/api/maps/${mapId}`, { method: 'DELETE' }) // Ganti dengan endpoint DELETE backend Anda
                .then(response => {
                    if (response.ok) {
                        alert('Peta berhasil dihapus.');
                        location.reload(); // Muat ulang halaman untuk memperbarui data
                    } else {
                        alert('Gagal menghapus peta.');
                    }
                })
                .catch(error => console.error('Error deleting map:', error));
        }
    }
</script>

<!--GALLERY-->
    <section class="whats-news-area pt-50 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row d-flex justify-content-between">
                        <div class="col-lg-3 col-md-3">
                            <div class="section-tittle mb-30">
                                <h3>GALLERY</h3>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div class="properties__button"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <!-- Nav Card -->
                            <div class="tab-content" id="nav-tabContent">
                                <!-- card one -->
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="whats-news-caption">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews1.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">GEMPA TEKTONIK</span>
                                                        <h4><a href="#">gempa bumi yang disebabkan oleh pergerakan lempeng tektonik di bawah permukaan bumi</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews2.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">SESAR</span>
                                                        <h4><a href="#">patahan atau retakan pada lapisan bumi yang mengalami pergerakan relatif antara dua blok batuan</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews2.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">SESAR</span>
                                                        <h4><a href="#">patahan atau retakan pada lapisan bumi yang mengalami pergerakan relatif antara dua blok batuan</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews2.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">SESAR</span>
                                                        <h4><a href="#">patahan atau retakan pada lapisan bumi yang mengalami pergerakan relatif antara dua blok batuan</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<!--ARTIKELS-->
    <section class="whats-news-area pt-50 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row d-flex justify-content-between">
                        <div class="col-lg-3 col-md-3">
                            <div class="section-tittle mb-30">
                                <h3>ARTIKELS</h3>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div class="properties__button"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <!-- Nav Card -->
                            <div class="tab-content" id="nav-tabContent">
                                <!-- card one -->
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                    aria-labelledby="nav-home-tab">
                                    <div class="whats-news-caption">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews1.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">GEMPA TEKTONIK</span>
                                                        <h4><a href="#">gempa bumi yang disebabkan oleh pergerakan lempeng tektonik di bawah permukaan bumi</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews2.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">SESAR</span>
                                                        <h4><a href="#">patahan atau retakan pada lapisan bumi yang mengalami pergerakan relatif antara dua blok batuan</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews2.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">SESAR</span>
                                                        <h4><a href="#">patahan atau retakan pada lapisan bumi yang mengalami pergerakan relatif antara dua blok batuan</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="single-what-news mb-100">
                                                    <div class="what-img">
                                                        <img src="assets/img/news/whatNews2.jpg" alt="">
                                                    </div>
                                                    <div class="what-cap">
                                                        <span class="color1">SESAR</span>
                                                        <h4><a href="#">patahan atau retakan pada lapisan bumi yang mengalami pergerakan relatif antara dua blok batuan</a></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

@endsection
