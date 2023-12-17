<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Categorie;
use App\Http\Resources\CategorieResource;

use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index($user){
        $categorie = Categorie::where('vcard',$user)->get();
        if ($categorie) {
            return response ()->json(['data'=>$categorie],200); 
        } else {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }
    public function show(){
        $categorie = Categorie::paginate(8);
        return response ()->json(['data'=>$categorie],200);
    }
    
}
