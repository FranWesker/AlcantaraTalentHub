<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Skill;

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
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request){
        $user = $request->user();

        // Construimos la consulta
        $projectsQuery = Project::with('company')
            ->where('is_active', true); // Solo mostramos proyectos activos

        // Corregido: Usamos el método isStudent() del modelo User
        if ($user && $user->isStudent()) {
             $projectsQuery->hideRejectedForStudent($user);
        }

        $projects = $projectsQuery->latest()->paginate(10);

        // Obtenemos todas las skills para mostrarlas en el filtro ordenadas alfabeticamente
        $skills = Skill::orderBy('name', 'asc')->get();

        return view('projects.index', compact('projects', 'skills'));
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
    public function show(Project $project){
        // Si la política retorna false (ej. estudiante rechazado), aborta con un 403.
        Gate::authorize('view', $project);

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
                          ->with(['profile', 'skills'])
                          ->get();

        return view('projects.applicants', compact('project', 'applicants'));
    }
    /**
     * Busca proyectos mediante AJAX y devuelve JSON.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Obtenemos el texto que el usuario escribió en el input (query)
        $searchTerm = $request->input('query');

        // Si la búsqueda está vacía, podemos devolver un arreglo vacío
        if (empty($searchTerm)) {
            return response()->json([]);
        }

        // Realizamos la consulta a la base de datos
        // Usamos 'with('company')' para traer la información de la empresa y evitar errores en JavaScript
        $projects = Project::with('company')
            ->where('title', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
            ->orWhereHas('company', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            })
            ->latest() // Ordenamos por los más recientes
            ->get();

        // Devolvemos los datos en formato JSON para que Fetch API los pueda procesar
        return response()->json($projects);
    }
}
