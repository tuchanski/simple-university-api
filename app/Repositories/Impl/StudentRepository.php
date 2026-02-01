<?php

namespace App\Repositories\Impl;

use App\Models\Student;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StudentRepository implements BaseRepositoryInterface
{
    public function create(array $attributes) : Student
    {
        return Student::create($attributes);
    }

    public function findById(int $id)
    {
        try
        {
            return Student::query()->findOrFail($id);
        }
        catch (ModelNotFoundException $e)
        {
            return null;
        }
    }

    public function findAll(): LengthAwarePaginator
    {
        return Student::query()->paginate(getenv('PAGINATION_SIZE'));
    }

    public function delete(int $id): bool
    {
        $student = Student::query()->findOrFail($id);
        return $student->delete();
    }

    public function existsById(int $id): bool
    {
        return Student::query()->where('id', $id)->exists();
    }

    public function count(): int
    {
        return Student::query()->count();
    }

    public function existsByEmail(string $email): bool
    {
        return Student::query()->where('email', $email)->exists();
    }
}
