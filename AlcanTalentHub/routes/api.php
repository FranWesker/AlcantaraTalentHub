<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SearchController;

Route::get('/user', function (Request $request) {
    // Ruta a la que llamará nuestro JavaScript: /api/offers/search?q=termino
    Route::get('/offers/search', [SearchController::class, 'searchOffers']);
    return $request->user();
})->middleware('auth:sanctum');
