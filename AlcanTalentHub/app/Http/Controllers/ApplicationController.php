<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Project;
use App\Models\User;
use App\Notifications\StudentAppliedToProject;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Un estudiante postula a un proyecto
     * * Evitamos las postulaciones dublicadas
     * ! Verificamos que el estudiante tenga su CV subido antes de postularse
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Project $project){
        $student = auth()->user();

        //  Verificar que el usuario autenticando que el usuario estudiante tiene el CV subido a su perfil
        if (!$student->profile || empty($student->profile->cv_pdf_path)) {
            return back()->with('error', 'Debes subir tu CV antes de postularte a un proyecto.');
        }

        // Evitar postulación duplicada
        if ($project->applicants()->where('student_id', $student->id)->exists()) {
            return back()->with('error', 'Ya te has postulado a este proyecto.');
        }

        // Adjuntar al estudiante con estado 'pending'
        $project->applicants()->attach($student->id, ['status' => 'pending']);

        // Enviar la notificación a la empresa (dueña del proyecto)
        $project->company->notify(new StudentAppliedToProject($student, $project));

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

    /**
     * Metodo para aceptar la postulacion de un estudiante
     * @param Project $project
     * @param User $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Project $project, User $student)
    {
        // Autorización: Asegurar que solo el dueño del proyecto pueda aceptar
        abort_if(auth()->id() !== $project->company_id, 403, 'No estás autorizado para realizar esta acción.');

        // Actualizar el estado en la tabla pivote a 'accepted'
        $project->applicants()->updateExistingPivot($student->id, ['status' => 'accepted']);

        return back()->with('success', 'Estudiante aceptado exitosamente.');
    }
}
