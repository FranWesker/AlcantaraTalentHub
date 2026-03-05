<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
