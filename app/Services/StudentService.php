<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface StudentService
{
    public function createStudent(array $data): Student;
    public function getAllStudents() : LengthAwarePaginator;
    public function getStudentById(int $id) : ?Student;
    public function updateStudentById(int $id, array $data): Student;
    public function deleteStudentById(int $id): void;
}
