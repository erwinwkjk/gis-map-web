<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Closure;

class MapController extends Controller
{
    // Menampilkan daftar semua maps
    public function index(Request $request)
    {
        // Ambil parameter pencarian dan jumlah per halaman dari request
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default 10 jika tidak ada parameter per_page

        // Query untuk mendapatkan data dengan pagination, pencarian, dan jumlah item per halaman
        $maps = Map::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Berikan data pencarian dan maps ke view
        return view('admin.maps.index', compact('maps', 'search', 'perPage'));
    }


    // Menampilkan form untuk membuat map baru
    public function create()
    {
        return view('admin.maps.create');
    }

    // Menyimpan map baru ke database
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'zoom' => 'required|integer',
            'description' => 'nullable|string',
            'map_type' => 'required|string',
            'polygon' => 'required|string',
            'file' => 'nullable|file|max:100000',
        ]);

        // Simpan data peta ke database
        $mapData = $request->all();

        // Jika ada file yang diupload
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Simpan file dan dapatkan path-nya
            $path = $file->store('uploads', 'public');

            // Simpan path file dalam data peta
            $mapData['file_path'] = $path; // Gantilah 'file_path' dengan kolom yang sesuai di tabel Anda
        }

        Map::create($mapData);

        return redirect()->route('admin.maps.index')->with('success', 'Map created successfully.');
    }

    // Menampilkan detail map
    public function show(Map $map)
    {
        return view('admin.maps.show', compact('map'));
    }

    // Menampilkan form untuk mengedit map
    public function edit(Map $map)
    {
        return view('admin.maps.edit', compact('map'));
    }

    // Memperbarui data map di database
    public function update(Request $request, Map $map)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'zoom' => 'required|integer',
            'description' => 'nullable|string',
            'map_type' => 'required|string',
            'polygon' => 'required|string',
        ]);

        $map->update($request->all());

        return redirect()->route('admin.maps.index')->with('success', 'Map updated successfully.');
    }

    // Menghapus map dari database
    public function destroy(Map $map)
    {
        $map->delete();

        return redirect()->route('admin.maps.index')->with('success', 'Map deleted successfully.');
    }

    public function saveJson(Request $request, $id)
    {
        Log::info("Save JSON called for map ID: $id");
        // Temukan map berdasarkan ID
        $map = Map::findOrFail($id);

        // Ambil data polygon dari map
        $polygonData = $map->polygon;

        // Tentukan nama file dan isi konten
        $fileName = $map->name . '.json';
        $fileContent = json_encode([
            'type' => 'FeatureCollection',
            'features' => [[
                'type' => 'Feature',
                'geometry' => json_decode($polygonData),
                'properties' => [
                    'name' => $map->name,
                ],
            ]]
        ], JSON_PRETTY_PRINT);

        // Simpan file di storage lokal
        Storage::disk('local')->put($fileName, $fileContent);

        // Kembalikan file untuk diunduh
        return Response::download(storage_path('app/' . $fileName))->deleteFileAfterSend(true);
    }
    public function handle($request, Closure $next)
{
    Log::info('Request Method:', ['method' => $request->method()]);

    return $next($request);
}

public function downloadJson($id)
{
    // Temukan data berdasarkan ID
    $map = Map::findOrFail($id);

    // Decode string polygon menjadi objek JSON
    $polygonData = json_decode($map->polygon, true); // Ubah menjadi array asosiatif

    // Buat struktur JSON yang diinginkan
    $jsonData = [
        'type' => 'FeatureCollection',
        'features' => [
            [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => $polygonData['features'][0]['geometry']['coordinates'], // Ambil koordinat dari data polygon
                ],
                'properties' => [
                    'name' => $map->name,
                    'description' => $map->description,
                ],
            ],
        ],
    ];

    // Konversi data menjadi JSON yang terformat
    $jsonDataFormatted = json_encode($jsonData, JSON_PRETTY_PRINT);

    // Tentukan nama file JSON
    $fileName = 'map_' . $map->id . '.json';

    // Kembalikan file JSON untuk diunduh
    return response()->streamDownload(function () use ($jsonDataFormatted) {
        echo $jsonDataFormatted;
    }, $fileName, [
        'Content-Type' => 'application/json',
    ]);
    }
}
