@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Maps</h1>

        <!-- Tombol untuk Membuat Map Baru -->
        <div class="mb-3">
            <a href="{{ route('admin.maps.create') }}" class="btn btn-success">+ Create Map</a>
        </div>

        <!-- Form untuk Search -->
        <form action="{{ route('admin.maps.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama"
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="d-flex justify-content-start mt-2">
                <div class="form-group mx-sm-2">
                    <label for="per_page" class="mr-2">Items per page:</label>
                    <select name="per_page" id="per_page" class="form-control form-select" onchange="this.form.submit()">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
            </div>
        </form>

        <!-- Tabel Data Maps -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Zoom</th>
                    <th>Deskripsi</th>
                    <th>Tipe Map</th>
                    <th>Polygon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($maps as $map)
                    <tr>
                        <td>{{ $map->id }}</td>
                        <td>{{ $map->name }}</td>
                        <td>{{ $map->latitude }}</td>
                        <td>{{ $map->longitude }}</td>
                        <td>{{ $map->zoom }}</td>
                        <td>{{ $map->description }}</td>
                        <td>{{ $map->map_type }}</td>
                        <td>{{ Str::limit($map->polygon, 50) }}</td>
                        <td>
                            <a href="{{ route('admin.maps.edit', $map->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.maps.destroy', $map->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus map ini?')">Delete</button>
                            </form>
                            <a href="{{ route('admin.maps.show', $map->id) }}" class="btn btn-info btn-sm">Lihat Map</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $maps->links() }}
        </div>
    </div>
@endsection
