<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Devuelve un formulario donde se crearan los proyectos por parte de las empresas.
     * @return \Illuminate\Contracts\View\View
     */
    public function create(){
        return view('projects.create');
    }

    public function store(Request $request){
        // Validamos los datos del formulario
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        // Creamos el proyecto y le asignamos el identificador de la empresa que lo ha creado
        Project::create([
            'company_id' => auth()->id(), // Asumimos que el usuario autenticado es una empresa
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => true, // Por defecto el proyecto se crea como activo
        ]);

        // Redirigimos a la página de dashboard con un mensaje de éxito
        return redirect()->route('dashboard')->with('success', '¡Proyecto publicado con éxito!');
    }

    /**
     * Muestra el formulario para editar el proyecto
     * @param Project $project
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Project $project){
        // Verificamos que el proyecto pertenece a la empresa autenticada
        if ($project->company_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este proyecto.');
        }

        return view('projects.edit', compact('project'));
    }

    /**
     * Guarda los cambios del proyecto en la base de datos
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Project $project){
        // Verificamos que el proyecto pertenece a la empresa autenticada
        if ($project->company_id !== auth()->id()) {
            abort(403, 'No tienes permiso para actualizar este proyecto.');
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            // $request->has() devuelve true si el checkbox está marcado, false si no
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('dashboard')->with('status', '¡Proyecto actualizado con éxito!');
    }
    /**
     ** Elimina el proyecto de la base de datos
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Project $project){
        // Verificamos que el proyecto pertenece a la empresa autenticada
        if ($project->company_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este proyecto.');
        }

        $project->delete();

        return redirect()->route('dashboard')->with('status', '¡Proyecto eliminado con éxito!');
    }

    /**
     * Muestra la lista de proyectos activos a los que un usuario se puede postular
     * @return \Illuminate\Contracts\View\View
     */
    public function index(){
        // Recuperamos todos los proyectos junto con la empresa que los creó.
        // Filtramos solo los activos y los ordenamos por los más recientes.
        $projects = Project::with('company')
            ->where('is_active', true)
            ->latest()
            ->paginate(9); // Paginamos de 9 en 9

        return view('projects.index', compact('projects'));
    }

    /**
     * Verifica si el usuario que esta postulando a un proyecto tiene CV o no
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apply(Project $project)
    {
        $user = auth()->user();

        $profile = $user->profile;

        // Validamos si es un estudiante y si tiene el CV subido
        if (!$profile || empty($profile->cv_pdf_path)) {
            // Bloqueamos la acción y redirigimos con un Flash Message de error
            return redirect()->back()->with('error', 'Debes subir tu CV en tu perfil antes de poder postularte a un proyecto.');
        }

        return redirect()->route('projects.index')->with('success', '¡Te has postulado al proyecto con éxito!');
    }
    /**
     * Summary of show
     * @param Project $project
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Project $project)
    {
        $isOwner = auth()->check() && auth()->id() === $project->company_id;

        $pendingApplicants = collect();
        $acceptedApplicants = collect();

        // Solo cargamos los postulantes si el usuario autenticado es la empresa dueña
        if ($isOwner) {
            // Usamos la relación ya definida y filtramos por el status del pivot
            $pendingApplicants = $project->applicants()
                                         ->wherePivot('status', 'pending')
                                         ->with('profile') // Cargamos el perfil del estudiante (para el CV)
                                         ->get();

            $acceptedApplicants = $project->applicants()
                                          ->wherePivot('status', 'accepted')
                                          ->with('profile')
                                          ->get();
        }

        return view('projects.show', compact('project', 'isOwner', 'pendingApplicants', 'acceptedApplicants'));
    }

    /**
     * Muestra la lista de postulaciones de un proyecto en especifico, solo accesible para la empresa que lo creó
     * al rechazar a un alumno que ha postulado deja de aparecer en la lista de alumnos postulados
     * @param Project $project
     * @return \Illuminate\Contracts\View\View
     */
    public function applicants(Project $project){
        // Seguridad: Verificar que el proyecto pertenece a la empresa autenticada
        if ($project->company_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver los postulantes de este proyecto.');
        }

        // Recuperamos los postulantes, pero FILTRAMOS para que no traiga a los 'rejected'
        $applicants = $project->applicants()
                              ->wherePivot('status', '!=', 'rejected')
                              ->with('profile')
                              ->get();

        return view('projects.applicants', compact('project', 'applicants'));
    }
}
