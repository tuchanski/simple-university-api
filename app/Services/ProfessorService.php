<?php

namespace App\Services;

use App\Models\Professor;
use App\Repositories\ProfessorRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ProfessorService
{

    private ProfessorRepository $professorRepository;

    public function __construct()
    {
        $this->professorRepository = new ProfessorRepository();
    }

    public function getAllProfessors() : Collection {
        return $this->professorRepository->findAll();
    }

    public function getProfessor(int $id) : ?Professor {
        try {
            return $this->professorRepository->findById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

}
