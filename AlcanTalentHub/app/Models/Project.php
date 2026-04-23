<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relacion: El proyecto pertenece a una empresa
    public function company(){
        return $this->belongsTo(User::class, 'company_id');
    }

    // Relacion: Muchos estudiantes pueden postular a un proyecto
    public function applicants(){
        return $this->belongsToMany(User::class, 'applications', 'project_id', 'student_id')
                    ->withPivot('status')
                    ->withTimestamps()
                    ->using(Application::class);
    }

    /**
     * Relacion con las solicitudes
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Application, Project>
     */
    public function applications(){
        return $this->hasMany(Application::class);
    }

    /**
     * Scope para ocultar proyectos donde el usuario fue rechazado.
     * Solo aplica si el usuario es de tipo 'student'.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User|null $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHideRejectedForStudent($query, $user)
    {
        if (! $user || $user->user_type !== 'student') {
            return $query;
        }

        // ¡IMPORTANTE! Cambiamos 'applications' por 'applicants'
        return $query->whereDoesntHave('applicants', function ($q) use ($user) {
            $q->where('user_id', $user->id) // Asegúrate de que tu tabla pivot use 'user_id'
              ->where('applications.status', 'rejected'); // Especificar la tabla para evitar ambigüedades
        });
    }
}
