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
        $professor =  $this->professorRepository->findById($id);

        if (is_null($professor)) {
            throw new \Exception("Professor not found");
        }

        return $professor;
    }

    public function createProfessor(array $data) : Professor {

        if ($this->professorRepository->findProfessorByEmail($data['email']) != null) {
            throw new \Exception('Email already registered');
        }

        if ($this->professorRepository->findProfessorByCpf($data['cpf']) != null) {
            throw new \Exception('CPF already registered');
        }

        return $this->professorRepository->create($data);
    }

    public function deleteProfessor(int $id) : void {
        $professor = $this->professorRepository->findById($id);

        if ($professor == null) {
            throw new \Exception('Professor not found');
        }

        $this->professorRepository->delete($professor);
    }

}
