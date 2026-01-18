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

    public function findProfessorByEmail(string $email): ?Professor {
        return Professor::query()->where('email', $email)->first();
    }

    public function findProfessorByCpf(string $cpf): ?Professor {
        return Professor::query()->where('cpf', $cpf)->first();
    }

    public function create(array $attributes): Professor {
        return Professor::create($attributes);
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
