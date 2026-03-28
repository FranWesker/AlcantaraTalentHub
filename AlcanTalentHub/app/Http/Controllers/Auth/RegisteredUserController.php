<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
        // 1. Validamos los campos básicos y la selección virtual del formulario
        $request->validate([
            'account_type' => ['required', 'in:student,company'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],

            // Redes sociales (solo validadas si se envían)
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
        ]);

        // 2. Creamos al usuario general en la base de datos
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. LA MAGIA: Solo creamos el perfil extra si es un estudiante.
        // Las empresas se quedan solo con su registro en la tabla 'users'.
        if ($request->account_type === 'student') {
            $user->studentProfile()->create([
                'github_url' => $request->github_url,
                'linkedin_url' => $request->linkedin_url,
            ]);
        }

        // 4. Autenticación y redirección
        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
