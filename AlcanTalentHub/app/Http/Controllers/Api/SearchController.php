<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class SearchController extends Controller
{
    public function searchOffers(Request $request)
    {
        // Obtenemos lo que el usuario escribió en el buscador
        $query = $request->input('q');

        // Buscamos en la base de datos (filtrando por título o descripción)
        $offers = Project::query()
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        // Devolvemos los datos en formato JSON para que JavaScript los pueda leer
        return response()->json([
            'status' => 'success',
            'data' => $offers
        ]);
    }
}
