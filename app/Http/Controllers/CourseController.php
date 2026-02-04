<?php

namespace App\Http\Controllers;

use App\Enums\Language;
use App\Enums\Level;
use App\Enums\Status;
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
use App\Helpers\GlobalExceptionHandler;
use App\Helpers\Utilities;
use App\Services\CourseService;
use App\Services\Impl\CourseServiceImpl;
use Dedoc\Scramble\Attributes\PathParameter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{

    private CourseService $courseService;

    public function __construct(CourseServiceImpl $courseService) {
        $this->courseService = $courseService;
    }

    /**
     * Get All
     *
     * Through this route, it is possible to retrieve all courses registered in the system.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return response($this->courseService->getAllCourses(), 200);
    }

    /**
     * Create
     *
     * Through this route, it is possible to persist a new course in the system.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     *
     */
    public function store(Request $request)
    {
        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {

            $request->validate([
                'title' => ['required', 'string'],
                'description' => ['required', 'string'],
                'language' => [Rule::in(Language::cases()), 'required'],
                'level' => [Rule::in(Level::cases()), 'required'],
                'status' => [Rule::in(Status::cases()), 'required'],
                'start_date' => ['required', 'date'],
                'end_date' => 'date',
                'professor_id' => ['integer', 'exists:professors,id'],
            ]);

            return response($this->courseService->createCourse($request->all()), 201);
        } catch (ValidationException $exception) {
            return GlobalExceptionHandler::retrieveValidationExceptionResponse($exception);
        } catch (InvalidLangException|InvalidLevelException|InvalidStatusException|ProfessorNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Get by ID
     *
     * Through this route, it is possible to retrieve a course registered in the system by its ID.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the course being shown', type: 'integer', example: '1')]
    public function show(int $id)
    {
        try {
            return response($this->courseService->getCourseById($id), 200);
        } catch (CourseNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Update
     *
     * Through this route, it is possible to update an existing course by its ID, providing the target fields via body request.
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the course being updated', type: 'integer', example: '1')]
    public function update(int $id, Request $request)
    {
        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {

            $request->validate([
                'title' => 'string',
                'description' => 'string',
                'language' => Rule::in(Language::cases()),
                'level' => Rule::in(Level::cases()),
                'status' => Rule::in(Status::cases()),
                'start_date' => 'date',
                'end_date' => 'date',
            ]);

            return response($this->courseService->updateCourseById($id, $request->all()), 200);
        } catch (CourseNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        } catch (ValidationException $exception) {
            return response(['errors' => $exception->errors()], 422);
        }
    }

    /**
     * Delete
     *
     * Through this route, it is possible to delete a course by its ID.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the course being deleted', type: 'integer', example: '1')]
    public function destroy(int $id)
    {
        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {
            $this->courseService->deleteCourseById($id);
            return response(null, 204);
        } catch (CourseNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Enroll a Student
     *
     * Through this route, it is possible to enroll a student to an existing course.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the targeted course', type: 'integer', example: '1')]
    public function enrollStudent(int $id, Request $request) {

        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {

            $request->validate([
                'student_id' => ['required', 'integer', 'exists:students,id'],
            ]);

            $this->courseService->enrollStudent($id, $request->all());
            return response(['message' => 'Student enrolled successfully'], 201);
        } catch (CourseNotFoundException|StudentAlreadyEnrolledException|StudentNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        } catch (ValidationException $exception) {
            return GlobalExceptionHandler::retrieveValidationExceptionResponse($exception);
        }
    }

    /**
     * Unenroll a Student
     *
     * Through this route, it is possible to unenroll a student from an existing course.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the targeted course', type: 'integer', example: '1')]
    public function destroyEnrollStudent(int $id, Request $request) {

        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {
            $request->validate([
                'student_id' => ['required', 'integer', 'exists:students,id'],
            ]);

            $this->courseService->unenrollStudent($id, $request->all());
            return response(null, 204);
        } catch (CourseNotFoundException|StudentNotFoundException|StudentNotEnrolledException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        } catch (ValidationException $exception) {
            return GlobalExceptionHandler::retrieveValidationExceptionResponse($exception);
        }
    }

    /**
     * Get All Enrolled Students
     *
     * Through this route, it is possible to retrieve all enrolled students from an existing course.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the targeted course', type: 'integer', example: '1')]
    public function getEnrolledStudents(int $id) {
        try {
            return response($this->courseService->getEnrolledStudents($id), 200);
        } catch (CourseNotFoundException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Enroll Professor
     *
     * Through this route, it is possible to enroll a professor to an existing course.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the targeted course', type: 'integer', example: '1')]
    public function enrollProfessor(int $id, Request $request) {

        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {

            $request->validate([
                'professor_id' => ['required', 'integer', 'exists:professors,id'],
            ]);

            $this->courseService->enrollProfessor($id, $request->all());
            return response(null, 204);
        } catch (CourseNotFoundException|ProfessorNotFoundException|ProfessorAlreadyEnrolledException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        } catch (ValidationException $exception) {
            return GlobalExceptionHandler::retrieveValidationExceptionResponse($exception);
        }
    }

    /**
     * Unenroll Professor
     *
     * Through this route, it is possible to unenroll a professor that is enrolled to an existing course.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('id', description: 'The ID of the targeted course', type: 'integer', example: '1')]
    public function destroyEnrollProfessor(int $id) {

        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {
            $this->courseService->unenrollProfessor($id);
            return response(null, 204);
        } catch (CourseNotFoundException|ProfessorNotEnrolledException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }
}
