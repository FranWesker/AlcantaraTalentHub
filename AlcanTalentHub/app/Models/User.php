<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_validated',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_validated' => 'boolean',
        ];
    }

    // Relacion: Un estudiante tiene un perfil
    public function profile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    // Relacion: Un estudiante tiene muchas habilidades
    public function skills(){
        return $this->belongsToMany(Skill::class,);
    }

    //Relacion: Una empresa publica muchos proyectos
    public function publishedProjects(){
        return $this->hasMany(Project::class, 'company_id');
    }

    // Relacion: Un estudiante se postula a muchos proyectos
    public function applications(){
        return $this->belongsToMany(Project::class, 'applications', 'student_id', 'project_id')
                    ->withPivot('status')
                    ->withTimestamps()
                    ->using(Application::class);
    }

    public function isStudent()
    {
        // Aquí debes ajustar la lógica según cómo determines el rol de estudiante en tu aplicación
        // Ajusta esto a la lógica real de tu base de datos
        return $this->role === 'estudiante';
    }
    public function isCompany()
    {
        // Aquí debes ajustar la lógica según cómo determines el rol de empresa en tu aplicación
        // Ajusta esto a la lógica real de tu base de datos
        return $this->role === 'empresa';
    }
}
