<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

        return view('template.index', compact('maps', 'search', 'perPage'));
    }

    public function category()
    {
        return view('template.categori');
    }

    public function about()
    {
        return view('template.about');
    }

    public function latest_news()
    {
        return view('template.latest_news');
    }

    public function contact()
    {
        return view('template.contact');
    }
    
}
