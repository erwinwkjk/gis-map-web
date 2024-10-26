<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'zoom' => 'required|integer',
            'description' => 'nullable|string',
            'map_type' => 'required|string',
            'polygon' => 'required|string',
        ]);

        Map::create($request->all());

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
}
