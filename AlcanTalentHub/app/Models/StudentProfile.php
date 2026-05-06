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
        /*
         * Usamos belongsToMany para la relación de muchos a muchos.
         * Como la tabla intermedia parece ser 'skill_user', se lo indicamos a Laravel
         * junto con las claves foráneas correctas para que sepa cómo buscarlas
         * usando el 'user_id' que conecta al perfil con el usuario.
         */
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
