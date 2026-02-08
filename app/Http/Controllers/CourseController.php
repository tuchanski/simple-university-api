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
use Dedoc\Scramble\Attributes\Response;
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
    #[Response(401, 'Unauthenticated', type: 'array{message: "Unauthenticated"}')]
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
    #[Response(404, 'Not Found', type: 'array{message: "Professor not found"}')]
    #[Response(400, 'Bad Request', type: 'array{message: "Invalid property"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    #[Response(201, 'Course Created')]
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
                'start_date' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:end_date'],
                'end_date' => ['date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
                'professor_id' => ['integer', 'exists:professors,id'],
            ]);

            return response($this->courseService->createCourse($request->all()), 201);
        } catch (InvalidLangException|InvalidLevelException|InvalidStatusException|ProfessorNotFoundException|ValidationException $exception) {
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
    #[Response(404, 'Not Found', type: 'array{message: "Course not found"}')]
    #[Response(200, 'Course Found', type: 'array{id: "0", title: "string", description: "string", language: "pt-br",
     level: "beginner", status: "active", start_date: "2019-01-01", end_date: "2019-01-01"}')]
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
    #[Response(404, 'Not Found', type: 'array{message: "Course not found"}')]
    #[Response(400, 'Bad Request', type: 'array{message: "Invalid property"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    #[Response(200, 'Course Updated', type: 'array{message: "Course updated successfully"}')]
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
                'start_date' => ['date', 'date_format:Y-m-d', 'before_or_equal:end_date'],
                'end_date' => ['date', 'date_format:Y-m-d', 'after_or_equal:start_date']
            ]);

            $this->courseService->updateCourseById($id, $request->all());

            return response(['message' => 'Course updated successfully'], 200);
        } catch (CourseNotFoundException|ValidationException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
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
    #[Response(404, 'Not Found', type: 'array{message: "Course not found"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
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
    #[Response(404, 'Not Found', type: 'array{message: "Entity not found"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    #[Response(201, 'Student Enrolled', type: 'array{message: "Student enrolled successfully"}')]
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
        } catch (CourseNotFoundException|StudentAlreadyEnrolledException|StudentNotFoundException|ValidationException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
        }
    }

    /**
     * Unenroll a Student
     *
     * Through this route, it is possible to unenroll a student from an existing course.
     *
     * @param int $courseId
     * @param int $studentId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    #[PathParameter('courseId', description: 'The ID of the targeted course', type: 'integer', example: '1')]
    #[PathParameter('studentId', description: 'The ID of the targeted student', type: 'integer', example: '1')]
    #[Response(404, 'Not Found', type: 'array{message: "Entity not found"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    #[Response(400, 'Bad Request', type: 'array{message: "Invalid property"}')]
    public function destroyEnrollStudent(int $courseId, int $studentId) {

        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {
            $this->courseService->unenrollStudent($courseId, $studentId);
            return response(null, 204);
        } catch (CourseNotFoundException|StudentNotFoundException|StudentNotEnrolledException|ValidationException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
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
    #[Response(404, 'Not Found', type: 'array{message: "Course not found"}')]
    #[Response(200, 'Enrolled Students')]
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
    #[Response(404, 'Not Found', type: 'array{message: "Entity not found"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    #[Response(400, 'Bad Request', type: 'array{message: "Invalid property"}')]
    #[Response(201, 'Professor Enrolled', type: 'array{message: "Professor enrolled successfully"}')]
    public function enrollProfessor(int $id, Request $request) {

        if (!Utilities::isAuthUserAdmin()) {
            return response(['message' => 'Unauthorized'], 401);
        }

        try {

            $request->validate([
                'professor_id' => ['required', 'integer', 'exists:professors,id'],
            ]);

            $this->courseService->enrollProfessor($id, $request->all());
            return response(['message' => 'Professor enrolled successfully'], 201);
        } catch (CourseNotFoundException|ProfessorNotFoundException|ProfessorAlreadyEnrolledException|ValidationException $exception) {
            return GlobalExceptionHandler::retrieveResponse($exception);
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
    #[Response(404, 'Not Found', type: 'array{message: "Course not found"}')]
    #[Response(401, 'Unauthorized', type: 'array{message: "Unauthorized"}')]
    #[Response(400, 'Bad Request', type: 'array{message: "Invalid property"}')]
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
