<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Skill;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_path',
        'github_url',
        'linkedin_url',
        'cv_pdf_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un perfil de estudiante tiene muchas habilidades (Skills).
     */
    public function skills()
    {
        return $this->belongsToMany(
            Skill::class,
            'skill_user', // Nombre de la tabla intermedia
            'user_id',    // Clave foránea en la tabla intermedia referenciando al estudiante/usuario
            'skill_id',   // Clave foránea en la tabla intermedia referenciando a la habilidad
            'user_id',    // Clave local en la tabla student_profiles (asumiendo que tienes una columna user_id)
            'id'          // Clave local en la tabla skills
        );
    }
}
