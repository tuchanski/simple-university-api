<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CourseService
{
    public function createCourse(array $data): Course;

    public function getAllCourses() : LengthAwarePaginator;

    public function getCourseById(int $id): ?Course;

    public function updateCourseById(int $id, array $data): Course;

    public function deleteCourseById(int $id): void;

    public function enrollStudent(int $courseId, array $data): void;
}
