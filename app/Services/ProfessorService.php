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

    public function createProfessor(array $data) : Professor
    {
        if ($this->professorRepository->findProfessorByEmail($data['email']) !== null)
        {
            throw new EmailAlreadyRegisteredException();
        }

        if ($this->professorRepository->findProfessorByCpf($data['cpf']) !== null)
        {
            throw new CpfAlreadyRegisteredException();
        }

        return $this->professorRepository->create($data);
    }

    public function getAllProfessors() : Collection
    {
        return $this->professorRepository->findAll();
    }

    public function getProfessorById(int $id) : ?Professor
    {
        $professor =  $this->professorRepository->findById($id);

        if (is_null($professor)) {
            throw new ProfessorNotFoundException();
        }

        return $professor;
    }

    public function updateProfessorById(int $id, array $data): Professor
    {
        $professor = $this->professorRepository->findById($id);

        if (is_null($professor)) {
            throw new ProfessorNotFoundException();
        }

        if (
            array_key_exists('email', $data) &&
            $data['email'] !== null &&
            $data['email'] !== $professor->email
        ) {
            if ($this->professorRepository->existsByEmail($data['email'])) {
                throw new EmailAlreadyRegisteredException();
            }

            $professor->email = $data['email'];
        }

        if (
            array_key_exists('cpf', $data) &&
            $data['cpf'] !== null &&
            $data['cpf'] !== $professor->cpf
        ) {
            if ($this->professorRepository->existsByCpf($data['cpf'])) {
                throw new CpfAlreadyRegisteredException();
            }

            $professor->cpf = $data['cpf'];
        }

        if (
            array_key_exists('birth_date', $data) &&
            $data['birth_date'] !== null &&
            $data['birth_date'] !== $professor->birth_date?->toDateString()
        ) {
            $professor->birth_date = $data['birth_date'];
        }

        if (
            array_key_exists('gender', $data) &&
            $data['gender'] !== null &&
            $data['gender'] !== $professor->gender
        ) {
            $professor->gender = $data['gender'];
        }

        if (
            array_key_exists('phone', $data) &&
            $data['phone'] !== null &&
            $data['phone'] !== $professor->phone
        ) {
            $professor->phone = $data['phone'];
        }

        if (
            array_key_exists('address', $data) &&
            $data['address'] !== null &&
            $data['address'] !== $professor->address
        ) {
            $professor->address = $data['address'];
        }

        if (array_key_exists('profile_picture', $data)) {
            $professor->profile_picture = $data['profile_picture'];
        }

        $professor->save();

        return $professor;
    }

    public function deleteProfessor(int $id) : void
    {
        $professor = $this->professorRepository->findById($id);

        if ($professor == null) {
            throw new ProfessorNotFoundException();
        }

        $this->professorRepository->delete($professor);
    }

}
