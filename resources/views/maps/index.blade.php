@extends('layouts.home')

@section('content')
    <div class="container">
        <h1>Daftar Maps</h1>

        <!-- Form untuk Search -->
        <form action="{{ route('maps.index') }}" method="GET" class="mb-3">
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

        <!-- Tampilan Data Maps sebagai Card -->
        <div class="row">
            @foreach ($maps as $map)
                <a href="{{ route('maps.show', $map->id) }}" class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $map->name }}</h5>
                            <p class="card-text">{{ $map->description }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $maps->links() }}
        </div>
    </div>
@endsection
