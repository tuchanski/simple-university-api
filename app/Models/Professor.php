<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    /** @use HasFactory<\Database\Factories\ProfessorFactory> */
    use HasFactory;

    protected $table = 'professors';

    protected $fillable = [
        'name',
        'email',
        'cpf',
        'birth_date',
        'gender',
        'phone',
        'address',
        'profile_picture',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'profile_picture',
    ];

    public function courses() {
        return $this->hasMany(Course::class);
    }
}
