<?php

namespace App\Repositories;

use App\Models\Professor;
use  \Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfessorRepository
{

    public function findAll(): Collection
    {
        return Professor::all();
    }

    public function findById(int $id): ?Professor {
        try {
            return Professor::query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function findProfessorByEmail(string $email): ?Professor {
        return Professor::query()->where('email', $email)->first();
    }

    public function findProfessorByCpf(string $cpf): ?Professor {
        return Professor::query()->where('cpf', $cpf)->first();
    }

    public function create(array $attributes): ?Professor {
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

    public function existsByEmail(string $email): bool {
        return Professor::query()->where('email', $email)->exists();
    }

    public function existsByCpf(string $cpf): bool {
        return Professor::query()->where('cpf', $cpf)->exists();
    }


}
