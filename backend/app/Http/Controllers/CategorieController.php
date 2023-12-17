<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCategorieRequest;
use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CategorieController extends Controller
{
    public function index(Request $request)
{
    $query = Categorie::query();

    if ($request->filled('id')) {
        $query->where('id', $request->input('id'));
    }

    if ($request->filled('vcard')) {
        $query->where('vcard', $request->input('vcard'));
    }

    if ($request->filled('sortField') && $request->filled('sortOrder')) {
        $query->orderBy($request->input('sortField'), $request->input('sortOrder'));
    }

    $categories = $query->paginate(10);

    return response()->json($categories, 200);
}

public function indexSend(Request $request)
    {
        $categoryNames = Categorie::select('name')->distinct()->get()->pluck('name')->toArray();

        $perPage = 10;
        $page = $request->get('page', 1);
        if ($page > count($categoryNames) or $page < 1) { $page = 1; }
        $offset = ($page * $perPage) - $perPage;

        $categories = new LengthAwarePaginator(array_slice($categoryNames, $offset, $perPage, true), count($categoryNames), $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]);

        return response()->json($categories, 200);
    }

    public function show(Categorie $category)
    {
        return new CategorieResource($category);
    }

    public function update(UpdateCategorieRequest $request)
    {
        $category = Categorie::where('id', $request->id)->first();
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        $dataToSave = $request->validated();

        $category->fill($dataToSave);

        // If there's any specific logic related to categories, add it here

        $category->save();

        return new CategorieResource($category);
    }
}