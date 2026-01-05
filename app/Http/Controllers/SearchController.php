<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $sanpham = SanPham::where('tensanpham', 'LIKE', "%{$query}%")
            ->orWhere('dongia', 'LIKE', "%{$query}%")
            ->paginate(12);

        return view('frontend.search_results', compact('sanpham'));
    }
}