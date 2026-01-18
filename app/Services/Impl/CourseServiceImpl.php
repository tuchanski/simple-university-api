<?php

namespace App\Services\Impl;

use App\Models\Course;
use App\Models\Professor;
use App\Repositories\Impl\CourseRepository;
use App\Services\CourseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class CourseServiceImpl implements CourseService
{

    private CourseRepository $courseRepository;

    public function __construct()
    {
        $this->courseRepository = new CourseRepository();
    }

    public function createCourse(array $data): Course
    {

        if (array_key_exists('professor_id', $data)) {
            $professor = Professor::query()->find($data['professor_id']);

            if (is_null($professor)) {
                throw new ModelNotFoundException('Professor not found');
            }
        }

        return $this->courseRepository->create($data);
    }

    public function getAllCourses(): Collection
    {
        return $this->courseRepository->findAll();
    }

    public function getCourseById(int $id): ?Course
    {
        $course = $this->courseRepository->findById($id);

        if (is_null($course)) {
            throw new ModelNotFoundException();
        }

        return $course;
    }

    public function updateCourseById(int $id, array $data): Course
    {
        $course = $this->getCourseById($id);

        if (is_null($course)) {
            throw new ModelNotFoundException();
        }

        $course->update($data);

        return $course;
    }

    public function deleteCourseById(int $id): void
    {
        $course = $this->courseRepository->findById($id);

        if (is_null($course)) {
            throw new ModelNotFoundException();
        }

        $this->courseRepository->delete($id);
    }
}
