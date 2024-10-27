<!-- Form untuk Save JSON -->
<form action="{{ route('admin.maps.downloadJson', $map->id) }}" method="GET">
    <button type="submit" class="btn btn-secondary btn-sm">Unduh JSON</button>
</form>
