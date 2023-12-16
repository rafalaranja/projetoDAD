<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Categorie;

use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index(Request $request)
    {
        $categoryNames = Categorie::select('name')->distinct()->get()->pluck('name')->toArray();
    
        $perPage = 10;
        $page = $request->get('page', 1);
        if ($page > count($categoryNames) or $page < 1) { $page = 1; }
        $offset = ($page * $perPage) - $perPage;
    
        $categories = new LengthAwarePaginator(array_slice($categoryNames, $offset, $perPage, true), count($categoryNames), $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]);
    
        return response()->json($categories, 200);
    }
}
