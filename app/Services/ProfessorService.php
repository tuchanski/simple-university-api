<?php

namespace App\Services;

use App\Models\Professor;
use Illuminate\Support\Collection;

interface ProfessorService
{
    public function createProfessor(array $data): Professor;

    public function getAllProfessors(): Collection;

    public function getProfessorById(int $id): ?Professor;

    public function updateProfessorById(int $id, array $data): Professor;

    public function deleteProfessorById(int $id): void;
}
