<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determina si el usuario puede ver el modelo Proyecto
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function view(User $user, Project $project): bool
    {
        // Si es un estudiante, comprobamos si tiene una solicitud rechazada para este proyecto
        if ($user->role === 'student') {
            $isRejected = $project->applications()
                ->where('user_id', $user->id)
                ->where('status', 'rejected')
                ->exists();

            if ($isRejected) {
                // Retorna false automáticamente lanza un error 403 Forbidden
                return false;
            }
        }

        // Para administradores, empresas o estudiantes no rechazados, permitimos el acceso
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}
