<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Collection;

interface StudentService
{
    public function createStudent(array $data): Student;
    public function getAllStudents() : Collection;
    public function getStudentById(int $id) : ?Student;
    public function updateStudentById(int $id, array $data): Student;
    public function deleteStudentById(int $id): void;
}
