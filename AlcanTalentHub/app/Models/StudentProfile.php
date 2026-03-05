<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
