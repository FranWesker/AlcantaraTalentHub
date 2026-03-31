<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validamos los campos básicos y la selección virtual del formulario
        $request->validate([
            'account_type' => ['required', 'in:student,company'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],

            // Redes sociales (solo validadas si se envían)
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
        ]);

        // Mapeamos lo que llega del formulario (inglés) a lo que acepta la BD (español)
        $roleMap = [
            'student' => 'estudiante',
            'company' => 'empresa',
        ];

        // Creamos al usuario general en la base de datos
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $roleMap[$request->account_type], // Aquí traducimos y guardamos el rol
        ]);


        //Solo creamos el perfil extra si es un estudiante.
        if ($request->account_type === 'student') {
            $user->profile()->create([
                'github_url' => $request->github_url,
                'linkedin_url' => $request->linkedin_url,
            ]);
        }

        //Autenticación y redirección
        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
