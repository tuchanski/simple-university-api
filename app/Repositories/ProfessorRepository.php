<?php

namespace App\Repositories;

use App\Models\Professor;
use  \Illuminate\Database\Eloquent\Collection;

class ProfessorRepository
{
    public function __construct()
    {
        //
    }

    public function findAll(): Collection
    {
        return Professor::all();
    }

    public function findById(int $id): Professor {
        return Professor::query()->findOrFail($id);
    }

    public function save(Professor $professor) : Professor {
        $professor->save();
        return $professor;
    }

    public function delete(Professor $professor) : bool {
        return $professor->delete();
    }

    public function count(): int {
        return Professor::query()->count();
    }

    public function existsById(int $id): bool {
        return Professor::query()->where('id', $id)->exists();
    }


}
