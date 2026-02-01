<?php

namespace App\Services\Impl;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\InvalidGenderException;
use App\Exceptions\StudentNotFoundException;
use App\Helpers\Utilities;
use App\Models\Student;
use App\Repositories\Impl\StudentRepository;
use App\Services\StudentService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StudentServiceImpl implements StudentService
{


    private $studentRepository;

    public function __construct()
    {
        $this->studentRepository = new StudentRepository();
    }

    public function createStudent(array $data): Student
    {
        if ($this->studentRepository->existsByEmail($data['email'])) {
            throw new EmailAlreadyRegisteredException();
        }

        if (!Utilities::isEmailValid($data['email'])) {
            throw new InvalidEmailException();
        }

        if (!Utilities::isGenderValid($data['gender'])) {
            throw new InvalidGenderException();
        }

        return $this->studentRepository->create($data);
    }

    public function getAllStudents(): LengthAwarePaginator
    {
        return $this->studentRepository->findAll();
    }

    public function getStudentById(int $id): ?Student
    {
        $student = $this->studentRepository->findById($id);

        if (is_null($student)) {
            throw new StudentNotFoundException();
        }

        return $student;
    }

    public function updateStudentById(int $id, array $data): Student
    {
        $student = $this->studentRepository->findById($id);

        if (is_null($student)) {
            throw new StudentNotFoundException();
        }

        if (array_key_exists('name', $data) &&
            $data['name'] !== $student->name &&
            !is_null($data['name']))
        {
            $student->name = $data['name'];
        }

        if (array_key_exists('email', $data) &&
            $data['email'] !== $student->email &&
            !is_null($data['email']
            )
        )
        {
            if ($this->studentRepository->existsByEmail($data['email'])) {
                throw new EmailAlreadyRegisteredException();
            }

            if (!Utilities::isEmailValid($data['email'])) {
                throw new InvalidEmailException();
            }

            $student->email = $data['email'];
        }

        if (
            array_key_exists('birth_date', $data) &&
            $data['birth_date'] !== null &&
            $data['birth_date'] !== $student->birth_date?->toDateString()
        ) {
            $student->birth_date = $data['birth_date'];
        }

        if (
            array_key_exists('phone', $data) &&
            $data['phone'] !== null &&
            $data['phone'] !== $student->phone
        ) {
            $student->phone = $data['phone'];
        }

        if (array_key_exists('gender', $data) &&
            $data['gender'] !== $student->gender &&
            !is_null($data['gender'])
        )
        {
            if (!Utilities::isGenderValid($data['gender'])) {
                throw new InvalidGenderException();
            }

            $student->gender = $data['gender'];
        }

        if (
            array_key_exists('address', $data) &&
            $data['address'] !== null &&
            $data['address'] !== $student->address
        ) {
            $student->address = $data['address'];
        }

        $student->save();

        return $student;
    }

    public function deleteStudentById(int $id): void
    {
        $student = $this->studentRepository->findById($id);

        if (is_null($student)) {
            throw new StudentNotFoundException();
        }

        $student->delete();
    }
}
