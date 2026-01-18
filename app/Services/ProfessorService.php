<?php

namespace App\Services;

use App\Exceptions\CpfAlreadyRegisteredException;
use App\Exceptions\EmailAlreadyRegisteredException;
use App\Exceptions\ProfessorNotFoundException;
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
            throw new ProfessorNotFoundException();
        }

        return $professor;
    }

    public function createProfessor(array $data) : Professor {

        if ($this->professorRepository->findProfessorByEmail($data['email']) != null) {
            throw new EmailAlreadyRegisteredException();
        }

        if ($this->professorRepository->findProfessorByCpf($data['cpf']) != null) {
            throw new CpfAlreadyRegisteredException();
        }

        return $this->professorRepository->create($data);
    }

    public function deleteProfessor(int $id) : void {
        $professor = $this->professorRepository->findById($id);

        if ($professor == null) {
            throw new ProfessorNotFoundException();
        }

        $this->professorRepository->delete($professor);
    }

}
