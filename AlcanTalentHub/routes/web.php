<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/welcom', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('bienvenido');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Rutas para proyectos
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Rutas para mostrar proyectos a los usuarios
    Route::get('/proyectos', [ProjectController::class, 'index'])->name('projects.index');

    // Curriculum de Alumno
    Route::post('/profile/cv/upload', [ProfileController::class, 'uploadCv'])->name('profile.cv.upload');
    Route::delete('/profile/cv/delete', [ProfileController::class, 'deleteCv'])->name('profile.cv.delete');

    // Vista del proyecto por parte del estudiante
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    // Estudiante postula a un proyecto
    Route::post('/projects/{project}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    // Empresa gestiona las postulaciones
    Route::get('/company/applications', [ApplicationController::class, 'index'])->name('applications.index');
    // Empresa acepta o rechaza una postulacion
    Route::patch('/projects/{project}/students/{student_id}/status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
});

require __DIR__.'/auth.php';
