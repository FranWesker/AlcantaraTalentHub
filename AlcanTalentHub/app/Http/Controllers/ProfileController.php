<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Sube y actualiza el CV del estudiante.
     */
    public function uploadCv(\Illuminate\Http\Request $request)
    {
        // Validamos que el archivo sea obligatoriamente un PDF y no pese más de 2MB
        $request->validate([
            'cv_file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        // Comprobamos si el usuario tiene perfil de estudiante para guardar la ruta
        $user = auth()->user();

        if ($user->profile) {
            // Guarda el archivo en la carpeta storage/app/public/cvs
            // Se guardará con un nombre único generado automáticamente
            $path = $request->file('cv_file')->store('cvs', 'public');

            // Aquí deberías guardar la variable $path en tu base de datos
            // Por ejemplo: $user->profile->update(['cv_path' => $path]);

            return redirect()->route('dashboard')->with('status', '¡Tu currículum se ha subido correctamente!');
        }

        return redirect()->route('dashboard')->withErrors(['cv_file' => 'No tienes un perfil de estudiante válido para subir un CV.']);
    }
}
