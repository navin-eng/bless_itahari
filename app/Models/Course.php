<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_level',
        'grade_span',
        'slug',
        'duration',
        'semester',
        'requirement',
        'evaluation_system',
        'starting_time',
        'closing_time',
        'image',
        'gallery',
        'status',
        'description',
        'fulldescription',
        'curriculum',
        'rules',
        'admission_procedure',
    ];
}
