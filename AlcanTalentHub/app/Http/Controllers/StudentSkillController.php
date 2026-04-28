<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSkillController extends Controller
{
    /**
     * Muestra el formulario con todas las skills disponibles y las skills asignadas al estudiante.
     * @return \Illuminate\Contracts\View\View
     */
    public function index(){
        // Obtenemos todas las skills creadas en la base de datos
        $skills = Skill::all();

        // Obtenemos un array con los ids de las skills que el estudiante tiene asignadas
        $userSkills = auth()->user()->skills()->pluck('id')->toArray();

        return view('profile.skills', compact('skills', 'userSkills'));
    }

    /**
     * Sincroniza las skills seleccionadas con el perfil del usuario.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request){
        // Validamos que lo que envíe el usuario sea un array y que los IDs existan en la tabla skills
        $request->validate([
            'skills' => ['array'],
            'skills.*' => ['exists:skills,id'],
        ]);

        // El método sync() se encarga de actualizar la tabla pivote (skill_user)
        Auth::user()->skills()->sync($request->input('skills', []));

        // Redirigimos de vuelta con un mensaje de éxito
        return back()->with('status', 'skills-updated');
    }
}
