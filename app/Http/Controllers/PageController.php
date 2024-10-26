<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;

class PageController extends Controller
{
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

        return view('maps.index', compact('maps', 'search', 'perPage'));
    }

    public function show($id)
    {
        $map = Map::findOrFail($id);

        return view('maps.show', compact('map'));
    }
}
