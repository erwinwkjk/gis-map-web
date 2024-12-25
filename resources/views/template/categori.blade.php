@extends('layouts.template')

<main>

    @section('content')
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
    @endsection
</main>