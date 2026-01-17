<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'title',
        'description',
        'language',
        'level',
        'status',
        'start_date',
        'end_date',
        'professor_id',
    ];

    public function professor() {
        return $this->belongsTo(Professor::class);
    }

    public function students() {
        return $this->hasMany(Student::class);
    }
}
