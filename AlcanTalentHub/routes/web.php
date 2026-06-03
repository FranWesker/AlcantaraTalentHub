<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StudentSkillController;
use Illuminate\Support\Facades\Route;

//* RUTA De inicio claramente no necesita autenticación
Route::get('/', function () {
    return view('bienvenido');
});

//* Rutas para los usuarios autenticados aqui no ponemos todas las rutas, hay otras que necesitan ser un tipo de usuario definido y se definen en el AppServiceProvider con las políticas de acceso, luego se agrupan en este archivo para mantener un orden claro y evitar confusiones.
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard general
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Gestión del perfil general
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Búsqueda y visualización de proyectos
    //! El search va primero para que no entre en conflicto con el show de proyectos, si ponemos el show antes, al intentar acceder a /projects/search, Laravel pensará que "search" es un ID de proyecto y lanzará un error 404.
    Route::get('/projects/search', [ProjectController::class, 'search'])->name('projects.search');
    Route::get('/proyectos', [ProjectController::class, 'index'])->name('projects.index');

    //* RUTAS EXCLUSIVAS PARA EMPRESAS Y ADMIN
    Route::middleware('can:manage-company-features')->group(function () {
        // Creación y edición de proyectos
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        // Gestión de postulaciones por parte de la empresa
        Route::get('/company/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/projects/{project}/applicants', [ProjectController::class, 'applicants'])->name('projects.applicants');
        Route::patch('/projects/{project}/students/{student_id}/status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
        Route::post('/projects/{project}/accept/{student}', [ApplicationController::class, 'accept'])->name('applications.accept');

        // Notificaciones de empresa
        Route::patch('/notifications/{id}/read', function($id) {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            return back();
        })->name('notifications.read');
    });

    //* RUTAS EXCLUSIVAS PARA ESTUDIANTES Y ADMIN
    Route::middleware('can:manage-student-features')->group(function () {
        // Gestión del Curriculum (CV)
        Route::post('/profile/cv/upload', [ProfileController::class, 'uploadCv'])->name('profile.cv.upload');
        Route::delete('/profile/cv/delete', [ProfileController::class, 'deleteCv'])->name('profile.cv.delete');

        // Gestión de habilidades (Skills)
        Route::get('/profile/skills', [StudentSkillController::class, 'index'])->name('profile.skills');
        Route::post('/profile/skills', [StudentSkillController::class, 'update'])->name('profile.skills.update');

        // Postulación a proyectos
        Route::post('/projects/{project}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    });

    // La ruta 'show' ({project}) DEBE ir al final para que no intercepte rutas estáticas como /projects/create
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

});

// Autenticación por defecto de Laravel Breeze
require __DIR__.'/auth.php';
