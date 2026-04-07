<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
     * Sube y actualiza el CV del usuario. Si ya existe un CV, se reemplaza por el nuevo.
     * @param Request $request
     * @return RedirectResponse
     */
    public function uploadCv(Request $request){
        $request->validate([
            'cv_file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        $user = $request->user();

        $profile = $user->profile;

        if ($profile) {
            // Si ya existe un CV, lo borramos del almacenamiento local (Storage)
            if ($profile->cv_pdf_path && Storage::disk('public')->exists($profile->cv_pdf_path)) {
                Storage::disk('public')->delete($profile->cv_pdf_path);
            }

            // Guardamos el nuevo archivo
            $path = $request->file('cv_file')->store('cvs', 'public');

            // Actualizamos la base de datos usando el campo exacto de tu modelo StudentProfile
            $profile->update(['cv_pdf_path' => $path]);

            return back()->with('status', 'cv-updated'); // Mensaje de éxito
        }

        return back()->withErrors(['cv_file' => 'No tienes un perfil de estudiante válido para subir un CV.']);
    }

    /**
     * Summary of deleteCv
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteCv(Request $request)
    {
        $profile = $request->user()->profile;

        if ($profile && $profile->cv_pdf_path) {
            // Borramos el archivo físico
            if (Storage::disk('public')->exists($profile->cv_pdf_path)) {
                Storage::disk('public')->delete($profile->cv_pdf_path);
            }

            // Actualizamos la base de datos a null
            $profile->update(['cv_pdf_path' => null]);

            return back()->with('status', 'cv-deleted');
        }

        return back()->withErrors(['cv_file' => 'No se encontró ningún CV para eliminar.']);
    }

}
