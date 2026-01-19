<?php

namespace App\Services\Impl;

use App\Exceptions\CourseNotFoundException;
use App\Exceptions\InvalidLangException;
use App\Exceptions\InvalidLevelException;
use App\Exceptions\InvalidStatusException;
use App\Exceptions\ProfessorAlreadyEnrolledException;
use App\Exceptions\ProfessorNotEnrolledException;
use App\Exceptions\ProfessorNotFoundException;
use App\Exceptions\StudentAlreadyEnrolledException;
use App\Exceptions\StudentNotEnrolledException;
use App\Exceptions\StudentNotFoundException;
use App\Helpers\Utilities;
use App\Models\Course;
use App\Repositories\Impl\CourseRepository;
use App\Repositories\Impl\ProfessorRepository;
use App\Repositories\Impl\StudentRepository;
use App\Services\CourseService;
use Illuminate\Support\Collection;

class CourseServiceImpl implements CourseService
{

    private CourseRepository $courseRepository;
    private ProfessorRepository $professorRepository;
    private StudentRepository $studentRepository;

    public function __construct()
    {
        $this->courseRepository = new CourseRepository();
        $this->professorRepository = new ProfessorRepository();
        $this->studentRepository = new StudentRepository();
    }

    public function createCourse(array $data): Course
    {

        if (array_key_exists('professor_id', $data)) {
            $professor = $this->professorRepository->findById($data['professor_id']);

            if (is_null($professor)) {
                throw new ProfessorNotFoundException();
            }
        }

        if (array_key_exists('level', $data)) {
            if (!Utilities::isLevelValid($data['level'])) {
                throw new InvalidLevelException();
            }
        }

        if (array_key_exists('status', $data)) {
            if (!Utilities::isStatusValid($data['status'])) {
                throw new InvalidStatusException();
            }
        }

        if (array_key_exists('language', $data)) {
            if (!Utilities::isLangValid($data['language'])) {
                throw new InvalidLangException();
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
            throw new CourseNotFoundException();
        }

        return $course;
    }

    public function updateCourseById(int $id, array $data): Course
    {
        $course = $this->courseRepository->findById($id);

        if (is_null($course)) {
            throw new CourseNotFoundException();
        }

        $course->update($data);

        return $course;
    }

    public function deleteCourseById(int $id): void
    {
        $course = $this->courseRepository->findById($id);

        if (is_null($course)) {
            throw new CourseNotFoundException();
        }

        $this->courseRepository->delete($id);
    }

    public function enrollStudent(int $courseId, array $data): void
    {
        $course = $this->courseRepository->findById($courseId);

        if (is_null($course)) {
            throw new CourseNotFoundException();
        }

        $student = $this->studentRepository->findById($data['student_id']);

        if (is_null($student)) {
            throw new StudentNotFoundException();
        }

        if ($course->students()->where('student_id', $student->id)->exists()) {
            throw new StudentAlreadyEnrolledException();
        }

        $student->courses()->attach($course);
    }

    public function unenrollStudent(int $courseId, array $data): void {
        $course = $this->courseRepository->findById($courseId);

        if (is_null($course)) {
            throw new CourseNotFoundException();
        }

        $student = $this->studentRepository->findById($data['student_id']);

        if (is_null($student)) {
            throw new StudentNotFoundException();
        }

        if (!$course->students()->find($data['student_id'])) {
            throw new StudentNotEnrolledException();
        }

        $student->courses()->detach($course);
    }

    public function getEnrolledStudents(int $courseId): Collection {
        $course = $this->courseRepository->findById($courseId);

        if (is_null($course)) {
            throw new CourseNotFoundException();
        }

        return $course->students()->getResults();
    }

    public function enrollProfessor(int $courseId, array $data): void {
        $course = $this->courseRepository->findById($courseId);

        if (is_null($course)) {
            throw new CourseNotFoundException();
        }

        $professor = $this->professorRepository->findById($data['professor_id']);

        if (is_null($professor)) {
            throw new ProfessorNotFoundException();
        }

        if ($course->professor()->exists()) {
            throw new ProfessorAlreadyEnrolledException();
        }

        $course->professor()->associate($professor);
        $course->save();
    }

    public function unenrollProfessor(int $courseId): void
    {
        $course = $this->courseRepository->findById($courseId);

        if (is_null($course)) {
            throw new CourseNotFoundException();
        }

        if (is_null($course->professor)) {
            throw new ProfessorNotEnrolledException();
        }

        $course->professor()->dissociate();
        $course->save();
    }

}
