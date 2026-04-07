<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Project;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Un estudiante postula a un proyecto
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Project $project){
        $user = auth()->user();

        if ($user->role !== 'estudiante') {
            abort(403, 'Solo estudiantes pueden postularse.');
        }

        // Evitar postulación duplicada usando tu relación
        if ($user->applications()->where('project_id', $project->id)->exists()) {
            return back()->with('error', 'Ya te has postulado a este proyecto.');
        }

        // Usamos attach() porque es una relación belongsToMany (Muchos a Muchos)
        $user->applications()->attach($project->id, ['status' => 'pending']);

        return back()->with('success', 'Te has postulado correctamente.');
    }

    /**
     * Una empresa ve las postulaciones de sus proyectos
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role !== 'empresa') {
            abort(403, 'Acceso denegado.');
        }

        // Usamos la relación 'publishedProjects' y traemos los 'applicants' (estudiantes)
        $projects = $user->publishedProjects()->with('applicants')->get();

        return view('applications.index', compact('projects'));
    }

    /**
     * Empresa acepta/rechaza las postulaciones de los estudiantes
     * @param Request $request
     * @param Application $application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Project $project, $student_id)
    {
        // Seguridad: Verificar que el proyecto pertenece a la empresa autenticada
        if ($project->company_id !== auth()->id()) {
            abort(403, 'No tienes permiso sobre este proyecto.');
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        // Actualizamos la tabla pivote usando updateExistingPivot
        $project->applicants()->updateExistingPivot($student_id, [
            'status' => $request->status
        ]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
