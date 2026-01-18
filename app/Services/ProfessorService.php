<?php

namespace App\Services;

use App\Repositories\ProfessorRepository;
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

}
