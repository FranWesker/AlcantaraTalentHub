<?php

use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/projects/search', [ProjectController::class, 'search']);
});
