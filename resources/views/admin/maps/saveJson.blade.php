@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Maps</h1>

    <!-- Form untuk Save JSON -->
    <form action="{{ route('admin.maps.saveJson', $map->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-secondary btn-sm">Unduh JSON</button>
    </form>
</div>
@endsection
