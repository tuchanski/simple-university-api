<?php

namespace App\Repositories\Impl;

use App\Models\Course;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseRepository implements BaseRepositoryInterface
{

    public function create(array $attributes)
    {
        return Course::create($attributes);
    }

    public function findById(int $id)
    {
        try {
            return Course::query()->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return null;
        }
    }

    public function findAll(): LengthAwarePaginator
    {
        return Course::query()->paginate(getenv('PAGINATION_SIZE'));
    }

    public function delete(int $id): bool
    {
        $course = Course::query()->findOrFail($id);
        return $course->delete();
    }

    public function existsById(int $id): bool
    {
        return Course::query()->where('id', $id)->exists();
    }

    public function count(): int
    {
        return Course::query()->count();
    }

    public function findAllByProfessorId(int $professorId): LengthAwarePaginator {
        return Course::query()->where('professor_id', $professorId)->paginate(getenv('PAGINATION_SIZE'));
    }

}
