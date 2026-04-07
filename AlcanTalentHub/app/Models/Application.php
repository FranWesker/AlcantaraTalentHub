<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Application extends Pivot
{
    protected $table = 'applications';

    protected $fillable = [
        'student_id',
        'project_id',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
