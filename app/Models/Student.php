<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'email',
        'birth_date',
        'gender',
        'phone',
        'address',
        'profile_picture',
    ];

    public function courses() {
        return $this->hasMany(Course::class);
    }

}
