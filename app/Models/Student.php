<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    protected $hidden = [
        'created_at',
        'updated_at',
        'profile_picture',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

}
